<?php

require_once ('Query.php'); 
require_once 'constants.php';
require 'flight/Flight.php';


//Route to events directory
// April 13,2016
Flight::route('/getEvents', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getEvents();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Route to members directory
// April 13,2016
Flight::route('/getMembers', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getMembers();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Route to companies directory
// April 13,2016
Flight::route('/getCompanies', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getCompanies();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to login and redirect
// April 15,2016
Flight::route('/login', function()
{
   enable_cors();	
   services_included();	
	$returnarray=login();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Function for forgot password feature
//April 15, 2016
Flight::route('/forgotpassword', function()
{
   enable_cors();	
   services_included();	
	$returnarray=forgot_password();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Function to get user session
//April 18,2016
Flight::route('/get_user_session', function()
{
   enable_cors();	
   services_included();	
	$returnarray=get_user_session();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//Function to get user session
//April 19,2016
Flight::route('/getLocations', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getLocations_dropdown();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//Route to fetch new members
// April 21,2016
Flight::route('/getNewMembers', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getNewMembers();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch new members
// April 21,2016
Flight::route('/view_user_profile', function()
{
   enable_cors();	
   services_included();	
	$returnarray=viewUserProfile();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch new members
// April 25,2016
Flight::route('/view_company_profile', function()
{
   enable_cors();	
   services_included();	
	$returnarray=viewCompanyProfile();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch new members
// April 27,2016
Flight::route('/view_event_detail', function()
{
   enable_cors();	
   services_included();	
	$returnarray=viewEventDetail();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});



//Route to get a user's own details
//April 28,2016
Flight::route('/get_my_details', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getMyProfileDetails();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to update user profile details
//May 03,2016
Flight::route('/update_my_profile', function()
{
   enable_cors();	
   services_included();	
	$returnarray=updateMyProfileDetails();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to get a user's own company details
//May 03,2016
Flight::route('/get_my_company_profile', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getMyCompanyProfileDetails();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to update user's company profile details
//May 03,2016
Flight::route('/update_my_company_profile', function()
{
   enable_cors();
   services_included();	
	$returnarray=updateMyCompanyDetails();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to update user's profile avatar
//May 06,2016
Flight::route('/update_member_avatar', function()
{
   enable_cors();
   services_included();	
	$returnarray=updateMyProfileAvatar();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Route to fetch latest events
//May 09,2016
Flight::route('/getLatestEvents', function()
{
   enable_cors();
   services_included();	
	$returnarray=getLatestEvents();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch basic user information
//May 09,2016
Flight::route('/getBasicUserInformation', function()
{
   enable_cors();
   services_included();	
	$returnarray=getBasicUserInformation();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to follow a user from his profile
//May 11,2016
Flight::route('/followThisUser', function()
{
   enable_cors();
   services_included();	
	$returnarray=followThisUser();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to un-follow a user from his profile
//May 11,2016
Flight::route('/unfollowThisUser', function()
{
   enable_cors();
   services_included();	
	$returnarray=unfollowThisUser();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to follow a user from member directory
//May 11,2016
Flight::route('/followUser', function()
{
   enable_cors();
   services_included();	
	$returnarray=followUser();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to un-follow a user from member directory
//May 11,2016
Flight::route('/unfollowUser', function()
{
   enable_cors();
   services_included();	
	$returnarray=unfollowUser();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to follow a company from company directory
//May 12,2016
Flight::route('/followCompany', function()
{
   enable_cors();
   services_included();	
	$returnarray=followCompany();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to un-follow a company from company directory
//May 12,2016
Flight::route('/unfollowCompany', function()
{
   enable_cors();
   services_included();	
	$returnarray=unfollowCompany();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to follow a company from company profile
//May 12,2016
Flight::route('/followThisCompany', function()
{
   enable_cors();
   services_included();	
	$returnarray=followThisCompany();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to un-follow a company from company profile
//May 12,2016
Flight::route('/unfollowThisCompany', function()
{
   enable_cors();
   services_included();	
	$returnarray=unfollowThisCompany();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to mark going for an event (from event details)
//May 12,2016
Flight::route('/goingForEvent', function()
{
   enable_cors();
   services_included();	
	$returnarray=goingForEvent();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to mark not going for an event (from event details)
//May 12,2016
Flight::route('/notGoingForEvent', function()
{
   enable_cors();
   services_included();	
	$returnarray=notGoingForEvent();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to mark going for an event (from event directory)
//May 13,2016
Flight::route('/goingToEvent', function()
{
   enable_cors();
   services_included();	
	$returnarray=goingToEvent();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to mark not going for an event (from event directory)
//May 13,2016
Flight::route('/notGoingToEvent', function()
{
   enable_cors();
   services_included();	
	$returnarray=notGoingToEvent();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch a member's following list
//May 13,2016
Flight::route('/getMemberFollowing', function()
{
   enable_cors();
   services_included();	
	$returnarray=getMemberFollowing();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch a member's follower list
//May 13,2016
Flight::route('/getMemberFollowers', function()
{
   enable_cors();
   services_included();	
	$returnarray=getMemberFollowers();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Route to fetch a company's follower list
//May 13,2016
Flight::route('/getCompanyFollowers', function()
{
   enable_cors();
   services_included();
	$returnarray=getCompanyFollowers();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to check validity of a user token
//May 17,2016
Flight::route('/validateUserToken', function()
{
   enable_cors();
   services_included();	
	$returnarray=validateUserToken();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to check validity of a user token
//May 17,2016
Flight::route('/destroyUserToken', function()
{
   enable_cors();
   services_included();	
	$returnarray=destroyUserToken();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to post new Feed to timeline
//May 18,2016
Flight::route('/postCurrentPost', function()
{
   enable_cors();
   services_included();	
	$returnarray=postCurrentPost();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//Route to get my Feed to timeline
//May 18,2016
Flight::route('/getMembersPost', function()
{
   enable_cors();
   services_included();	
	$returnarray=getMyNewsFeed();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//Route to get timeline feeds of users I follow
//May 30,2016
Flight::route('/getFollowedMembersPosts', function()
{
   enable_cors();
   services_included();	
	$returnarray=getFollowedMembersPosts();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//Route to get timeline feeds of all users
//May 30,2016
Flight::route('/getAllPosts', function()
{
   enable_cors();
   services_included();	
	$returnarray=getAllPosts();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//Route to post a new comment for a timeline post
//May 20,2016
Flight::route('/postThisComment', function()
{
   enable_cors();
   services_included();	
	$returnarray=postThisComment();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//Route to like a timeline post
//May 20,2016
Flight::route('/likeThisPost', function()
{
   enable_cors();
   services_included();	
	$returnarray=likeThisPost();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//Route to unlike a timeline post
//May 20,2016
Flight::route('/unlikeThisPost', function()
{
   enable_cors();
   services_included();	
	$returnarray=unlikeThisPost();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//Route to prfile settings
// May 24,2016
//Arshad
Flight::route('/getMyPreferences', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getMyPreferences();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//Route to update preferences
// May 25,2016
//Arshad
Flight::route('/updateMyPreferences', function()
{
   enable_cors();	
   services_included();	
	$returnarray=updateMyPreferences();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch top contributors
//June 02,2016
Flight::route('/getTopContributors', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getTopContributors();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//Route to fetch user notifications
//June 02,2016
Flight::route('/getMyNotifications', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getMyNotifications();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});



//Route to test timeline posts
//November 31,2016
/*
Flight::route('/testTimelinePosts', function()
{
   enable_cors();
   services_included();	
	$returnarray=testTimelinePosts();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});
*/




Flight::start();


function services_included()
{
	
	require_once 'services/myCompanyProfileServices.php'; 
	require_once 'services/readOnlyServices.php'; 
	require_once 'services/userLoginServices.php'; 
	require_once 'services/userIDBasedServices.php'; 
	require_once 'services/myProfileServices.php'; 
	require_once 'services/directoryServices.php'; 
	require_once 'services/imageUploadServices.php'; 
	require_once 'services/followUnfollowServices.php'; 
	require_once 'services/timelineServices.php'; 
	require_once 'services/emailServices.php'; 
	require_once 'services/sectionServices.php'; 
	require_once 'services/notificationServices.php'; 
}


//Arshad
//For base_url()
function base_url(){
	$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
	$base_url .= "://".$_SERVER['HTTP_HOST'].'/'; //will give : http(s)://somewebsite.com/
	
	/*----- Want to change something then change it here ------*/
	$base_url .= 'projects/entreprenity/#/'; 
	
	return $base_url;
}





//Function to fetch a company's follower list
//May 13,2016
function getCompanyFollowers()
{
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends
		
	$companyUserName=validate_input($_GET['company']);
	$companyid=getCompanyIdfromCompanyUserName($companyUserName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$data= array();	
	
	$data=fetch_company_information_from_companyid($companyid);
	
	if($my_session_id)
	{
		$data['followed']= doIFollowThisCompany($my_session_id,$companyid);
	}
	
	$data['followersObjects']		= getThisCompanyfollowerObjects($companyid);
	
	return $data;
	/*	
	data = {
			"id": 1,
			"companyUserName": "nbbit",
			"profilePhoto": "company01.jpg",
			"coverPhoto": "memberCover01.jpg",
			"name": "Pet Studio.com",
			"location": "Fort Legend Tower",
			"followed": true,
			"followers": "1",
			"following": "1",
			"followersObjects": [
				{
					"id": 1,
					"username": "jordan",
					"avatar": "member01.jpg",
					"coverPhoto": "memberCover01.jpg",
					"firstName": "Jordan",
					"lastName": "Rains",
					"position": "Office Assistant",
					"company": [{
						"companyName": "Pet Studio.com",
						"companyDesc": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
					}]
				}
			]
		};	
	*/

}

//Function to fetch a member's follower list
//May 13,2016
function getMemberFollowers()
{
	
   //the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends

	$userName=validate_input($_GET['user']);
	$clientid=getUserIdfromUserName($userName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$data= array();	
	
	$data									= fetch_user_information_from_id($clientid);
	if($my_session_id)
	{
		$data['followed']				= doIFollowThisUser($my_session_id,$clientid);
	}
	
	$data['followersObjects']		= getThisUserfollowerObjects($clientid);
	
	return $data;

}


//Function to fetch a member's following list
//May 13, 2016
function getMemberFollowing()
{
	
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends

	$userName=validate_input($_GET['user']);
	$clientid=getUserIdfromUserName($userName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$data= array();	
	
	$data									= fetch_user_information_from_id($clientid);
	if($my_session_id)
	{
		$data['followed']				= doIFollowThisUser($my_session_id,$clientid);
	}
	
	$data['followingObjects']		= getThisUserfollowingObjects($clientid);
	
	return $data;		

		/*
		data = {
			"id": 1,
			"userName": "jordan",
			"avatar": "member01.jpg",
			"coverPhoto": "memberCover01.jpg",
			"firstName": "Jordan",
			"lastName": "Rains",
			"position": "Office Assistant",
			"company": [
				{
					"companyName": "Pet Studio.com",
					"companyDesc": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
				}
			],
			"followed": true,
			"followers": "1",
			"following": "1",
			"followingObjects": [
				{
					"id": 1,
					"username": "jordan",
					"avatar": "member01.jpg",
					"coverPhoto": "memberCover01.jpg",
					"firstName": "Jordan",
					"lastName": "Rains",
					"position": "Office Assistant",
					"company": [{
						"companyName": "Pet Studio.com",
						"companyDesc": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
					}]
				}
			]
		};
	 */

}



//Function to fetch basic user information
//May 09,2016
function getBasicUserInformation()
{
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends
	
	$data= array();
  
	//$userid=validate_input($_GET['id']);
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$userid=$my_session_id;
	if($userid)
	{
			$qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,
					 		 client_profile.avatar,client_profile.designation,client_profile.company_name,client_profile.cover_pic,
					 		 company_profiles.company_username
					FROM entrp_login
					LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
					LEFT JOIN company_profiles ON entrp_login.clientid=company_profiles.clientid
					WHERE entrp_login.clientid=".$userid."
			      ";
			$res=getData($qry);
		   $count_res=mysqli_num_rows($res);
		   if($count_res>0)
		   {
		   	while($row=mysqli_fetch_array($res))
		   	{
		   		$data['id']			=	$row['clientid'];
		   		
		   		if($row['avatar']!='')
   				{
   					$data['avatar']				=	$row['avatar'];
   				}
   				else
   				{
   					$data['avatar']				=	$member_default_avatar;
   				}
   				if($row['cover_pic']!='')
   				{
   					$data['coverPhoto']				=	$row['cover_pic'];
   				}
   				else
   				{
   					$data['coverPhoto']				=	'';
   				}
   				
   				if($row['firstname']!='')
   				{
   					$data['firstName']			=	$row['firstname'];
   				}
   				else
   				{
   					$data['firstName']			=	'';
   				}
   				
   				if($row['lastname']!='')
   				{
   					$data['lastName']				=	$row['lastname'];
   				}
   				else
   				{
   					$data['lastName']				=	'';
   				}
   				
   				if($row['username']!='')
   				{
   					$data['userName']				=	$row['username'];
   				}
   				else
   				{
   					$data['userName']				=	'';
   				}
   				
   				if($row['designation']!='')
   				{
   					$data['position']				=	$row['designation'];
   				}
   				else
   				{
   					$data['position']				=	'';
   				}
   				
   				if($row['company_name']!='')
   				{
   					$data['myOffice']				=	$row['company_name'];
   				}
   				else
   				{
   					$data['myOffice']				=	'';
   				}
   				
   				if($row['company_username']!='')
   				{
   					$data['companyUserName']				=	$row['company_username'];
   				}
   				else
   				{
   					$data['companyUserName']				=	'';
   				}
		   	}		   	   
		   }
		   else
		   {
					$data['id']					=	'';
					$data['avatar']			=	'';
					$data['firstName']		=	'';
					$data['lastName']			=	'';
					$data['userName']			=	'';
					$data['position']			=	'';
					$data['myOffice']			=	'';		   
		   }	
	}
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
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends
	
	$companyUserName=validate_input($_GET['id']);
	$companyid=getCompanyIdfromCompanyUserName($companyUserName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$data= array();	
	
	if($my_session_id)
	{
		$data['followed']= doIFollowThisCompany($my_session_id,$companyid);
	}
	
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
   		$data['companyUserName']=	$row['company_username'];
   		$data['location']			=	$row['located_at'];
   		
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
   			$data['profilePhoto']	=	$company_default_avatar;
   		}     				
   		$data['website']			=	$row['website'];
   		$data['email']				=	$row['email'];
   		$data['mobile']			=	$row['mobile'];
   		$data['tel']				=	$row['telephone'];
   		$data['fax']				=	$row['fax'];
   		$data['desc']				=	$row['description'];
   		
   		$data['followers']		=	entrp_company_follows($companyid);
   		$data['categories']		=  fetch_company_categories($companyid);

   	}
   	
	}
	else
	{
		$data['id']				=	'';
		$data['name']			=	'';
		$data['companyUserName']='';
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
	  
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends
	
	$eventid=validate_input($_GET['id']);
	$data= array();		
	
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
   	
		$data['joining']			=	goingForThisEventorNot($eventid);
		$data['attendees']		=	getEventAttendeesFromEventID($eventid);
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




//Function to fetch a user profile
//April 22,2016
//Updated on May 03, 2016: to fetch secondary mobile and designation
//Updated May 11, 2016: To check if I follow this user or not
function viewUserProfile()
{
	//$clientid=validate_input($_GET['id']);
	
	$userName=validate_input($_GET['id']);
	$clientid=getUserIdfromUserName($userName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$data= array();	
	
	if($my_session_id)
	{
		$data['followed']= doIFollowThisUser($my_session_id,$clientid);
	}
	
  //the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends

  $qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,client_profile.city,client_profile.country,client_profile.contact_email as email,
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
      	
      	
   		if($row['avatar']!='')
   		{
   			$data['avatar']	=	$row['avatar'];
   		}
   		else
   		{
   			$data['avatar']	=	$member_default_avatar;
   		}  
   		   		
   		if($row['cover_pic']!='')
   		{
   			$data['coverPhoto']	=	$row['cover_pic'];
   		}
   		else
   		{
   			$data['coverPhoto']	=	'';
   		}  

			$data['firstName'] 	= 	$row['firstname'];
			$data['lastName'] 	= 	$row['lastname'];
			$data['userName'] 	= 	$row['username'];
			$data['position'] 	= 	$row['designation'];
			
			$data['city'] 			= 	$row['city'];
			
			$data['aboutMe'] 		=  $row['about_me'];
			$data['email'] 		=  $row['email'];
			$data['website'] 		=  $row['website'];
			$data['mobile'] 		=  $row['mobile'];
			$data['tel'] 			=  $row['secondary_mobile'];
			
			$data['company']['companyName'] 		= $row['company_name'];
			$data['company']['companyDesc'] 		= $row['description'];

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
		$data['msg'] = 'Please check your credentials once again';
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
	$sessions['username'] 		= $_SESSION['username'];
	
	return $sessions;
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
