<?php

//Function to fetch all business opportunities
//June 15,2016
function getAllBusinessOpportunities()
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
		
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.created_at,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc 
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.status=1 AND EUT.business_opp=1
			ORDER BY EUT.created_at DESC";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id'];      	
			$data[$i]['content']										=	$row['content'];
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['created_at']									=	$row['created_at'];
			
			$data[$i]['post_author']['id']						=	$row['clientid'];
			$data[$i]['post_author']['firstName']				=	$row['firstname'];
			$data[$i]['post_author']['lastName']				=	$row['lastname'];
			if($row['avatar']!='')
			{
				$data[$i]['post_author']['avatar']				=	$row['avatar'];
			}
			else
			{
				$data[$i]['post_author']['avatar']				=	$member_default_avatar;
			}
   				
			$data[$i]['post_author']['position']				=	$row['designation'];
			$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;
}

//Function to get post id of last inserted timeline post
//June 15,2016 (This can be temporary; Find another way- a better way)
function getLastTimelinePostID($posted_by,$created_at,$content)
{
	$qry="SELECT post_id FROM entrp_user_timeline  
			WHERE content='".$content."' AND created_at='".$created_at."' AND posted_by=".$posted_by." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['post_id'];  					
		}
		return $id;
	}
	else
	{
		return null;
	}  

}

//Function to post a business opportunity
//June 15,2016
function postABusinessOpportunity()
{
	$data= array();
	$categories=array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$content	= validate_input($_POST['postContent']['content']);
		
		if(!empty($_POST['postContent']['categories']))
		{
			$business_op=1;
			$count_category=count($_POST['postContent']['categories']);
			for($i=0;$i<$count_category;$i++)	
			{
				$categories[$i]	=	$_POST['postContent']['categories'][$i]['text'];
				$category_json		=  json_encode($categories);
			}
		}
		else
		{
			$business_op=0;
		}
		
		$post_img='';
		$created_at=date('Y-m-d H:i:s');
		$posted_by=$my_session_id;
		
		
		//$data['postContent']=$content;
		//$data['postCategories']=$categories;
		
		$qry="INSERT INTO entrp_user_timeline(content,post_img,created_at,posted_by,business_opp) VALUES('".$content."','".$post_img."','".$created_at."',".$posted_by.",".$business_op.")";
		if(setData($qry))
		{
			$postID  = getLastTimelinePostID($posted_by,$created_at,$content);
			if($business_op==1)
			{
				$qry2="INSERT INTO entrp_user_timeline_businessopp_tags ( postid, business_tags) VALUES (".$postID.", '".$category_json."')";
				setData($qry2);  
			}
			
			$data['response']='success';
		}
		else
		{
			$data['response']='failed';
		}
		
	
	}
	return $data;

}

//Function to fetch a company timeline post based on company username
//June 13,2016
function getCompanyPosts()
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

	//I represent this post
	$companyUserName=validate_input($_GET['company']);
	
	//I am the logged in user.
	$session_values=get_user_session();
	$my_session_id	= (int)$session_values['id'];

	$companyId=getCompanyIdfromCompanyUserName($companyUserName);

	$companyMembers= getAllCompanyMemberIDs($companyId);
	//$companyMembersString = implode(",", $companyMembers);
	$companyMembersString = '1,2,3';
	
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.created_at,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc 
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.posted_by IN (".$companyMembersString.") AND EUT.status=1 
			ORDER BY EUT.created_at DESC";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id'];      	
			$data[$i]['content']										=	$row['content'];
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['created_at']									=	$row['created_at'];
			
			$data[$i]['post_author']['id']						=	$row['clientid'];
			$data[$i]['post_author']['firstName']				=	$row['firstname'];
			$data[$i]['post_author']['lastName']				=	$row['lastname'];
			if($row['avatar']!='')
			{
				$data[$i]['post_author']['avatar']				=	$row['avatar'];
			}
			else
			{
				$data[$i]['post_author']['avatar']				=	$member_default_avatar;
			}
   				
			$data[$i]['post_author']['position']				=	$row['designation'];
			$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;
}

//Function to fetch a single timeline post
//June 08,2016
function getThisPost()
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
	
	//I represent this post
	$post_id=validate_input($_GET['post']);
	
	//I am the logged in user.
	$session_values=get_user_session();
	$my_session_id	= (int)$session_values['id'];
	
	//Let me see to whom this post belongs
	$posted_by= whoIsTheAuthorOfThisPost($post_id);
	
	if($posted_by==$my_session_id)
	{
		//This post is mine. I'm the author
		$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.created_at,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc 
				FROM entrp_user_timeline AS EUT
				LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
				LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
				LEFT JOIN location_info AS LI ON LI.id=CP.client_location
				WHERE EUT.status=1 AND EUT.post_id=".$post_id."";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	while($row=mysqli_fetch_array($res))
	      {
	      	//$post_id												=	$row['post_id'];	      	
	      	$data['post_id']									=	$row['post_id'];      	
				$data['content']									=	$row['content'];
				$data['image']										=	$row['post_img'];
				$data['created_at']								=	$row['created_at'];
				
				$data['post_author']['id']						=	$row['clientid'];
				$data['post_author']['firstName']			=	$row['firstname'];
				$data['post_author']['lastName']				=	$row['lastname'];
				if($row['avatar']!='')
				{
					$data['post_author']['avatar']			=	$row['avatar'];
				}
				else
				{
					$data['post_author']['avatar']			=	$member_default_avatar;
				}
	   				
				$data['post_author']['position']				=	$row['designation'];
				$data['post_author']['companyName']			=	$row['company_name'];
				$data['post_author']['userName']				=	$row['username'];
				$data['post_author']['location']				=	$row['location_desc'];
				
				$data['isLiked']									= doILikeThisPost($post_id);
				$data['likes_count']								= howManyLikesThisPostReceived($post_id);
				$data['likers']									= usersWhoLikesThisPost($post_id);
				$data['comments_count']							= howManyCommentsThisPostReceived($post_id);
				$data['commenters']								= usersWhoCommentedThisPost($post_id);
				$data['comments']									= userCommentsForThisPost($post_id);
				
	      }	
	   }			
	}
	
	return $data;	
}



//Route to get timeline feeds of users I follow
//May 30,2016
//June 06, 2016: Added location (client centre location)
function getFollowedMembersPosts()
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
   
   $username=validate_input($_GET['user']);
	$myUserId	= getUserIdfromUserName($username);
	
	$usersIFollow= getAllUserIDsIFollow($myUserId);
	$usersIFollowString = implode(",", $usersIFollow);
		
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.created_at,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc 
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.posted_by IN (".$usersIFollowString.") AND EUT.status=1 
			ORDER BY EUT.created_at DESC";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id'];      	
			$data[$i]['content']										=	$row['content'];
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['created_at']									=	$row['created_at'];
			
			$data[$i]['post_author']['id']						=	$row['clientid'];
			$data[$i]['post_author']['firstName']				=	$row['firstname'];
			$data[$i]['post_author']['lastName']				=	$row['lastname'];
			if($row['avatar']!='')
			{
				$data[$i]['post_author']['avatar']				=	$row['avatar'];
			}
			else
			{
				$data[$i]['post_author']['avatar']				=	$member_default_avatar;
			}
   				
			$data[$i]['post_author']['position']				=	$row['designation'];
			$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;	

	
	/*
	var posts = [
		{
			"post_id": "123456",
			"content": "Hi, we recently noticed an increased sign up for our eVoiceMail.net service particularly from users from US. Anyone know why and is interested to help us to market our service to even more peeps?",
			"image": "jpg01.jpg",
			"created_at": "2015-05-12T14:54:31.566Z",
			"post_author": {
				"id": "1",
				"firstName": "Jordan",
				"lastName": "Rains",
				"avatar": "member-default.jpg",
				"position": "Office Assistant",
				"companyName": "Pet Studio.com",
				"userName": "jordan"
			},
			"isLiked": false,
			"likes_count": 1,
			"likers": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments_count": 1,
			"commenters": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments": [
				{
					"content": "congrats Albert!",
					"created_at": "2015-05-12T15:06:51.457Z",
					"likes_count": 0,
					"likers": [],
					"comment_author": {
						"id": "3",
						"firstName": "John",
						"lastName": "Smith",
						"avatar": "member-default.jpg",
						"position": "Creative Director",
						"companyName": "Wendy Skelton",
						"userName": "John"
					}
				}
			]
		}
	];

	vm.posts = posts;
	*/

}


//Route to get timeline feeds of all users
//May 30,2016
//June 06,2016: Added user location (centre location)
function getAllPosts()
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
		
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.created_at,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc 
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.status=1 
			ORDER BY EUT.created_at DESC";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id'];      	
			$data[$i]['content']										=	$row['content'];
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['created_at']									=	$row['created_at'];
			
			$data[$i]['post_author']['id']						=	$row['clientid'];
			$data[$i]['post_author']['firstName']				=	$row['firstname'];
			$data[$i]['post_author']['lastName']				=	$row['lastname'];
			if($row['avatar']!='')
			{
				$data[$i]['post_author']['avatar']				=	$row['avatar'];
			}
			else
			{
				$data[$i]['post_author']['avatar']				=	$member_default_avatar;
			}
   				
			$data[$i]['post_author']['position']				=	$row['designation'];
			$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;	

	
	/*
	var posts = [
		{
			"post_id": "123456",
			"content": "Hi, we recently noticed an increased sign up for our eVoiceMail.net service particularly from users from US. Anyone know why and is interested to help us to market our service to even more peeps?",
			"image": "jpg01.jpg",
			"created_at": "2015-05-12T14:54:31.566Z",
			"post_author": {
				"id": "1",
				"firstName": "Jordan",
				"lastName": "Rains",
				"avatar": "member-default.jpg",
				"position": "Office Assistant",
				"companyName": "Pet Studio.com",
				"userName": "jordan"
			},
			"isLiked": false,
			"likes_count": 1,
			"likers": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments_count": 1,
			"commenters": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments": [
				{
					"content": "congrats Albert!",
					"created_at": "2015-05-12T15:06:51.457Z",
					"likes_count": 0,
					"likers": [],
					"comment_author": {
						"id": "3",
						"firstName": "John",
						"lastName": "Smith",
						"avatar": "member-default.jpg",
						"position": "Creative Director",
						"companyName": "Wendy Skelton",
						"userName": "John"
					}
				}
			]
		}
	];

	vm.posts = posts;
	*/

}


//To fetch user data from entrp_login using postId
//Arshad
function fetchLoginInfoUsingPostid($postId){
	$data = array();
	
	$qry = "
				SELECT L.* 
				FROM entrp_login as L 
				JOIN entrp_user_timeline as UT ON UT.posted_by = L.clientid
				WHERE UT.post_id = ".$postId."
			 ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data['clientid'] = $row['clientid'];
      	$data['username'] = $row['username'];
      	$data['email'] = $row['email'];					
		}
	}
	return $data;
}

//Function to unlike a timeline post\
//May 20,2016
function unlikeThisPost()
{
	$data= array();
	$data1= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$unlikedPostId = $requestData->unlikedPostId;
		$postId=validate_input($unlikedPostId);
		
		$likedUsersID=fetchUsersIDWhoLikesThisPost($postId);
		if (!empty($likedUsersID)) 
		{
			//if user not liked it
			if(in_array($my_session_id,$likedUsersID))
			{
				if (($key = array_search($my_session_id, $likedUsersID)) !== false) 
				{
				    unset($likedUsersID[$key]);
				}
				
				if(!empty($likedUsersID))
				{	
					//$likedUsersIDArr[]		=	$likedUsersID;		
					$likedUsersIDJSON			= json_encode($likedUsersID);
					/*
					$qry0="UPDATE entrp_user_timeline_post_likes SET liked_user_ids='' WHERE  post_id=".$postId."";
					setData($qry0);
					*/
					$qry="UPDATE entrp_user_timeline_post_likes SET liked_user_ids='".$likedUsersIDJSON."' WHERE  post_id=".$postId."";
					if(setData($qry))
					{
						$data['response']='success';
						
						//Let me see to whom this post belongs
						$Host=whoIsTheAuthorOfThisPost($postId);
						$notify_type="like";
						$notify_to=$Host;
						$notify_from=$my_session_id;
						$post_id=$postId;
						$notify_for="user";
						deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
					}
					else
					{
						$data['response']='failed';
					}
				}
				else
				{
					$qry2="DELETE FROM entrp_user_timeline_post_likes WHERE  post_id=".$postId."";
					if(setData($qry2))
					{
						$data['response']='success';
						
						//Let me see to whom this post belongs
						$Host=whoIsTheAuthorOfThisPost($postId);
						$notify_type="like";
						$notify_to=$Host;
						$notify_from=$my_session_id;
						$post_id=$postId;
						$notify_for="user";
						deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
					}
					else
					{
						$data['response']='failed';
					}
				}
				
			}
		}
	}
	return $data;
}


//Function to fetch userIDs who liked this timeline post
//May 20,2016
function fetchUsersIDWhoLikesThisPost($post_id)
{
	$data= array();

	$qry="SELECT liked_user_ids FROM entrp_user_timeline_post_likes  
			WHERE post_id=".$post_id."";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['liked_user_ids']);  					
		}
	}
	return $data;
}

//Function to like a timelinePost
//May 20,2016
function likeThisPost()
{
	$data= array();
	$data1= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$my_session_username	= $session_values['username'];
	
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$likedPostId = $requestData->likedPostId;
		$postId=validate_input($likedPostId);
		
		$likedUsersID=fetchUsersIDWhoLikesThisPost($postId);
		//$likedUsersIDArray = array_filter($likedUsersID);
		//if array is not empty
		if (!empty($likedUsersID)) 
		{
			//if user not liked it
			if(! in_array($my_session_id,$likedUsersID))
			{
				array_push($likedUsersID, $my_session_id);
				$likedUsersIDJSON		= json_encode($likedUsersID);
				
				$qry="UPDATE entrp_user_timeline_post_likes SET liked_user_ids='".$likedUsersIDJSON."' WHERE  post_id=".$postId."";
				if(setData($qry))
				{
					
					$postAuthorDetails = fetchLoginInfoUsingPostid($postId);
			
					if($my_session_id !== $postAuthorDetails['clientid'])
					{				
						$myPreferences = getMyPreferences();
					
						if($myPreferences['likes'] == 'true')
						{
							$notification_array = array(
															'type' => 'like',
															'postAuthorEmail' => $postAuthorDetails['email'],
															'postAuthorUsername' => $postAuthorDetails['username'],
															'likerUsername' => $my_session_username
														 );
							$data['mail_send'] = send_notification_mail($notification_array);
						}
						
						$Host=$postAuthorDetails['clientid'];
						$notify_type="like";
						$notify_to=$Host;
						$notify_from=$my_session_id;
						$post_id=$postId;
						$notify_for="user";
						addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
					}
			
					$data['response']='successssss';
					
				}
				else
				{
					$data['response']='failed';
				}
			}
		}
		else
		{
			array_push($data1, $my_session_id);
			$likedUsersIDJSON		= json_encode($data1);
			
			$qry2="INSERT INTO entrp_user_timeline_post_likes(post_id,liked_user_ids) VALUES(".$postId.",'".$likedUsersIDJSON."')";
			if(setData($qry2))
			{
				
				$postAuthorDetails = fetchLoginInfoUsingPostid($postId);
			
				if($my_session_id !== $postAuthorDetails['clientid'])
				{				
					$myPreferences = getMyPreferences();
				
					if($myPreferences['likes'] == 'true')
					{
						$notification_array = array(
														'type' => 'like',
														'postAuthorEmail' => $postAuthorDetails['email'],
														'postAuthorUsername' => $postAuthorDetails['username'],
														'likerUsername' => $my_session_username
													 );
						$data['mail_send'] = send_notification_mail($notification_array);
					}
					$Host=$postAuthorDetails['clientid'];
					$notify_type="like";
					$notify_to=$Host;
					$notify_from=$my_session_id;
					$post_id=$postId;
					$notify_for="user";
					addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
				}
					
				$data['response']='success';
			}
			else
			{
				$data['response']='failed';
			}		
		}
	
	}
	return $data;
}


//Function to post a comment for a timeline post
//May 20,2016
function postThisComment()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$my_session_username	= $session_values['username'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$timelinePostId = $requestData->postId;
		$postId=validate_input($timelinePostId);
		$newComment = $requestData->postComment;
		$comment=validate_input($newComment);
		
		$post_img='';
		$created_at=date('Y-m-d H:i:s');
		$posted_by=$my_session_id;

		$qry="INSERT INTO entrp_user_timeline_post_comments(post_id,comment,commented_by,commented_at) VALUES(".$postId.",'".$comment."',".$posted_by.",'".$created_at."')";
		if(setData($qry))
		{
			$postAuthorDetails = fetchLoginInfoUsingPostid($postId);
			
			if($my_session_id !== $postAuthorDetails['clientid'])
			{
				$myPreferences = getMyPreferences();
			
				if($myPreferences['comments'] == 'true'){
					$notification_array = array(
													'type' => 'comment',
													'postAuthorEmail' => $postAuthorDetails['email'],
													'postAuthorUsername' => $postAuthorDetails['username'],
													'commentAuthorUsername' => $my_session_username
												 );
					$data['mail_send'] = send_notification_mail($notification_array);
				}
				
				$Host=$postAuthorDetails['clientid'];
				$notify_type="comment";
				$notify_to=$Host;
				$notify_from=$my_session_id;
				$post_id=$postId;
				$notify_for="user";
				addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
			}
			
			$data['response']='success';
		}
		else
		{
			$data['response']='failed';
		}
	
	}
	return $data;
}


//Function to fetch users who liked this comment
//May 19,2016
function usersWhoLikedThisComment($commentId)
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

	$data0= array();
	$data= array();
	$likes_count= 0;
	
	$qry0="SELECT liked_user_ids FROM entrp_user_timeline_comments_likes  
			WHERE comment_id=".$commentId." ";
	$res0=getData($qry0);
   $count_res0=mysqli_num_rows($res0);
	if($count_res0>0)
	{
		while($row0=mysqli_fetch_array($res0))
		{
			$data0		=	json_decode($row0['liked_user_ids']);  					
		}
		
		$likers_array = implode(",", $data0);
		
		$qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,
			 		 client_profile.avatar,client_profile.designation,client_profile.company_name 
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			WHERE entrp_login.clientid IN(".$likers_array.")
	      ";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	$i=0;
	   	while($row=mysqli_fetch_array($res))
	   	{
	   		$data[$i]['id']			=	$row['clientid'];
	   		
	   		if($row['avatar']!='')
				{
					$data[$i]['avatar']				=	$row['avatar'];
				}
				else
				{
					$data[$i]['avatar']				=	$member_default_avatar;
				}
				
				if($row['firstname']!='')
				{
					$data[$i]['firstName']			=	$row['firstname'];
				}
				else
				{
					$data[$i]['firstName']			=	'';
				}
				
				if($row['lastname']!='')
				{
					$data[$i]['lastName']			=	$row['lastname'];
				}
				else
				{
					$data[$i]['lastName']			=	'';
				}
				
				if($row['username']!='')
				{
					$data[$i]['userName']			=	$row['username'];
				}
				else
				{
					$data[$i]['userName']			=	'';
				}
				
				if($row['designation']!='')
				{
					$data[$i]['position']			=	$row['designation'];
				}
				else
				{
					$data[$i]['position']			=	'';
				}
				
				if($row['company_name']!='')
				{
					$data[$i]['companyName']		=	$row['company_name'];
				}
				else
				{
					$data[$i]['companyName']		=	'';
				}
				$i++;
	   	}		   	   
	   }
	   else
	   {
				$data['id']					=	'';
				$data['firstName']		=	'';
				$data['lastName']			=	'';
				$data['avatar']			=	'';
				$data['position']			=	'';
				$data['companyName']		=	'';
				$data['userName']			=	'';		   
	   }		
	}
	return $data;

}


//Function to get count of how many users liked this comment
//May 19,2016
function howManyLikesThisCommentReceived($commentId)
{

	$data= array();
	$likes_count= 0;
	
	$qry="SELECT liked_user_ids FROM entrp_user_timeline_comments_likes  
			WHERE comment_id=".$commentId." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['liked_user_ids']);  					
		}
		
		$likes_count=sizeof($data);
	}   
	return $likes_count;	

}

//Function to count how many comments this timeline post received
//May 19,2016
function howManyCommentsThisPostReceived($post_id)
{
	//SELECT COUNT(post_comments_id) AS comments_count FROM entrp_user_timeline_post_comments WHERE post_id=1
	$data= array();
	$comments_count= 0;
	
	$qry="SELECT COUNT(post_comments_id) AS comments_count FROM entrp_user_timeline_post_comments  
			WHERE post_id=".$post_id." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$comments_count		=	$row['comments_count'];  					
		}
	}   
	return $comments_count;
}

//Function to fetch users who commented on this timeline post
//May 19,2016
function usersWhoCommentedThisPost($post_id)
{
	/*
	"commenters": [
			{
				"id": "3",
				"firstName": "John",
				"lastName": "Smith",
				"avatar": "member-default.jpg",
				"position": "Creative Director",
				"companyName": "Wendy Skelton",
				"userName": "John"
			}
		]
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
		
	$data0= array();
	$data= array();
	
	//SELECT commented_by FROM entrp_user_timeline_post_comments WHERE post_id=1
	$qry0="SELECT DISTINCT commented_by FROM entrp_user_timeline_post_comments  
			WHERE post_id=".$post_id." ";
	$res0=getData($qry0);
   $count_res0=mysqli_num_rows($res0);
	if($count_res0>0)
	{
		while($row0=mysqli_fetch_array($res0))
		{
			$data0[]		=	$row0['commented_by'];  
							
		}
		$commenters_array = implode(",", $data0);
		
		$qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,
			 		 client_profile.avatar,client_profile.designation,client_profile.company_name 
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			WHERE entrp_login.clientid IN(".$commenters_array.")
	      ";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	$i=0;
	   	while($row=mysqli_fetch_array($res))
	   	{
	   		$data[$i]['id']			=	$row['clientid'];
	   		
	   		if($row['avatar']!='')
				{
					$data[$i]['avatar']				=	$row['avatar'];
				}
				else
				{
					$data[$i]['avatar']				=	$member_default_avatar;
				}
				
				if($row['firstname']!='')
				{
					$data[$i]['firstName']			=	$row['firstname'];
				}
				else
				{
					$data[$i]['firstName']			=	'';
				}
				
				if($row['lastname']!='')
				{
					$data[$i]['lastName']			=	$row['lastname'];
				}
				else
				{
					$data[$i]['lastName']			=	'';
				}
				
				if($row['username']!='')
				{
					$data[$i]['userName']			=	$row['username'];
				}
				else
				{
					$data[$i]['userName']			=	'';
				}
				
				if($row['designation']!='')
				{
					$data[$i]['position']			=	$row['designation'];
				}
				else
				{
					$data[$i]['position']			=	'';
				}
				
				if($row['company_name']!='')
				{
					$data[$i]['companyName']		=	$row['company_name'];
				}
				else
				{
					$data[$i]['companyName']		=	'';
				}
				$i++;
	   	}		   	   
	   }
	   else
	   {
				$data['id']					=	'';
				$data['firstName']		=	'';
				$data['lastName']			=	'';
				$data['avatar']			=	'';
				$data['position']			=	'';
				$data['companyName']		=	'';
				$data['userName']			=	'';		   
	   }		
	}
	return $data;
}


//Function to fetch comments made on this timeline post
//May 19,2016
//June 06,2016: Added user location (centre location)
//June 14,2016: Sorted comments based on datetime posted
function userCommentsForThisPost($post_id)
{
	
	//SELECT ETC.post_comments_id,ETC.post_id,ETC.comment,ETC.commented_by,ETC.commented_at,
	//EP.firstname,EP.lastname,EP.username,
	//CP.avatar,CP.designation,CP.company_name
	//FROM entrp_user_timeline_post_comments AS ETC
	//LEFT JOIN entrp_login AS EP ON EP.clientid=ETC.commented_by 
	//LEFT JOIN client_profile AS CP ON EP.clientid=CP.clientid
	//WHERE ETC.post_id=1 AND ETC.status=1

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
	
	$i=0;
   
	$data= array();
	$qry="SELECT ETC.post_comments_id,ETC.post_id,ETC.comment,ETC.commented_by,ETC.commented_at,
			EP.firstname,EP.lastname,EP.username,
			CP.avatar,CP.designation,CP.company_name,LI.location_desc
			FROM entrp_user_timeline_post_comments AS ETC
			LEFT JOIN entrp_login AS EP ON EP.clientid=ETC.commented_by 
			LEFT JOIN client_profile AS CP ON EP.clientid=CP.clientid
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE ETC.post_id=".$post_id." AND ETC.status=1 
			ORDER BY ETC.commented_at ASC
			";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$commentId												=  $row['post_comments_id'];
			$data[$i]['commentId']								=	$row['post_comments_id'];
			$data[$i]['content']									=	$row['comment'];
			$data[$i]['created_at']								=	$row['commented_at'];
			$data[$i]['likes_count']							=	howManyLikesThisCommentReceived($commentId);
			$data[$i]['likers']									=	usersWhoLikedThisComment($commentId);			
			
			$data[$i]['comment_author']['id']				=	$row['commented_by'];
			$data[$i]['comment_author']['firstName']		=	$row['firstname'];
			$data[$i]['comment_author']['lastName']		=	$row['lastname'];
			if($row['avatar']!='')
			{
				$data[$i]['comment_author']['avatar']		=	$row['avatar'];
			}
			else
			{
				$data[$i]['comment_author']['avatar']		=	$member_default_avatar;
			} 
			
			$data[$i]['comment_author']['location']				=	$row['location_desc'];
			$data[$i]['comment_author']['position']		=	$row['designation'];
			$data[$i]['comment_author']['companyName']	=	$row['company_name'];
			$data[$i]['comment_author']['userName']		=	$row['username'];
			 			
			$i++;
		}		
	}
	return $data;	
	
	
	/*
	"comments": [
		{
			"content": "congrats Albert!",
			"created_at": "2015-05-12T15:06:51.457Z",
			"likes_count": 0,
			"likers": [],
			"comment_author": {
				"id": "3",
				"firstName": "John",
				"lastName": "Smith",
				"avatar": "member-default.jpg",
				"position": "Creative Director",
				"companyName": "Wendy Skelton",
				"userName": "John"
			}
		}
	]
	*/

}

//Function to check whether the current user like this timeline post or not
//May 19,2016
function doILikeThisPost($postid)
{
	$data= array();
	$post_id=(int)$postid;
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id)
	{
		$qry="SELECT liked_user_ids FROM entrp_user_timeline_post_likes  
			WHERE post_id=".$post_id." ";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
		if($count_res>0)
		{
			while($row=mysqli_fetch_array($res))
			{
				$data		=	json_decode($row['liked_user_ids']);  					
			}
			
			if(in_array($my_session_id,$data))
			{
			    return true;
			}
			else
			{
				return false;
			}
		}
		{
			return false;
		}
	}
	else 
	{
		return false;
	}
}

//Function to fetch users who like this timeline post
//May 19,2016
function usersWhoLikesThisPost($post_id)
{
	//SELECT liked_user_ids FROM entrp_user_timeline_post_likes WHERE post_id=1
	
	
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
	
	$data0= array();
	$data= array();
	$likes_count= 0;
	
	$qry0="SELECT liked_user_ids FROM entrp_user_timeline_post_likes  
			WHERE post_id=".$post_id." ";
	$res0=getData($qry0);
   $count_res0=mysqli_num_rows($res0);
	if($count_res0>0)
	{
		while($row0=mysqli_fetch_array($res0))
		{
			$data0		=	json_decode($row0['liked_user_ids']);  					
		}
		
		$likers_array = implode(",", $data0);
		
		$qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,
			 		 client_profile.avatar,client_profile.designation,client_profile.company_name 
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			WHERE entrp_login.clientid IN(".$likers_array.")
	      ";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	$i=0;
	   	while($row=mysqli_fetch_array($res))
	   	{
	   		$data[$i]['id']			=	$row['clientid'];
	   		
	   		if($row['avatar']!='')
				{
					$data[$i]['avatar']				=	$row['avatar'];
				}
				else
				{
					$data[$i]['avatar']				=	$member_default_avatar;
				}
				
				if($row['firstname']!='')
				{
					$data[$i]['firstName']			=	$row['firstname'];
				}
				else
				{
					$data[$i]['firstName']			=	'';
				}
				
				if($row['lastname']!='')
				{
					$data[$i]['lastName']			=	$row['lastname'];
				}
				else
				{
					$data[$i]['lastName']			=	'';
				}
				
				if($row['username']!='')
				{
					$data[$i]['userName']			=	$row['username'];
				}
				else
				{
					$data[$i]['userName']			=	'';
				}
				
				if($row['designation']!='')
				{
					$data[$i]['position']			=	$row['designation'];
				}
				else
				{
					$data[$i]['position']			=	'';
				}
				
				if($row['company_name']!='')
				{
					$data[$i]['companyName']		=	$row['company_name'];
				}
				else
				{
					$data[$i]['companyName']		=	'';
				}
				$i++;
	   	}		   	   
	   }
	   else
	   {
				$data['id']					=	'';
				$data['firstName']		=	'';
				$data['lastName']			=	'';
				$data['avatar']			=	'';
				$data['position']			=	'';
				$data['companyName']		=	'';
				$data['userName']			=	'';		   
	   }		
	}
	return $data;

}

//Function to count how many likes a timeline post received
//May 19,2016
function howManyLikesThisPostReceived($post_id)
{
	//SELECT liked_user_ids FROM entrp_user_timeline_post_likes WHERE post_id=1
	
	$data= array();
	$likes_count= 0;
	
	$qry="SELECT liked_user_ids FROM entrp_user_timeline_post_likes  
			WHERE post_id=".$post_id." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['liked_user_ids']);  					
		}
		
		$likes_count=sizeof($data);
	}   
	return $likes_count;	
}


//Function to get my news feed to timeline
//May 18,2016
//June 06, 2016: Added location (client centre location)
function getMyNewsFeed()
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
   
   $username=validate_input($_GET['user']);
	$my_id	= getUserIdfromUserName($username);
		
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.created_at,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc 
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.posted_by=".$my_id." AND EUT.status=1 
			ORDER BY EUT.created_at DESC";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id'];      	
			$data[$i]['content']										=	$row['content'];
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['created_at']									=	$row['created_at'];
			
			$data[$i]['post_author']['id']						=	$row['clientid'];
			$data[$i]['post_author']['firstName']				=	$row['firstname'];
			$data[$i]['post_author']['lastName']				=	$row['lastname'];
			if($row['avatar']!='')
			{
				$data[$i]['post_author']['avatar']				=	$row['avatar'];
			}
			else
			{
				$data[$i]['post_author']['avatar']				=	$member_default_avatar;
			}
   				
			$data[$i]['post_author']['position']				=	$row['designation'];
			$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;	

	
	/*
	var posts = [
		{
			"post_id": "123456",
			"content": "Hi, we recently noticed an increased sign up for our eVoiceMail.net service particularly from users from US. Anyone know why and is interested to help us to market our service to even more peeps?",
			"image": "jpg01.jpg",
			"created_at": "2015-05-12T14:54:31.566Z",
			"post_author": {
				"id": "1",
				"firstName": "Jordan",
				"lastName": "Rains",
				"avatar": "member-default.jpg",
				"position": "Office Assistant",
				"companyName": "Pet Studio.com",
				"userName": "jordan"
			},
			"isLiked": false,
			"likes_count": 1,
			"likers": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments_count": 1,
			"commenters": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments": [
				{
					"content": "congrats Albert!",
					"created_at": "2015-05-12T15:06:51.457Z",
					"likes_count": 0,
					"likers": [],
					"comment_author": {
						"id": "3",
						"firstName": "John",
						"lastName": "Smith",
						"avatar": "member-default.jpg",
						"position": "Creative Director",
						"companyName": "Wendy Skelton",
						"userName": "John"
					}
				}
			]
		}
	];

	vm.posts = posts;
	*/

}

//Function to add new feed to timeline
//May 18,2016
function postCurrentPost()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$newPost = $requestData->newPost;
		$content=validate_input($newPost);
		
		$post_img='';
		$created_at=date('Y-m-d H:i:s');
		$posted_by=$my_session_id;
		
		$qry="INSERT INTO entrp_user_timeline(content,post_img,created_at,posted_by) VALUES('".$content."','".$post_img."','".$created_at."',".$posted_by.")";
		if(setData($qry))
		{
			$data['response']='success';
		}
		else
		{
			$data['response']='failed';
		}
	
	}
	return $data;

}


//Function to test time-line module
//November 31,2016
function testTimelinePosts()
{

	$resp= array();
	$data = json_decode(file_get_contents("php://input"));
	$email = $data->name;
	$pass = $data->date;
	$qry="INSERT INTO users(email,password) VALUES('".$email."','".$pass."')";
	if( setData($qry))
	{
		$resp['msg']				=	"saved";    
	} 
	else 
	{
		$resp['msg']				=	"notsaved";   
	}
	return $resp;

}

?>