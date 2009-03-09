<?php

class User_model extends Model {

	function User_model(){
		parent::Model();
	}
	//---------------------
	function isAuth(){
		if($u=$this->session->userdata('username')){
			return $u;
		}else{ return false; }
	}
	function validate($user,$pass){
		$ret=$this->getUser($user);
		return (isset($ret[0]) && $ret[0]->password==md5($pass)) ? true : false;
	}
	function logStatus(){
		//piece to handle the login/logout
		$u=$this->isAuth();
		$lstr=($u) ? '<a href="/user/main">'.$u.'</a> <a href="/user/logout">[logout]</a>':'<a href="/user/login">login</a>';
		$this->template->write('logged',$lstr);
	}
	function isSiteAdmin(){
		return ($this->session->userdata('admin')==1) ? true : false;
	}
	function isAdminEvent($rid){
		if($this->isAuth()){
			$uid=$this->session->userdata('ID');
			$q=$this->db->get_where('user_admin',array('uid'=>$uid,'rid'=>$rid,'rtype'=>'event'));
			$ret=$q->result();
			return (isset($ret[0]->ID) || $this->isSiteAdmin()) ? true : false;
		}else{ return false; }
	}
	function isAdminTalk($tid){
		if($this->isAuth()){
			$ad=false;
			$uid=$this->session->userdata('ID');
			$q=$this->db->get_where('user_admin',array('uid'=>$uid,'rid'=>$tid,'rtype'=>'talk'));
			$ret=$q->result();
			//return (isset($ret[0]->ID)) ? true : false;
			if(isset($ret[0]->ID)){ $ad=true; }
			
			//also check to see if the user is an admin of the talk's event
			$ret=$this->talks_model->getTalks($tid); //print_r($ret);
			if(isset($ret[0]->event_id) && $this->isAdminEvent($ret[0]->event_id)){ $ad=true; }
			return $ad;
		}else{ return false; }
	}
	//---------------------
	function updateUserInfo($uid,$arr){
		$this->db->where('ID',$uid);
		$this->db->update('user',$arr);
	}
	//---------------------
	function getUser($in){
		if(is_numeric($in)){
			$q=$this->db->get_where('user',array('ID'=>$in));
		}else{ 
			$w="username='".$in."'";
			$q=$this->db->get_where('user',$w);
		}
		return $q->result();
	}
	function getUserByEmail($in){
		$q=$this->db->get_where('user',array('email'=>$in));
		return $q->result();
	}
	function getAllUsers(){
		$q=$this->db->get('user');
		return $q->result();
	}
	function getOtherUserAtEvt($uid,$limit=15){
		//find speakers (users attending too?) that have spoken at conferences this speaker did too
		$other_speakers=array();
		$sql=sprintf("
			select
				distinct u.ID as user_id,
				t.event_id,
				u.username,
				u.full_name
			from
				user u,
				user_admin ua,
				talks t
			where
				ua.uid=u.ID and ua.rtype='talk' and ua.rid=t.ID and
				t.event_id in (
					select 
						distinct it.event_id 
					from
						user_admin iua,
						talks it
					where
						iua.uid=%s and
						iua.rtype='talk' and
						iua.rid=it.ID
				) and
				u.ID!=%s
			order by rand()
			limit %s
		",$uid,$uid,$limit);
		$q=$this->db->query($sql);
		$ret=$q->result();
		foreach($ret as $k=>$v){ $other_speakers[$v->user_id]=$v; }
		return $other_speakers;
	}
	//-------------------
	function search($term,$start=null,$end=null){
		$sql=sprintf("
			select
				u.username,
				u.full_name,
				u.ID,
				(select count(ID) from user_admin where rtype='talk' and uid=u.ID) talk_count,
				(select count(ID) from user_attend where uid=u.ID) event_count
			from
				user u
			where
				username like '%%%s%%' or
				full_name like '%%%s%%'
		",$term,$term);
		$q=$this->db->query($sql);
		return $q->result();
	}
}
?>