<?php

class TalksController extends ApiController {
    public function handle($request, $db) {
        if($request->verb == 'GET') {
            return $this->getAction($request, $db);
        } elseif($request->verb == 'POST') {
            return $this->postAction($request, $db);
        } else {
            throw new BadRequestException("method not supported");
        }
        return false;
    }

	protected function getAction($request, $db) {
        $talk_id = $this->getItemId($request);

        // verbosity
        $verbose = $this->getVerbosity($request);

        // pagination settings
        $start = $this->getStart($request);
        $resultsperpage = $this->getResultsPerPage($request);

        if(isset($request->url_elements[4])) {
            // sub elements
            if($request->url_elements[4] == "comments") {
                $comment_mapper = new TalkCommentMapper($db, $request);
                $list = $comment_mapper->getCommentsByTalkId($talk_id, $resultsperpage, $start, $verbose);
            }
        } else {
            if($talk_id) {
                $mapper = new TalkMapper($db, $request);
                $list = $mapper->getTalkById($talk_id, $verbose);
            } else {
                // listing makes no sense
                return false;
            }
        }

        return $list;
	}

    protected function postAction($request, $db) {
        $talk_id = $this->getItemId($request);

        if(isset($request->url_elements[4])) {
            // sub elements
            if($request->url_elements[4] == "comments") {
                // no anonymous comments over the API
                if(!isset($request->user_id) || empty($request->user_id)) {
                    throw new BadRequestException('You must log in to comment');
                }

                $comment = $request->getParameter('comment');
                if(empty($comment)) {
                    throw new BadRequestException('The field "comment" is required');
                }

                $rating = $request->getParameter('rating');
                if(empty($rating)) {
                    throw new BadRequestException('The field "rating" is required');
                }

                $comment_mapper = new TalkCommentMapper($db, $request);
                $data['user_id'] = $request->user_id;
                $data['talk_id'] = $talk_id;
                $data['comment'] = $comment;
                $data['rating'] = $rating;

                $comment_mapper->save($data);
                header("Location: " . $request->base . $request->path_info);
                exit;
            }
        } else {
            throw new Exception("method not yet supported - sorry");
        }
    }
}
