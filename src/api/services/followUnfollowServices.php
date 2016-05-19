<?php



//***** EVENT GOING/NOT GOING From EVENT DIRECTORY PAGE *****

//Function to mark going for an event (from event directory)
//May 13,2016
function goingToEvent()
{
	$data= array();  
   $eventId=validate_input($_POST['event']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_event_attendees(eventid,clientid,date_time) VALUES(".$eventId.",".$my_session_id.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$data['joining']=true;
	}
	else
	{
		$data['joining']=false;
	}
	return $data;

}

//Function to mark going for an event (from event directory)
//May 13,2016
function notGoingToEvent()
{
	$data= array();  
   $eventId=validate_input($_POST['event']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="DELETE FROM entrp_event_attendees WHERE eventid=".$eventId." AND clientid=".$my_session_id."
	      ";
	if(setData($qry))
	{
		$data['joining']=false;
	}
	else
	{
		$data['joining']=true;
	}
	return $data;
}


//***** EVENT GOING/NOT GOING From EVENT DETAILS PAGE *****


//Function to mark going for an event
//May 12,2016
function goingForEvent()
{
	$data= array();
	   
   $eventId=validate_input($_POST['event']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_event_attendees(eventid,clientid,date_time) VALUES(".$eventId.",".$my_session_id.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$data=getEventFromEventID($eventId);
		$data['joining']=true;
	}
	else
	{
		$data=getEventFromEventID($eventId);
		$data['joining']=false;
	}
	return $data;

}

//Function to mark not going for an event
//May 12,2016
function notGoingForEvent()
{
	$data= array();
	   
   $eventId=validate_input($_POST['event']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="DELETE FROM entrp_event_attendees WHERE eventid=".$eventId." AND clientid=".$my_session_id."
	      ";
	if(setData($qry))
	{
		$data=getEventFromEventID($eventId);
		$data['joining']=false;
	}
	else
	{
		$data=getEventFromEventID($eventId);
		$data['joining']=true;
	}
	return $data;

}


//***** CHECK USER/COMPANY FOLLOW*****

//Function to check whether the current user follows this company or not
//May 12,2016
function doIFollowThisCompany($my_session_id,$companyid)
{
	$qry="SELECT id FROM entrp_company_follows WHERE clientid=".$my_session_id." AND companyid=".$companyid."";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	return true;
   }
   else
	{
		return false;
	}
}

//Function to check whether the current user follows the other user or not
//May 11,2016
function doIFollowThisUser($my_session_id,$clientid)
{
  	$qry="SELECT id FROM entrp_user_follows WHERE clientid=".$my_session_id." AND follows=".$clientid."";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	return true;
   }
   else
	{
		return false;
	}
}


//***** COMPANY FROM PROFILE *****

//Function to follow a company from company profile
//May 12,2016
function followThisCompany()
{
	$data= array();
	   
   $companyUserName=validate_input($_POST['company']);
	$companyid=getCompanyIdfromCompanyUserName($companyUserName);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_company_follows(clientid,companyid,date_time) VALUES(".$my_session_id.",".$companyid.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$data=fetch_company_information_from_companyid($companyid);
		$data['followed']=true;
	}
	else
	{
		$data=fetch_company_information_from_companyid($companyid);
		$data['followed']=false;
	}
	return $data;
}

//Function to un-follow a company from company profile
//May 12,2016
function unfollowThisCompany()
{
	$data= array();
   
   $companyUserName=validate_input($_POST['company']);
	$companyid=getCompanyIdfromCompanyUserName($companyUserName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="DELETE FROM entrp_company_follows WHERE clientid=".$my_session_id." AND companyid=".$companyid."";
	      
	if(setData($qry))
	{
		$data=fetch_company_information_from_companyid($companyid);
		$data['followed']=false;
	}
	else
	{
		$data=fetch_company_information_from_companyid($companyid);
		$data['followed']=true;
	}
	return $data;
}


//***** COMPANY FROM DIRECTORY *****

//Function to follow a company from company directory
//May 12,2016
function followCompany()
{
	$data= array();  
   $companyid=validate_input($_POST['company']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_company_follows(clientid,companyid,date_time) VALUES(".$my_session_id.",".$companyid.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$data['followed']=true;
	}
	else
	{
		$data['followed']=false;
	}
	return $data;

}

//Function to un-follow a company from company directory
//May 12,2016
function unfollowCompany()
{
	$data= array();
   $companyid=validate_input($_POST['company']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="DELETE FROM entrp_company_follows WHERE clientid=".$my_session_id." AND companyid=".$companyid."";
	      
	if(setData($qry))
	{
		$data['followed']=false;
	}
	else
	{
		$data['followed']=true;
	}
	return $data;

}

//***** MEMBER FROM DIRECTORY *****

//Function to follow a user from member directory
//May 11,2016
function followUser()
{
	$data= array();	   
   $clientid=validate_input($_POST['user']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_user_follows(clientid,follows,follow_date_time) VALUES(".$my_session_id.",".$clientid.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$data['followed']=true;
	}
	else
	{
		$data['followed']=false;
	}
	return $data;
}

//Function to unfollow a user from member directory
//May 11,2016
function unfollowUser()
{
 	$data= array();
   $clientid=validate_input($_POST['user']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="DELETE FROM entrp_user_follows WHERE clientid=".$my_session_id." AND follows=".$clientid."";
	      
	if(setData($qry))
	{
		$data['followed']=false;
	}
	else
	{
		$data['followed']=true;
	}
	return $data;
}

//***** MEMBER FROM PROFILE *****

//Function to follow a user from his/her profile view
//May 11,2016
function followThisUser()
{
	$data= array();
	   
   $userName=validate_input($_POST['user']);
	$clientid=getUserIdfromUserName($userName);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_user_follows(clientid,follows,follow_date_time) VALUES(".$my_session_id.",".$clientid.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$data=fetch_user_information_from_id($clientid);
		$data['followed']=true;
	}
	else
	{
		$data=fetch_user_information_from_id($clientid);
		$data['followed']=false;
	}
	return $data;
}


//Function to un-follow a user from his/her profile view
//May 11,2016
function unfollowThisUser()
{
	$data= array();
   
   $userName=validate_input($_POST['user']);
	$clientid=getUserIdfromUserName($userName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="DELETE FROM entrp_user_follows WHERE clientid=".$my_session_id." AND follows=".$clientid."";
	      
	if(setData($qry))
	{
		$data=fetch_user_information_from_id($clientid);
		$data['followed']=false;
	}
	else
	{
		$data=fetch_user_information_from_id($clientid);
		$data['followed']=true;
	}
	return $data;
}





?>