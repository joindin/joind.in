<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Service {
	
	var $CI	= null;
	var $public_actions = array(
		'event/attend'=>array('logged')
	);
	
	function Service(){
		$this->CI=&get_instance();
	}
	//---------------------
	function handle($type,$data){
		$this->CI->load->model('user_admin_model');
		$data=trim($data);
		
		$xml=$this->parseReqXML($data);
		if(!$xml){ return array('output'=>'msg','msg'=>'Invalid request!'); }
		$rtype	= (string)$xml->action['type'];
		
		$public=($this->isPublicAction($type,$rtype)) ? true : false;
		
		//check to be sure they're authed and that they can execute this action type
		if($this->checkAuth($xml) || $public){
			ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.BASEPATH.'application/libraries/wsactions');
			$ws_root=$_SERVER['DOCUMENT_ROOT'].'/system/application/libraries/wsactions/';
			
			if($public==false){
				//get user information
				$uinfo=$this->CI->user_model->getUser($xml->auth->user); //echo 'uninfo: '; print_r($uinfo);
				$uid	= (int)$uinfo[0]->ID;
			}else{
				//check our "key" to prevent abuse
				//split out these values...
				foreach(explode('&',$_SERVER['QUERY_STRING']) as $k=>$v){ 
					$x=explode('=',$v); $_GET[$x[0]]=$x[1]; 
				}
				
				$this->CI->load->helper('reqkey');
				$reqk=$_GET['reqk'];
				$seck=$_GET['seck'];
				if(checkReqKey($reqk,$seck)){ 
					echo 'woo!';
				}else{ $ret=array('output'=>'msg','msg'=>'Access denied!'); }
			}

			if($public || $this->CI->user_admin_model->hasPerm($uid,0,$rtype)){
				//run our given action	
				//$this->CI->load->library((string)$xml->action['type']);
				$class=ucwords(strtolower((string)$xml->action['type']));
				$class_file=$ws_root.$type.'/'.$class.'.php';
				$out=(string)$xml->action['output'];
				if(is_file($class_file)){
					include_once($class_file);
					$obj=new $class($xml);
					$ret['data']=$obj->run();
					$ret['output']=(!empty($out)) ? $out : 'xml';
				}else{ $ret=array('msg'=>'Invalid action!'); }
			}else{
				$ret=array('output'=>'msg','msg'=>'Access denied!');
			}
		}else{ 
			$ret=array('output'=>'msg','msg'=>'Authentication failed');
		}
		return $ret;
	}
	function parseReqXML($xml){ error_log($xml);
		$ret_xml=null;
		try {
			$ret_xml=simplexml_load_string($xml); //print_r($ret_xml);
		}catch(Exception $e){ /* exceptions */ }
		return $ret_xml;
	}
	function checkAuth($obj){
		$this->CI->load->model('user_model');
		if($obj->auth->user){
			$uinfo=$this->CI->user_model->getUser($obj->auth->user); //echo 'uninfo: '; print_r($uinfo);
			return (md5($obj->auth->pass)==$uinfo[0]->password && $uinfo[0]->api_access) ? true : false;
		}else{ return false; }
	}
	// check to see if our given action is one that doesnt need a user/pass
	function isPublicAction($rtype,$raction){
		$find=$rtype.'/'.$raction; //echo $find;
		return (array_key_exists($find,$this->public_actions)) ? true : false;
	}
	//------------------------
	function checkPublicRules($rtype,$raction){
		$find=$rtype.'/'.$raction;
		if(array_key_exists($find,$this->public_actions)){
			$pass	= true;
			$rules	= $this->public_actions[$find]; //print_r($rules);
			foreach($rules as $k=>$v){
				$ret=$this->{'rule_'.$v}();
				if(!$ret){ $pass=false; }
			}
			return $pass;
		}else{ return false; }
	}
	function rule_logged(){
		//check to see if they are logged in or not
		return ($this->CI->user_model->isAuth()) ? true : false;
	}
}
?>