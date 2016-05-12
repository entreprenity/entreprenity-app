<?php


//Function to fetch members directory
// April 13,2015
function getMembers()
{
	$records=1;
	$start=0;
	$limit=12;
	$end=12;
	$member_default='member-default.jpg';
	if(isset($_GET['page']))
	{
		$records=$_GET['page'];
		if($records==1)
		{
			$start=0;
			$end=12;
		}
		else if($records==1)
		{
			$start=$limit+$records;
			$end=$end+$limit;
		}
		else
		{
			$start=($limit*$records)+1;
			$end=$limit*$records;
		}
		
	}
	
	$limit=$start * $records;
	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$data= array();	
	$qry="SELECT CI.clientid,CI.firstname,CI.lastname,CI.username,CP.designation,CP.company_name,CP.avatar,LI.location_desc AS city 
	      FROM entrp_login AS CI 
	      LEFT JOIN client_profile AS CP ON CP.clientid=CI.clientid
	      LEFT JOIN location_info as LI ON LI.id=CP.client_location
	      WHERE CI.clientid!=".$my_session_id." 
	      ORDER BY CI.clientid ASC 
	      LIMIT $start ,$end
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	if(!empty($row['clientid']))
      	{
      		$data[$i]['id']				=	$row['clientid'];
      	}
      	else
      	{
      		$data[$i]['id']				=	"";
      	}
      	
      	if(!empty($row['firstname']))
      	{
      		$data[$i]['firstName']		=	$row['firstname'];
      	}
      	else
      	{
      		$data[$i]['firstName']		=	"";
      	}
      	
      	if(!empty($row['username']))
      	{
      		$data[$i]['userName']		=	$row['username'];
      	}
      	else
      	{
      		$data[$i]['userName']		=	"";
      	}
			
			if(!empty($row['lastname']))
      	{
      		$data[$i]['lastName']		=	$row['lastname'];
      	}
      	else
      	{
      		$data[$i]['lastName']		=	"";
      	}
			
			if(!empty($row['avatar']))
      	{
      		$data[$i]['avatar']			=	$row['avatar'];
      	}
      	else
      	{
      		$data[$i]['avatar']			=	$member_default;
      	}
			
			if(!empty($row['designation']))
      	{
      		$data[$i]['position']		=	$row['designation'];
      	}
      	else
      	{
      		$data[$i]['position']		=	"";
      	}
			
			if(!empty($row['company_name']))
      	{
      		$data[$i]['companyName']	=	$row['company_name'];
      	}
      	else
      	{
      		$data[$i]['companyName']	=	"";
      	}
			
			if(!empty($row['city']))
      	{
      		$data[$i]['city']				=	$row['city'];
      	}
      	else
      	{
      		$data[$i]['city']				=	"";
      	}
      	
      	$data[$i]['followed']=doIFollowThisUser($my_session_id,$data[$i]['id']);
      	
      	
			$i++;
      }	
   }
   else
   {
   	$data[$i]['id']				=	"";
		$data[$i]['firstName']		=	"";
		$data[$i]['lastName']		=	"";
		$data[$i]['avatar']			=	"";
		$data[$i]['position']		=	"";
		$data[$i]['companyName']	=	"";
		$data[$i]['city']				=	"";
		$data[$i]['userName']		=	"";
   }
	return $data;
}



//Function to fetch company directory
// April 13,2015
function getCompanies()
{	
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
			
	$qry="SELECT  CP.id,CP.clientid,CP.company_name,CP.description,CP.avatar,CP.company_username,LI.location_desc AS city 
			FROM company_profiles AS CP
			LEFT JOIN location_info as LI ON LI.id=CP.client_location ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']				=	$row['clientid'];
			$data[$i]['companyName']	=	$row['company_name'];
			$data[$i]['description']	=	$row['description'];
			$data[$i]['avatar']			=	$row['avatar'];
			$data[$i]['city']				=	$row['city'];
			$data[$i]['userName']		=	$row['company_username'];
			
			$data[$i]['followed']=doIFollowThisCompany($my_session_id,$data[$i]['id']);
			$i++;
      }	
   }
   else
   {
   	$data[$i]['id']				=	"";
		$data[$i]['companyName']	=	"";
		$data[$i]['description']	=	"";
		$data[$i]['avatar']			=	"";
		$data[$i]['city']				=	"";
		$data[$i]['userName']		=	"";
   }
	return $data;	
}



//Function to fetch events directory
// April 13,2015
function getEvents()
{
	$data= array();
	
	$qry="SELECT * FROM entrp_events";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']				=	$row['id'];
			$data[$i]['eventName']		=	$row['eventName'];
			$data[$i]['description']	=	$row['description'];
			$data[$i]['poster']			=	$row['poster'];
			$data[$i]['city']				=	$row['city'];
			$data[$i]['date']				=	$row['event_date'];
			$data[$i]['time']				=	$row['event_time'];
			$i++;
      }	
   }
   else
   {
   	$data[$i]['id']				=	"";
		$data[$i]['eventName']		=	"";
		$data[$i]['description']	=	"";
		$data[$i]['poster']			=	"";
		$data[$i]['city']				=	"";
		$data[$i]['date']				=	"";
		$data[$i]['time']				=	"";
   }
	
	return $data;
}



?>