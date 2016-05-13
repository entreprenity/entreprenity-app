<?php

/* Functions and services based on userid begins */


//Function to check going for this event or not
//May 13,2016
function goingForThisEventorNot($eventid)
{
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="SELECT id FROM entrp_event_attendees WHERE clientid=".$my_session_id." AND eventid=".$eventid."";
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

//Function to get event attendees from eventid
//May 12,2016
function getEventAttendeesFromEventID($eventid)
{
   	$i=0;
   	$member_default='assets/img/members/member-default.jpg';
   	$data2= array();
   	$qry2="SELECT entrp_event_attendees.clientid,entrp_login.firstname,entrp_login.lastname,client_profile.avatar,client_profile.designation,client_profile.city,client_profile.cover_pic 
				 FROM entrp_event_attendees 
				 LEFT JOIN entrp_login ON entrp_login.clientid=entrp_event_attendees.clientid 
				 LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
				 WHERE entrp_event_attendees.eventid=".$eventid."
				";
	   $res2=getData($qry2);
		$count_res2=mysqli_num_rows($res2);
		if($count_res2>0)
		{
			while($row2=mysqli_fetch_array($res2))
   		{
   			$data2[$i]['id']				=	$row2['clientid'];
   			$data2[$i]['firstName']		=	$row2['firstname'];
   			$data2[$i]['lastName']		=	$row2['lastname'];
   			if($row2['avatar']!='')
   			{
   				$data2[$i]['avatar']	=	$row2['avatar'];
   			}
   			else
   			{
   				$data2[$i]['avatar']	=	$member_default;
   			} 
   			
   			if($row2['cover_pic']!='')
   			{
   				$data2[$i]['coverPhoto']	=	$row2['cover_pic'];
   			}
   			else
   			{
   				$data2[$i]['coverPhoto']	=	$member_default;
   			}  
   			
   			$data2[$i]['position']	=	$row2['designation'];
   			$data2[$i]['city']		=	$row2['city'];
   			 			
   			$i++;
   		}		
		}
		return $data2;

}

//Function to get event from event id
//May 12,2016
function getEventFromEventID($eventid)
{

	$data= array();	
	$events_default='assets/img/events/events-default.jpg';
	$member_default='assets/img/members/member-default.jpg';
	
	$qry="SELECT entrp_events.*,entrp_event_categories.category_name 
			FROM entrp_events 
			LEFT JOIN entrp_event_categories ON entrp_events.category=entrp_event_categories.id
		   WHERE entrp_events.id=".$eventid."
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$data['id']				=	$row['id'];
   		$data['name']			=	$row['eventName'];
   		$data['address']		=	$row['address'];

   		$data['date']			=	$row['event_date'];
   		$data['startTime']	=	$row['start_time'];
   		$data['endTime']		=	$row['end_time'];
   		$data['eventPhoto']	=	$row['clientid'];
   		if($row['poster']!='')
   		{
   			$data['poster']	=	$row['poster'];
   		}
   		else
   		{
   			$data['poster']	=	$events_default;
   		}
   		$data['about']			=	$row['description'];
   		$data['category']		=	$row['category_name'];
   		$data['map']['center']['latitude']		=	$row['location_lat'];
			$data['map']['center']['longitude']		=	$row['location_long'];
			$data['map']['zoom']	=	8;
   	}
		
		$data['attendees']=getEventAttendeesFromEventID($eventid);
	}
	else
	{
		$data['id']										=	'';
		$data['name']									=	'';
		$data['address']								=	'';
		$data['map']['center']['latitude']		=	'';
		$data['map']['center']['longitude']		=	'';
		$data['map']['zoom']							=	8;
		$data['date']									=	'';
		$data['startTime']							=	'';
		$data['endTime']								=	'';
		$data['eventPhoto']							=	'';
		$data['poster']								=	'';
		$data['about']									=	'';
   	$data['category']								=	'';		
	}
	return $data;

		

}


//Function to fetch company profile from company id
//May 12,2016
function fetch_company_information_from_companyid($companyid)
{
	$data= array();
	$company_default_cover		='assets/img/companies/company-default.jpg';
	$company_default_avatar		='assets/img/companies/company-default.jpg';

	$qry="SELECT company_profiles.*,
			 		 location_info.location_desc
			FROM company_profiles
			LEFT JOIN location_info ON location_info.id=company_profiles.client_location
			WHERE company_profiles.id=".$companyid."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data['id']					=	$row['id']; 
			$data['name']		=	$row['company_name'];  	
			$data['companyUserName']=	$row['company_username'];		
			$data['location']			=	$row['located_at'];  			
			
			if($row['avatar']!='')
   		{
   			$data['profilePhoto']				=	$row['avatar'];
   		}
			else
			{
				$data['profilePhoto']				=	$company_default_avatar;
			} 
			
			if($row['cover_photo']!='')
   		{
   			$data['coverPhoto']				=	$row['cover_photo'];
   		}
			else
			{
				$data['coverPhoto']				=	$company_default_cover;
			}			 			
			 						 			 			
			$data['website']			=	$row['website'];
			$data['email']				=	$row['email'];   			
			$data['mobile']			=	$row['mobile'];  			
			$data['tel']				=	$row['telephone'];  			
			$data['fax']				=	$row['fax'];
			$data['desc']				=	$row['description'];   
			
			$data['followers']		=	entrp_company_follows($companyid);
			$data['categories']		= fetch_company_categories($companyid);			
		}
	}
	else 
	{
		$data['id']					=	'';  			
		$data['profilePhoto']	=	''; 		
		$data['coverPhoto']		=	''; 		
		$data['companyName']		=	''; 
		$data['companyUserName']='';
		$data['location']			=	'';   			
		$data['companyDesc']		=	''; 		
		$data['email']				=	''; 			
		$data['website']			=	'';  			
		$data['mobile']			=	''; 	
		$data['tel']				=	'';  			
		$data['fax']				=	''; 
	}
	
	return $data;

}



//Function to fetch total (count) users following a company
//May 05,2016
function entrp_company_follows($company_id)
{
	//To fetch user followers
	//SELECT COUNT(entrp_user_follows.clientid) AS followers FROM entrp_user_follows WHERE entrp_user_follows.follows=1
	$qry="SELECT COUNT(entrp_company_follows.clientid) AS followers 
			 FROM entrp_company_follows 
			 WHERE entrp_company_follows.companyid=".$company_id."
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$count_followers 		= $row['followers'];
   	}
	}
	else
	{
		$count_followers 			= 0;
	}
	return $count_followers;
}



//Function to delete company categories
//May 05,2016 (Not in use now)
function delete_company_categories($company_id)
{
	$qry="DELETE FROM entrp_company_categories  
			WHERE companyid=".$company_id." ";
   setData($qry);

}


//Function to fetch user id from username
//May 10,2016
function getUserIdfromUserName($userName)
{
	
	$qry="SELECT clientid FROM entrp_login  
			WHERE username='".$userName."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['clientid'];  					
		}
	}   
	return $id;
}




//Function to fetch company user id from company username
//May 10,2016
function getCompanyIdfromCompanyUserName($companyUserName)
{
	
	$qry="SELECT id FROM company_profiles  
			WHERE company_username='".$companyUserName."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['id'];  					
		}
	}   
	return $id;
}


//Function to fetch company categories based on company id
//May 05,2016
function fetch_company_categories($company_id)
{
	$data= array();
	
	$qry="SELECT * FROM entrp_company_categories  
			WHERE companyid=".$company_id." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['category']);  					
		}
	}   
	return $data;
}


//Function to get a user's own company details based on user id
//May 03,2016
function fetch_company_information_from_userid($clientid)
{
	$data= array();
	$company_default_cover		='assets/img/companies/company-default.jpg';
	$company_default_avatar		='assets/img/companies/company-default.jpg';

	$qry="SELECT company_profiles.*,
			 		 location_info.location_desc
			FROM company_profiles
			LEFT JOIN location_info ON location_info.id=company_profiles.client_location
			WHERE company_profiles.clientid=".$clientid."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data['id']					=	$row['id']; 
			$data['companyName']		=	$row['company_name'];  	
			$data['companyUserName']=	$row['company_username'];		
			$data['location']			=	$row['located_at'];  			
			
			if($row['avatar']!='')
   		{
   			$data['profilePhoto']				=	$row['avatar'];
   		}
			else
			{
				$data['profilePhoto']				=	$company_default_avatar;
			} 
			
			if($row['cover_photo']!='')
   		{
   			$data['coverPhoto']				=	$row['cover_photo'];
   		}
			else
			{
				$data['coverPhoto']				=	$company_default_cover;
			}			 			
			 						 			 			
			$data['website']			=	$row['website'];
			$data['email']				=	$row['email'];   			
			$data['mobile']			=	$row['mobile'];  			
			$data['tel']				=	$row['telephone'];  			
			$data['fax']				=	$row['fax'];
			$data['companyDesc']		=	$row['description'];   
			
			$data['followers']		=	entrp_company_follows($data['id']);
			$data['categories']		= fetch_company_categories($data['id']);			
		}
	}
	else 
	{
		$data['id']					=	'';  			
		$data['profilePhoto']	=	''; 		
		$data['coverPhoto']		=	''; 		
		$data['companyName']		=	''; 
		$data['companyUserName']='';
		$data['location']			=	'';   			
		$data['companyDesc']		=	''; 		
		$data['email']				=	''; 			
		$data['website']			=	'';  			
		$data['mobile']			=	''; 	
		$data['tel']				=	'';  			
		$data['fax']				=	''; 
	}
	
	return $data;

}


//Function to fetch a user's skill set
//May 02, 2016
function get_user_skill_sets($userid)
{
	
	$data= array();
	$qry="SELECT * FROM entrp_user_skills  
			WHERE user_id=".$userid." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['skills']);  					
		}
	}   
	return $data;	
	
	
}


//Function to fetch a user's interest set
//May 02, 2016
function get_user_interest_sets($userid)
{	
	$data= array();
	$qry="SELECT * FROM entrp_user_interests  
			WHERE user_id=".$userid." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['interests']);  					
		}
	}   
	return $data;		
}

//Function to get total (count) followings of a user
//April 25,2016
function user_following($clientid)
{
	//To fetch user following
   //SELECT COUNT(entrp_user_follows.follows) AS following FROM entrp_user_follows WHERE entrp_user_follows.clientid=1
	$qry="SELECT COUNT(entrp_user_follows.follows) AS following 
			 FROM entrp_user_follows 
			 WHERE entrp_user_follows.clientid=".$clientid."
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$count_following 		= $row['following'];
   	}
	}
	else
	{
		$count_following 			= 0;
	}
	return $count_following;
}



//Function to get total (count) followers of a user
//April 25, 2016
function user_followers($clientid)
{
	//To fetch user followers
	//SELECT COUNT(entrp_user_follows.clientid) AS followers FROM entrp_user_follows WHERE entrp_user_follows.follows=1
	$qry="SELECT COUNT(entrp_user_follows.clientid) AS followers 
			 FROM entrp_user_follows 
			 WHERE entrp_user_follows.follows=".$clientid."
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$count_followers 		= $row['followers'];
   	}
	}
	else
	{
		$count_followers 			= 0;
	}
	return $count_followers;
}


//Function to get user information based on id
//May 03, 2016
//Sibling to viewUserProfile
function fetch_user_information_from_id($clientid)
{
	
	$data= array();		
	/*
	SELECT client_info.clientid,client_info.firstname,client_info.lastname,client_info.city,client_info.country,client_info.email,
			 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.website,client_profile.about_me,
			 location_info.location_desc,company_profiles.company_name,company_profiles.description
	FROM client_info
	LEFT JOIN client_profile ON client_info.clientid=client_profile.clientid
	LEFT JOIN location_info ON location_info.id=client_profile.client_location
	LEFT JOIN company_profiles ON company_profiles.clientid=client_info.clientid
	*/


  $qry="SELECT entrp_login.clientid,entrp_login.username,entrp_login.firstname,entrp_login.lastname,client_profile.city,client_profile.country,client_profile.contact_email,
			 		 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.website,client_profile.about_me,
			 		 client_profile.secondary_mobile,
			 		 location_info.location_desc,
			 		 company_profiles.company_name,company_profiles.description
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			LEFT JOIN location_info ON location_info.id=client_profile.client_location
			LEFT JOIN company_profiles ON company_profiles.clientid=entrp_login.clientid
			WHERE entrp_login.clientid=".$clientid."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {

   	while($row=mysqli_fetch_array($res))
      {
      	$data['id']				=	$row['clientid'];
			$data['avatar']		=	$row['avatar'];
			$data['coverPhoto']	=	$row['cover_pic'];
			$data['firstName'] 	= 	$row['firstname'];
			$data['lastName'] 	= 	$row['lastname'];
			$data['position'] 	= 	$row['designation'];
			$data['city'] 			= 	$row['city'];
			
			$data['aboutMe'] 		=  $row['about_me'];
			$data['email'] 		=  $row['contact_email'];
			$data['website'] 		=  $row['website'];
			$data['mobile'] 		=  $row['mobile'];
			$data['tel'] 			=  $row['secondary_mobile'];
			$data['userName']			=	$row['username'];
			$data['company']['companyName'] 		= $row['company_name'];
			$data['company']['companyDesc'] 		= $row['company_name'];

			$data['success'] = true;
			$data['msg'] = 'Profile fetched';
		}

   	$data['skills'] 		= get_user_skill_sets($clientid);
   	$data['interests'] 	= get_user_interest_sets($clientid);
   	
   	//Function to get total followers of a user
		$data['followers'] 	= user_followers($clientid);
		
		//Function to get total followings of a user
		$data['following'] 	= user_following($clientid);
   	   	
   }
   else
   {
   	$data['success'] = false;
		$data['msg'] = 'User Not Found';
   }
   return $data;
}



/* Functions and services based on userid ends */



?>