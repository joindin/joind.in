<?php

class DefaultController {
	public function handle($request, $db) {
        $retval = array();

        // just add the available methods, with links
        $retval['events'] = 'http://' . $request->host . '/v2/events';
        $retval['talks'] = 'http://' . $request->host . '/v2/talks';

        return $retval;
	}
}