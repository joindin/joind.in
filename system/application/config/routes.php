<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "main";
$route['scaffolding_trigger'] = "";

//$route['event/([^add|view|edit|delete])'] = 'event/cust/$1';
$route['event/add']='event/add';
$route['event/submit']='event/submit';
$route['event/calendar/(:any)']	= 'event/calendar/$1';
$route['event/export/(:num)']	= 'event/export/$1';
$route['event/edit/(:num)']		= 'event/edit/$1';
$route['event/view/(:num)']		= 'event/view/$1';
$route['event/delete/(:num)']	= 'event/delete/$1';
$route['event/codes/(:num)']	= 'event/codes/$1';
//now our catch all...
$route['event/(:any)']			= 'event/cust/$1';
$route['(:num)']				= 'talk/view/$1';


/* End of file routes.php */
/* Location: ./system/application/config/routes.php */