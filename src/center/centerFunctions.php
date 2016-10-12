<?php

//Upcoming Event List
function getUpcomingEventsForCenter($location)
{
	//SELECT * FROM entrp_events where clientid IN (SELECT clientid FROM client_profile where client_location=14)
	$data = array();		
	$today = date('Y-m-d'); 
	$qry="SELECT * FROM entrp_events 
			WHERE clientid IN (SELECT clientid FROM client_profile WHERE client_location=".$location.") AND status=1 and event_date>='".$today."' ";
	$res = getData($qry);
   $count_res = mysqli_num_rows($res);
	if($count_res > 0)
   {
   	$i=0;
   	while($row = mysqli_fetch_array($res))
      {
      	$data[$i]['id'] 				= $row['id'];
      	$data[$i]['clientid'] 		= $row['clientid'];
      	$data[$i]['eventName'] 		= $row['eventName'];
      	$data[$i]['eventTagId'] 	= $row['eventTagId'];
      	$data[$i]['category'] 		= $row['category'];
      	$data[$i]['address']			= $row['address'];
			$data[$i]['description']	= $row['description'];			      	
			
			if($row['poster']!='')
			{
				$data[$i]['poster']		=	$row['poster'];
			}
			else
			{
				$data[$i]['poster']		=	'assets/img/events/events-default.jpg';
			}	      	
			
			$data[$i]['city'] 		  	= $row['city'];	
			$data[$i]['eventDate'] 		= $row['event_date'];	
			$data[$i]['eventTime'] 		= $row['event_time'];	
			$data[$i]['eventDateTime'] = $row['event_date_time'];	
			$data[$i]['startTime'] 		= $row['start_time'];	
			$data[$i]['endTime'] 		= $row['end_time'];				

			$eventDateTimestamp = strtotime($row['event_date']);
			$eventDayFormatted = date('l, F d', $eventDateTimestamp);  //Saturday, January 30
			$monthFormatted = date('F', $eventDateTimestamp);  //January
			$eventDFormatted = date('d', $eventDateTimestamp);  //30
			
			$data[$i]['eventDayFormatted'] 		= $eventDayFormatted;	
			$data[$i]['monthFormatted'] 		  	= $monthFormatted;	
			$data[$i]['eventDFormatted'] 		  	= $eventDFormatted;	
			
			$eventStartFormatted			= date('h:i a', strtotime($row['start_time']));
			$eventEndFormatted			= date('h:i a', strtotime($row['end_time']));
			
			$data[$i]['eventStartFormatted'] 	= $eventStartFormatted;	
			$data[$i]['eventEndFormatted'] 		= $eventEndFormatted;	
			$i++;
		}
   }
   return $data;
}


//Function to get user's company name
function getCompanyName($entrpID)
{
	$companyName 	= 'Not Specified';
	//SELECT company_profiles.company_name FROM entrp_company_members LEFT JOIN company_profiles ON company_profiles.id=entrp_company_members.companyid WHERE entrp_company_members.clientid=1
	$qry = "SELECT company_profiles.company_name 
			  FROM entrp_company_members 
			  LEFT JOIN company_profiles ON company_profiles.id=entrp_company_members.companyid 
			  WHERE entrp_company_members.clientid=".$entrpID."";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      while($row = mysqli_fetch_array($res))
      {
      	$companyName 	= $row['company_name'];
		}
   } 
	return $companyName;
}


//Function to login a user into center
function logUserIntoThisCenter($clientid,$vofClientId,$locId)
{
	date_default_timezone_set('UTC');
	$loginDate=date("Y-m-d");
	$loginDateTime=date("Y-m-d H:i:s");
	$status=1;
	$qry="INSERT INTO entrp_center_login(entrpID,voffID,locID,loginDate,checkIn,status) VALUES(".$clientid.",".$vofClientId.",".$locId.",'".$loginDate."','".$loginDateTime."',".$status.") ";
	setData($qry);
}

//Function to fetch user info using qrcode token
function fetchUserInfoUsingQRCode($qrCode)
{
	$data = array();		
	$qry="SELECT L.*,C.avatar
			FROM entrp_login as L
			LEFT JOIN client_profile AS C ON L.clientid=C.clientid
			WHERE L.qrCode='".$qrCode."'
	      ";
	$res = getData($qry);
   $count_res = mysqli_num_rows($res);
	if($count_res > 0)
   {
   	while($row = mysqli_fetch_array($res))
      {
      	$data['clientid'] 	= $row['clientid'];
      	$data['username'] 	= $row['username'];
      	$data['email'] 		= $row['email'];
      	$data['firstname'] 	= $row['firstname'];
      	$data['lastname'] 	= $row['lastname'];
      	$data['voffStaff']	= $row['voff_staff'];
			$data['vofClientId']	= $row['vof_clientid'];			      	
			
			if($row['avatar']!='')
			{
				$data['avatar']	=	$row['avatar'];
			}
			else
			{
				$data['avatar']	=	'assets/img/members/member-default.jpg';
			}	      	
			
			$data['success'] = 'true';
		}
   }
   return $data;

}


//Function to check whether user qrcode is valid or not
function validateUserQRCode($qrCode)
{
	$qry = "SELECT * FROM entrp_login WHERE qrCode='".$qrCode."'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      return 1;
   } 
   else 
   {
      return 0;
   }
}


?>