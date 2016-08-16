<?php

//Function to send new event notification mailto admin
//June 21,2016
//July 27,2016: SMTP mail sending
function send_new_event_notification_to_admin($eventTag)
{
	if($eventTag !='')
	{
		$eventData = fetchEventDetailsBasedonEventTAG($eventTag);
		if (!empty($eventData)) 
		{
			$id						=	$eventData['id'];
			$name						=	$eventData['name'];
			$address					=	$eventData['address'];
			$date						=	$eventData['date'];
			$startTime				=	$eventData['startTime'];
			$endTime					=	$eventData['endTime'];
			$clientid				=	$eventData['clientid'];
			$poster					=	$eventData['poster'];
			$about					=	$eventData['about'];
			$category				=	$eventData['category'];
			$latitude				=	$eventData['map']['center']['latitude']	;
			$longitude				=	$eventData['map']['center']['longitude'];
			$city						=	$eventData['city'];
			$added_by				=	$eventData['added_by'];
			$added_on				=	$eventData['added_on'];
			$status					=	$eventData['status'];
			$read_only				=	$eventData['read_only'];
			$eventTagId				=	$eventData['eventTagId'];
			
			$eventHostInfo			=  fetch_user_information_from_id($clientid);
			$firstName				=	$eventHostInfo['firstName'];
			$lastName				=	$eventHostInfo['lastName'];
			$companyName			=	$eventHostInfo['company']['companyName'];
			
			//http://192.168.11.13/projects/entreprenity/api/addNewEvent
			//http://entreprenity.co/app/api/services/testmail.php
			$pathToFile				= "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$eventBaseURL			=  str_replace("api/finishThisEvent","others/event.php",$pathToFile);
			//$eventBaseURL		=  str_replace("api/services/emailServices.php","others/event.php",$pathToFile);
			
			$eventAprovURL			= $eventBaseURL."?tagged=".urlencode($eventTagId).'&action=accept';
			$eventRejectURL		= $eventBaseURL."?tagged=".urlencode($eventTagId).'&action=reject';
			
			ob_start();
			include('email_templates/newEventsNotify.php');
			$eventsNotify_template = ob_get_contents();			
			ob_end_clean();
						
			//$to = 'dominic@cliffsupport.com'; 
			$to = ADMINEMAIL; 
			$strSubject = EVENTREQ_CONST;
			$message	= $eventsNotify_template;
		
			include('sendmail/sendmail.php');	
			$mail->SetFrom(MS_SENTFROM, MS_SENTFROMNAME);
			$mail->Subject = ($strSubject);
			$mail->MsgHTML($message);
			$mail->AddAddress($to);
			//$mail->AddBCC(RECIPIENTEMAIL1, RECIPIENTNAME1);
			//$mail->AddBCC(RECIPIENTEMAIL2, RECIPIENTNAME2);
			$mail->AddAddress(RECIPIENTEMAIL1, RECIPIENTNAME1);
			//$mail->AddAddress(RECIPIENTEMAIL2, RECIPIENTNAME2);
			if($mail->Send()) 
			{
		 		return "Mail send successfully";
			} 
			else 
			{
		  		return $mail->ErrorInfo;
			}
	
	
		}
	}
}


/* To manage all the email notifications */
//July 27,2016: SMTP mail sending
function send_notification_mail($notification_array)
{
	
	$type = $notification_array['type'];	
	//To send follow notification email	
	if($type === 'follow')
	{		
		$following_username = $notification_array['following_username'];
		$followed_username = $notification_array['followed_username'];
		$to_email = $followed_email = $notification_array['followed_email'];	
	}
	//To send comment notification email	
	elseif($type === 'comment')
	{		
		$commentAuthorUsername = $notification_array['commentAuthorUsername'];
		$postAuthorUsername = $notification_array['postAuthorUsername'];
		$to_email = $postAuthorEmail = $notification_array['postAuthorEmail'];
	}
	//To send like notification email	
	elseif($type === 'like')
	{		
		$likerUsername = $notification_array['likerUsername'];
		$postAuthorUsername = $notification_array['postAuthorUsername'];
		$to_email = $postAuthorEmail = $notification_array['postAuthorEmail'];
	}
	
	ob_start();
	include('email_templates/notification.php');
	$notification_template = ob_get_contents();			
	ob_end_clean();
	
	//$to = 'dominic@cliffsupport.com'; 
	$to = $to_email; 
	$strSubject = NOTIFICATION_CONST;
	$message	= $notification_template;

	include('sendmail/sendmail.php');	
	$mail->SetFrom(MS_SENTFROM, MS_SENTFROMNAME);
	$mail->Subject = ($strSubject);
	$mail->MsgHTML($message);
	$mail->AddAddress($to);
	//$mail->AddBCC(RECIPIENTEMAIL1, RECIPIENTNAME1);
	//$mail->AddBCC(RECIPIENTEMAIL2, RECIPIENTNAME2);		
	//$mail->AddAddress(RECIPIENTEMAIL1, RECIPIENTNAME1);
	//$mail->AddAddress(RECIPIENTEMAIL2, RECIPIENTNAME2);
	if($mail->Send()) 
	{
 		return "Mail send successfully";
	} 
	else 
	{
  		return $mail->ErrorInfo;
	}

	
}

?>