<?php

require_once ('Query.php'); 

require 'flight/Flight.php';


//Route to events directory
// April 13,2015
Flight::route('/getEvents', function()
{
   enable_cors();	
	$returnarray=getEvents();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Route to members directory
// April 13,2015
Flight::route('/getMembers', function()
{
   enable_cors();	
	$returnarray=getMembers();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Route to companies directory
// April 13,2015
Flight::route('/getCompanies', function()
{
   enable_cors();	
	$returnarray=getCompanies();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to login and redirect
// April 15,2015
Flight::route('/login', function()
{
   enable_cors();	
	$returnarray=login();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Function for forgot password feature
//April 15, 2016
Flight::route('/forgotpassword', function()
{
   enable_cors();	
	$returnarray=forgot_password();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Function to get user session
//April 18,2016
Flight::route('/get_user_session', function()
{
   enable_cors();	
	$returnarray=get_user_session();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//Function to get user session
//April 19,2016
Flight::route('/getLocations', function()
{
   enable_cors();	
	$returnarray=getLocations_dropdown();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//Route to fetch new members
// April 21,2015
Flight::route('/getNewMembers', function()
{
   enable_cors();	
	$returnarray=getNewMembers();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch new members
// April 21,2015
Flight::route('/view_user_profile', function()
{
   enable_cors();	
	$returnarray=viewUserProfile();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch new members
// April 25,2015
Flight::route('/view_company_profile', function()
{
   enable_cors();	
	$returnarray=viewCompanyProfile();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch new members
// April 27,2015
Flight::route('/view_event_detail', function()
{
   enable_cors();	
	$returnarray=viewEventDetail();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});



//Route to get a user's own details
// April 28,2015
Flight::route('/get_my_details', function()
{
   enable_cors();	
	$returnarray=getMyProfileDetails();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


Flight::start();


//Function to get all interest set
//May 02, 2016
function get_all_interest_sets()
{
	$i=0;
	$data= array();
	$qry="SELECT *  
			FROM entrp_interests
			WHERE status=1 
			";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			//$data[$i]['id']		=	$row['id'];
			$data[$i]		=	$row['interest'];  			
			$i++;
		}		
	}
	return $data;

}

//Function to get all skill set
//May 02, 2016
function get_all_skill_sets()
{
	$i=0;
	$data= array();
	$qry="SELECT *  
			FROM entrp_skills
			WHERE status=1 
			";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			//$data[$i]['id']		=	$row['id'];
			$data[$i]		=	$row['skills'];  			
			$i++;
		}		
	}
	return $data;
}

//Function to fetch a user's skill set
//May 02, 2016
function get_user_skill_sets($userid)
{
	//To fetch user skill set
	$data= array();
	$qry="SELECT entrp_user_skills.skill_id,entrp_skills.skills 
			 FROM entrp_user_skills 
			 LEFT JOIN entrp_skills ON entrp_user_skills.skill_id=entrp_skills.id 
			 WHERE entrp_user_skills.user_id=".$userid."
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	$k=0;
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$data[$k] 		= $row['interest'];
   		$k++;
   	}
	}
	return $data;
}


//Function to fetch a user's interest set
//May 02, 2016
function get_user_interest_sets($userid)
{
	//To fetch user interest list
	$data= array();
	$qry="SELECT entrp_user_interests.interest_id,entrp_interests.interest 
			 FROM entrp_user_interests 
			 LEFT JOIN entrp_interests ON entrp_interests.id=entrp_user_interests.interest_id 
			 WHERE entrp_user_interests.user_id=".$userid."
			 ";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	$j=0;
	if($count_res>0)
	{
	  while($row=mysqli_fetch_array($res))
     {
   	 $data[$j] 		= $row['interest'];
   	 $j++;
     }
	}
	return $data;
}



//Function to fetch a user's own details
//April 28,2016
function getMyProfileDetails()
{
	
/*
{
	"avatar": "member01.jpg",
	"coverPhoto": "memberCover01.jpg",
	"firstName": "Ken",
	"lastName": "Sia",
	"position": "Front-end Web Developer",
	"Location": "Fort Legend Tower",
	"aboutMe": "Front-end Web Developer who loves listening to music, surfing, and traveling",
	"email": "ken.voffice@gmail.com",
	"website": "ken.com.ph",
	"mobile": "09175296299",
	"tel": "0229131533"
	"skills": [
		"Programming",
		"Public Speaking"
	],
	"interests": [
		"Design",
		"Surf",
		"Basketball"
	]
}

*/	
	$userid=validate_input($_GET['id']);
	$data= array();
	
	$qry="SELECT client_info.clientid,client_info.firstname,client_info.lastname,client_info.city,client_info.country,client_info.email,
			 		 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.secondary_mobile,client_profile.website,client_profile.about_me,
			 		 location_info.location_desc,
			 		 company_profiles.company_name,company_profiles.description
			FROM client_info
			LEFT JOIN client_profile ON client_info.clientid=client_profile.clientid
			LEFT JOIN location_info ON location_info.id=client_profile.client_location
			LEFT JOIN company_profiles ON company_profiles.clientid=client_info.clientid
			WHERE client_info.clientid=".$userid."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
   	{
   		$data['avatar']			=	$row['avatar'];
   		$data['coverPhoto']		=	$row['cover_pic'];
   		$data['firstName']		=	$row['firstname'];
   		$data['lastName']			=	$row['lastname'];
   		$data['position']			=	$row['designation'];
   		$data['Location']			=	$row['location_desc'];
   		$data['aboutMe']			=	$row['about_me'];
   		$data['email']				=	$row['email'];
   		$data['website']			=	$row['website'];
   		$data['mobile']			=	$row['mobile'];
   		$data['tel']				=	$row['secondary_mobile'];
   	}
   	
   	//fetch user skills
   	 $data['userSkills'] 		= get_user_skill_sets($userid);
   	//fetch user interests
   	 $data['userInterests'] 	= get_user_interest_sets($userid);
   	   
   }
   else
   {
		$data['avatar']			=	'';
		$data['coverPhoto']		=	'';
		$data['firstName']		=	'';
		$data['lastName']			=	'';
		$data['position']			=	'';
		$data['Location']			=	'';
		$data['aboutMe']			=	'';
		$data['email']				=	'';
		$data['website']			=	'';
		$data['mobile']			=	'';
		$data['tel']				=	'';
   
   }
   //fetch all skills
   $data['allSkills'] 		= get_all_skill_sets();
   	
   //fetch all interests
   $data['allInterests'] 	= get_all_interest_sets();
	
	return $data;
}


//Function to fetch a company profile
//April 25,2016
function viewCompanyProfile()
{
		/*
		{
		  "name": "vOffice",
		  "location": "Fort Legend Tower",
		  "coverPhoto": "cover.jpg",
		  "profilePhoto": "profile.jpg",
		  "website": "voffice.com.ph",
		  "email": "sales@voffice.com",
		  "mobile": "639175296299",
		  "tel": "6322931533",
		  "fax": "6329165745",
		  "desc": "We provide businesses superior reach and access to South East Asia markets like Jakarta, Manila, Kuala Lumpur and Singapore.",
		  "natureOfBusinessTags": [
		    "virtual office",
		    "serviced office",
		    "co-working spaces"
		  ],
		  "employees": [
		    {
		      "id": "1",
		      "firstName": "Ken",
		      "lastName": "Sia",
		      "profilePhoto": "emp1.jpg"
		    },
		    {
		      "id": "2",
		      "firstName": "Jaye",
		      "lastName": "Atienza",
		      "profilePhoto": "emp2.jpg"
		    }
		  ]
		}
		*/

	$companyid=validate_input($_GET['id']);
	$data= array();	
	$company_default_profile='company-default.jpg';
	$company_default_cover='company-default.jpg';
	$member_default='member-default.jpg';
	
	$qry="SELECT  CP.*,LI.location_desc AS city 
			FROM company_profiles AS CP
			LEFT JOIN location_info as LI ON LI.id=CP.client_location
			WHERE CP.id=".$companyid." 
		  ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$data['id']					=	$row['id'];
   		$data['name']				=	$row['company_name'];
   		$data['location']			=	$row['client_location'];
   		
   		if($row['cover_photo']!='')
   		{
   			$data['coverPhoto']		=	$row['cover_photo'];
   		}
   		else
   		{
   			$data['coverPhoto']		=	$company_default_cover;
   		}
   		
   		if($row['avatar']!='')
   		{
   			$data['profilePhoto']	=	$row['avatar'];
   		}
   		else
   		{
   			$data['profilePhoto']	=	$company_default_profile;
   		}     				
   		$data['website']			=	$row['avatar'];
   		$data['email']				=	$row['email'];
   		$data['mobile']			=	$row['mobile'];
   		$data['tel']				=	$row['telephone'];
   		$data['fax']				=	$row['fax'];
   		$data['desc']				=	$row['description'];
   		$data['followers']		=	20;

   	}
   	
	}
	else
	{
		$data['id']				=	'';
		$data['name']			=	'';
		$data['location']		=	'';
		$data['coverPhoto']		=	'';
		$data['profilePhoto']		=	'';
		$data['website']			=	'';
		$data['mobile']	=	'';
		$data['tel']		=	'';
		$data['fax']	=	'';
		$data['desc']		=	'';
	}
	return $data;

}

//Function to get an event's details
//April 25, 2016
function viewEventDetail()
{
		/*
		{
	  "name": "Master The Art Of Selling",
	  "address": "10-f, Fort Legend Tower, 3rd Ave, Taguig, Metro Manila",
	  "gmapLong": 121.04692,
	  "gmapLat": 14.55408,
	  "date": "4-25-2016",
	  "startTime": "10:00",
	  "endTime": "19:00",
	  "eventPhoto": "event.jpg",
	  "about": "We will teach you on how to master selling and generate more sales for your brand or company",
	  "attendees": [
	    {
	      "id": "1",
	      "firstName": "Ken",
	      "lastName": "Sia",
	      "profilePhoto": "emp1.jpg"
	    },
	    {
	      "id": "2",
	      "firstName": "Jaye",
	      "lastName": "Atienza",
	      "profilePhoto": "emp2.jpg"
	    }
	  ]
	  }
	  */

	$eventid=validate_input($_GET['id']);
	$data= array();	
	$events_default='events-default.jpg';
	$member_default='member-default.jpg';
	
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
   	
   	$i=0;
   	$data2= array();
   	$qry2="SELECT entrp_event_attendees.clientid,client_info.firstname,client_info.lastname,client_profile.avatar 
				 FROM entrp_event_attendees 
				 LEFT JOIN client_info ON client_info.clientid=entrp_event_attendees.clientid 
				 LEFT JOIN client_profile ON client_profile.clientid=client_info.clientid
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
   				$data2[$i]['profilePhoto']	=	$row2['avatar'];
   			}
   			else
   			{
   				$data2[$i]['profilePhoto']	=	$member_default;
   			}   			
   			$i++;
   		}		
		}
		$data['attendees']=$data2;
	}
	else
	{
		$data['id']				=	'';
		$data['name']			=	'';
		$data['address']		=	'';
		$data['gmapLong']		=	'';
		$data['gmapLat']		=	'';
		$data['date']			=	'';
		$data['startTime']	=	'';
		$data['endTime']		=	'';
		$data['eventPhoto']	=	'';
		$data['poster']		=	'';
		$data['about']			=	'';
   	$data['category']		=	'';		
	}
	return $data;
}

//Function to get total followings of a user
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



//Function to get total followers of a user
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



//Function to fetch a user profile
//April 22,2016
function viewUserProfile()
{
	$clientid=validate_input($_GET['id']);
	
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


  $qry="SELECT client_info.clientid,client_info.firstname,client_info.lastname,client_info.city,client_info.country,client_info.email,
			 		 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.website,client_profile.about_me,
			 		 location_info.location_desc,
			 		 company_profiles.company_name,company_profiles.description
			FROM client_info
			LEFT JOIN client_profile ON client_info.clientid=client_profile.clientid
			LEFT JOIN location_info ON location_info.id=client_profile.client_location
			LEFT JOIN company_profiles ON company_profiles.clientid=client_info.clientid
			WHERE client_info.clientid=".$clientid."
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

			$data['city'] 			= 	$row['city'];

			$data['aboutMe'] 		=  $row['about_me'];
			$data['email'] 		=  $row['email'];
			$data['website'] 		=  $row['website'];
			$data['mobile'] 		=  $row['mobile'];

			$data['company']['companyName'] 		= $row['company_name'];
			$data['company']['companyDesc'] 		= $row['company_name'];

			$data['success'] = true;
			$data['msg'] = 'Profile fetched';
		}

		//To fetch user interest list
		$qry2="SELECT entrp_user_interests.interest_id,entrp_interests.interest 
   			 FROM entrp_user_interests 
   			 LEFT JOIN entrp_interests ON entrp_interests.id=entrp_user_interests.interest_id 
   			 WHERE entrp_user_interests.user_id=".$clientid."
   			 ";
		$res2=getData($qry2);
		$count_res2=mysqli_num_rows($res2);
		$j=0;
		if($count_res2>0)
		{
			while($row2=mysqli_fetch_array($res2))
			{
				$data['interests'][$j] 		= $row2['interest'];
				$j++;
			}
		}
		else
		{
			$data['interests'][$j] 		= '';
		}

		//To fetch user skill set
		$qry3="SELECT entrp_user_skills.skill_id,entrp_skills.skills 
   			 FROM entrp_user_skills 
   			 LEFT JOIN entrp_skills ON entrp_user_skills.skill_id=entrp_skills.id 
   			 WHERE entrp_user_skills.user_id=".$clientid."
   			";
		$res3=getData($qry3);
		$count_res3=mysqli_num_rows($res3);
		$k=0;
		if($count_res3>0)
		{
			while($row3=mysqli_fetch_array($res3))
			{
				$data['skills'][$k] 		= $row3['interest'];
				$k++;
			}
		}
		else
		{
			$data['skills'][$k] 		= '';
		}

		//Function to get total followers of a user
		$data['followers'] 	= user_followers($clientid);

		//Function to get total followings of a user
		$data['following'] 	= user_following($clientid);

	}
	else
	{
		$data['success'] = false;
		$data['msg'] = 'Please check your credentials once again';
	}
	return $data;
}


//Function to fetch newly registered members list
//April 21,2016
function getNewMembers()
{
	$today=date('Y-m-d H:i:s');
	$to_day = new DateTime($today);
	$to_day->modify('-14 day');
	$fromday= $to_day->format('Y-m-d H:i:s');

	$data= array();	
	$qry="SELECT CI.clientid,CI.firstname,CI.lastname,CP.designation,CP.company_name,CP.avatar,LI.location_desc AS city 
	      FROM client_info AS CI 
	      LEFT JOIN client_profile AS CP ON CP.clientid=CI.clientid
	      LEFT JOIN location_info as LI ON LI.id=CP.client_location
	      WHERE CP.join_date >= '".$fromday."' AND CP.join_date <= '".$today."'
	      ORDER BY CI.clientid DESC 
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
				$data[$i]['avatar']			=	"img-member.jpg";
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
	}
	return $data;

}


//Function to fetch location list (centers)
//April 19,2016
function getLocations_dropdown()
{
	$data= array();	
	$qry="SELECT  id,location_desc FROM location_info";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	$i=0; //to initiate count
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[$i]['id']				=	$row['id'];
			$data[$i]['location_desc']	=	$row['location_desc'];
			$i++;
		}	
	}
	else
	{
		$data[$i]['id']				=	"";
		$data[$i]['location_desc']	=	"";
	}
	return $data;	
}


//Function to get session values
//April 18,2016
function get_user_session()
{
	if (!isset($_SESSION))
	{    
		session_start();
	}    

	$sessions = array();

	$sessions['login_token'] 	= $_SESSION['login_token'];
	$sessions['firstname'] 		= $_SESSION['firstname'];
	$sessions['lastname'] 		= $_SESSION['lastname'];
	$sessions['id'] 				= $_SESSION['id'];

	return $sessions;
}


//Function for forgot password feature
//April 15, 2016
function forgot_password()
{
	$data= array();
	$username=validate_input($_POST['username']);
	//check whether this email id exist on database
	$qry="SELECT clientid,firstname,lastname FROM client_info AS CI where CI.email='".$username."'";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);   
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data['firstname']	=	$row['firstname'];
			$data['lastname']		=	$row['lastname'];
			$data['id']				=	$row['clientid'];      
		}

		//if yes, start password reset process

		$fullname=$data['firstname'].' '.$data['lastname'];
		$password=generateRandomAlphaNumeric($length=8);
		ob_start();
		include('email_templates/password_reset.php');
		$order_placement_template = ob_get_contents();			
		ob_end_clean();			

		$to='dominic@cliffsupport.com'; 
		//$to1='cs@vrush.ph'; 
		$strSubject="Password reset form";
		$message =  $order_placement_template;              
		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
		$headers .= "From: eprty@test.com"; 

		$qry2="UPDATE client_info SET password='".md5($password)."' where email='".$username."' ";
		if(setData($qry2))
		{
			$mail_to_vrush=mail($to, $strSubject, $message, $headers);  			
			if($mail_to_vrush)
			{
				$data['success'] 		= true;
				$data['msg'] 			= 'An email has been sent to you with your new password - Please check your email';
			}
			else
			{
				$data['success'] 		= false;
				$data['msg'] 			= 'We did not recognize that email';
			}
		}
		else
		{
			$data['success'] 		= false;
			$data['msg'] 			= 'We did not recognize that email';
		}
	}
	else
	{
		//if no, show them a message
		$data['success'] 		= false;
		$data['msg'] 			= 'We did not recognize that email';
	}
	return $data;
}

//Function to generate random alpha numeric string
//April 19,2016
function generateRandomAlphaNumeric($length = 4) 
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) 
	{
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

//Function to login
//April 15, 2016
function login()
{
	$data= array();
	/*
	$content=json_decode(file_get_contents('php://input'));
	$ncontent=(array)	$content;
	$username=validate_input($ncontent['username']);
	$password=validate_input($ncontent['password']);
	*/
	$username=validate_input($_POST['username']);
	$password=validate_input($_POST['password']);

	$qry="SELECT * FROM client_info where email='".$username."' AND password='".md5($password)."' ";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data['firstname']	=	$row['firstname'];
			$data['lastname']		=	$row['lastname'];
			$data['id']				=	$row['clientid'];
			$data['success'] 		= true;
			$data['msg'] 			= 'Valid User';

			//generate a client token
			$client_session_token=generate_login_token();

			//set session
			session_start();
			$_SESSION['id'] 				= $data['id'];
			$_SESSION['firstname'] 		= $data['firstname'];
			$_SESSION['lastname'] 		= $data['lastname'];
			$_SESSION['login_token'] 	= $client_session_token;

			set_client_session_token($client_session_token,$row['clientid']);

		}
	}
	else
	{
		$data['success'] = false;
		$data['msg'] = 'Please check your credentials once again';
	}

	return $data;

}

//Function to set a login token
function set_client_session_token($client_session_token,$clientid)
{
	$token_set_at=date("Y-m-d H:i:s");
	$qry="INSERT INTO client_login_tokens(clientid,client_token,date_time) VALUES(".$clientid.",'".$client_session_token."','".$token_set_at."') ";
	setData($qry);
}

//Function to generate a login token
function generate_login_token()
{
	$token = substr(md5(uniqid(rand(), true)),0,32);  // creates a 32 digit token
	$qry = "SELECT id FROM client_login_tokens WHERE client_token = '".$token."' ";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		generate_login_token();
	}
	else
	{
		return $token;
	}	


}

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
	$data= array();	
	$qry="SELECT CI.clientid,CI.firstname,CI.lastname,CP.designation,CP.company_name,CP.avatar,LI.location_desc AS city 
	      FROM client_info AS CI 
	      LEFT JOIN client_profile AS CP ON CP.clientid=CI.clientid
	      LEFT JOIN location_info as LI ON LI.id=CP.client_location
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
	}
	return $data;
}



//Function to fetch company directory
// April 13,2015
function getCompanies()
{	
	$data= array();	
	$qry="SELECT  CP.id,CP.clientid,CP.company_name,CP.description,CP.avatar,LI.location_desc AS city 
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


//Function to validate inputs
function validate_input($input) 
{	
	$input = trim($input);
	//$input = stripslashes($input);
	$input = addslashes($input);
	$input = htmlspecialchars($input);
	return $input;
}

//Function to enable CORS
function enable_cors() 
{
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: X-Requested-With");	
	date_default_timezone_set('asia/singapore');
}


?>
