<?php
//echo '<pre>'; print_r($talks); echo '</pre>';
//echo '<pre>'; print_r($events); echo '</pre>';
?>
<br/>
<table cellpadding="0" cellspacing="0" border="0" width="98%">
<tr>
<td width="60%">
<img src="/inc/img/curr_up.gif"/><br/><br/>
<?php
foreach($events as $k=>$v){
	echo '<div>';
	echo '<a style="font-weight:bold;font-size:12px" href="/event/view/'.$v->ID.'">'.$v->event_name.'</a><br/>';
	echo date('m.d.Y',$v->event_start).' - '.date('m.d.Y',$v->event_end).'<br/>';
	$p=explode(' ',$v->event_desc);
	$str='';
	for($i=0;$i<20;$i++){ if(isset($p[$i])){ $str.=$p[$i].' '; } } echo trim($str).'...';
	echo '</div><br/>';
}
echo '<br/>';
echo '<img src="/inc/img/pop_talk.gif"/><br/><br/>';
echo '<table cellpadding="3" cellspacing="0" border="0">';
foreach($talks as $k=>$v){
	$ccount=($v->ccount>1) ? $v->ccount.' comments' : '1 comment';
	echo '<tr><td align="right" valign="top">';
	for($i=1;$i<=$v->tavg;$i++){
		echo '<img id="rate_'.$i.'" src="/inc/img/thumbs_up.jpg" height="20" border="0"/>';
	}
	echo '<td/>';
	echo '<td><a style="font-size:12px" href="/talk/view/'.$v->ID.'">'.$v->talk_title.'</a><br/><span style="color:#999999;font-size:10px">('.$ccount.')</span></td></tr>';
}
echo '</table>';
?>
</td>
<?php if(isset($logged) && !$logged){ ?>
<td width="40%" valign="top" align="right">
	<div>
	<?php
	echo form_open('/user/login');
	echo '<table cellpadding="3" cellspcing="0" border="0">';
	echo '<tr><td colspan="2"><img src="/inc/img/login.gif"/></td></tr>';
	echo '<tr><td>User:</td><td>'.form_input('user').'</td></tr>';
	echo '<tr><td>Pass:</td><td>'.form_password('pass').'</td></tr>';
	echo '<tr><td align="right" colspan="2">'.form_submit('sub','login').'</td></tr>';
	echo '</table>';
	form_close();
	?>
	<span style="font-size:10px">Need an account? <a href="/user/register">Register now!</a></span>
	</div>
</td>
<?php } ?>
</tr>
</table>
<br/>
<center>
<script type="text/javascript"><!--
google_ad_client = "pub-2135094760032194";
/* 468x60, created 11/5/08 */
google_ad_slot = "4582459016"; google_ad_width = 468; google_ad_height = 60; //-->
</script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</center>