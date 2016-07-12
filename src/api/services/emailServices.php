<?php

//Function to send new event notification mailto admin
//June 21,2016
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
			$pathToFile				= "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$eventBaseURL			=  str_replace("api/addNewEvent","others/event.php",$pathToFile);
			
			$eventAprovURL			= $eventBaseURL."?tagged=".urlencode($eventTagId).'&action=accept';
			$eventRejectURL		= $eventBaseURL."?tagged=".urlencode($eventTagId).'&action=reject';
			
			ob_start();
			include('email_templates/newEventsNotify.php');
			$eventsNotify_template = ob_get_contents();			
			ob_end_clean();
			
			$to = 'sean@flexiesolutions.com'; 
			//$to = 'dominic@cliffsupport.com'; 
			/*$to = $to_email; //please uncomment this when in live*/
			$strSubject = "New Event Request";
			$message =  $eventsNotify_template;              
			$headers = 'MIME-Version: 1.0'."\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
			$headers .= "From: eprty@test.com"; 
			
			if(mail($to, $strSubject, $message, $headers))
			{
				return 'Mail send successfully';
			}
			else
			{
				return 'Could not send email';
			} 
		}
	}
}


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
	
	
	
	//$to = 'dominic@cliffsupport.com'; 
	$to = $to_email; //please uncomment this when in live
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