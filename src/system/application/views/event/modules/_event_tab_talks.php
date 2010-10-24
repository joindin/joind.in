<div id="talks">
<?php if (count($by_day) == 0): ?>
	<?php $this->load->view('msg_info', array('msg' => 'No talks available at the moment.')); ?>
<?php else: 
	if(isset($track_filter)){
		echo '<span style="font-size:13px">Sessions for track <b>'.$track_data->track_name.'</b></span>';
		echo ' <span style="font-size:11px"><a href="/event/view/'.$event_detail->ID.'">[show all sessions]</a></span>';
		echo '<br/><br/>';
	}
	?>
	<table summary="" cellpadding="0" cellspacing="0" border="0" width="100%" class="list">
    <?php
	$total_comment_ct   = 0;
	$session_rate	    = 0;
    foreach ($by_day as $talk_section_date=>$talk_section_talks): // was $k=>$v
        $ct = 0;
    ?>
    	<tr>
    		<th colspan="4">
    			<h4 id="talks"><?php echo date('M d, Y', $talk_section_date); ?></h4>
    		</th>
    	</tr>
    	<?php foreach($talk_section_talks as $ik=>$talk): 
//print_r($talk); echo '<br/><br/>';

	    $session_rate+=$talk->rank;
		
		if(isset($track_filter)){
			//Filter to the track ID
			if(empty($talk->tracks)){ 
				// If there's no track ID on the talk, don't show it
				continue; 
			}else{
				// There are tracks on the session, let's see if any match...
				$filter_pass=false;
				foreach($talk->tracks as $talk_track){
					if($talk_track->ID==$track_filter){ $filter_pass=true; }
				}
				if(!$filter_pass){ continue; }
			}
		}
	?>
    	<tr class="<?php echo ($ct%2==0) ? 'row1' : 'row2'; ?>">
    		<td>
    			<?php $type = !empty($talk->tcid) ? $talk->tcid : 'Talk'; ?>
    			<span class="talk-type talk-type-<?php echo strtolower(str_replace(' ', '-', $type)); ?>" title="<?php echo escape($type); ?>"><?php echo escape(strtoupper($type)); ?></span>
    		</td>
    		<td>
    			<a href="/talk/view/<?php echo $talk->ID; ?>"><?php echo escape($talk->talk_title); ?></a>
				<?php
					if($talk->display_time != '00:00') {echo '(' . $talk->display_time . ')';}
				?>
    		</td>
    		<td>
    			<?php
				$speaker_list=array();
				foreach($talk->speaker as $sp){
					if(isset($claims[$sp->talk_id])){
						foreach($claims[$sp->talk_id] as $c=>$claim){
							//If it matches exactly or if there's only one claim
							if(
								$c==$sp->speaker_name || 
								(count($claims[$sp->talk_id])==1 && count($talk->speaker)==1) && 
								$claim['rcode']!='pending'
							){
								$speaker_list[]='<a href="/user/view/'.$claim['uid'].'">'.$sp->speaker_name.'</a>';
							}elseif(count($talk->speaker)>1){ $speaker_list[]=$sp->speaker_name; }
						}
					}else{ $speaker_list[]=$sp->speaker_name; }
				}
				if(empty($speaker_list)){ $speaker_list[]='None'; }
				echo implode(', ',$speaker_list);
				?>
    		</td>
    		<td>
				<a class="comment-count" href="/talk/view/<?php echo $talk->ID; ?>/#comments"><?php echo $talk->comment_count; ?></a>
			</td>
    	</tr>
    <?php
    	    $ct++;
	    $total_comment_ct+=$talk->comment_count;
        endforeach;
    endforeach;
    ?>
    </table>
<?php endif; ?>
</div>