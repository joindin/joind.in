<?php

abstract class ApiController {
	abstract public function handle($request, $db);

    public function getItemId($request) {
        // item ID
		if(!empty($request->url_elements[3]) && is_numeric($request->url_elements[3])) {
            $item_id = (int)$request->url_elements[3];
            return $item_id;
		}
        return false;
    }

    public function getVerbosity($request) {
        // verbosity
        if(isset($request->parameters['verbose'])
                && $request->parameters['verbose'] == 'yes') {
            $verbose = true;
        } else {
            $verbose = false;
        }
        return $verbose;
    }

    public function getStart($request) {
        return (int)$request->parameters['start'];
         
    }
    
    public function getResultsPerPage($request) {
        return (int)$request->parameters['resultsperpage'];
    }
}
