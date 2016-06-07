<?php

/* To manage all the email notifications */
function send_notification_mail($notification_array){
	
	$type = $notification_array['type'];
	
	//To send follow notification email	
	if($type === 'follow'){
		
		$following_username = $notification_array['following_username'];
		$followed_username = $notification_array['followed_username'];
		$to_email = $followed_email = $notification_array['followed_email'];
	
	}
	//To send comment notification email	
	elseif($type === 'comment'){
		
		$commentAuthorUsername = $notification_array['commentAuthorUsername'];
		$postAuthorUsername = $notification_array['postAuthorUsername'];
		$to_email = $postAuthorEmail = $notification_array['postAuthorEmail'];
	}
	//To send like notification email	
	elseif($type === 'like'){
		
		$likerUsername = $notification_array['likerUsername'];
		$postAuthorUsername = $notification_array['postAuthorUsername'];
		$to_email = $postAuthorEmail = $notification_array['postAuthorEmail'];
	}
	
	ob_start();
	include('email_templates/notification.php');
	$notification_template = ob_get_contents();			
	ob_end_clean();
	
	
	
	$to = 'dominic@cliffsupport.com'; 
	/*$to = $to_email; //please uncomment this when in live*/
	$strSubject = "Notification mail";
	$message =  $notification_template;              
	$headers = 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
	$headers .= "From: eprty@test.com"; 
	
	if(mail($to, $strSubject, $message, $headers)){
		return 'Mail send successfully';
	}else{
		return 'Could not send email';
	} 
	
}

?>