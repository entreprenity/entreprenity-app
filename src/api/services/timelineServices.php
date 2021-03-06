<?php


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
					$likedUsersIDJSON		= json_encode($likedUsersID);
					
					$qry="UPDATE entrp_user_timeline_post_likes SET liked_user_ids='".$likedUsersIDJSON."' WHERE  post_id=".$postId."";
					if(setData($qry))
					{
						$data['response']='success';
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
			
					if($my_session_id !== $postAuthorDetails['clientid']){
					
						$myPreferences = getMyPreferences();
					
						if($myPreferences['likes'] == 'true'){
							$notification_array = array(
															'type' => 'like',
															'postAuthorEmail' => $postAuthorDetails['email'],
															'postAuthorUsername' => $postAuthorDetails['username'],
															'likerUsername' => $my_session_username
														 );
							$data['mail_send'] = send_notification_mail($notification_array);
						}
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
			
				if($my_session_id !== $postAuthorDetails['clientid']){
				
					$myPreferences = getMyPreferences();
				
					if($myPreferences['likes'] == 'true'){
						$notification_array = array(
														'type' => 'like',
														'postAuthorEmail' => $postAuthorDetails['email'],
														'postAuthorUsername' => $postAuthorDetails['username'],
														'likerUsername' => $my_session_username
													 );
						$data['mail_send'] = send_notification_mail($notification_array);
					}
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
			
			if($my_session_id !== $postAuthorDetails['clientid']){
			
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

	$data0= array();
	$data= array();
	$likes_count= 0;
	$member_default_cover			='assets/img/members/member-default.jpg';
   $member_default_avatar			='assets/img/members/member-default.jpg';
	
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
		
	$data0= array();
	$data= array();
	$member_default_cover			='assets/img/members/member-default.jpg';
   $member_default_avatar			='assets/img/members/member-default.jpg';
	
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
function userCommentsForThisPost($post_id)
{
	
	//SELECT ETC.post_comments_id,ETC.post_id,ETC.comment,ETC.commented_by,ETC.commented_at,
	//EP.firstname,EP.lastname,EP.username,
	//CP.avatar,CP.designation,CP.company_name
	//FROM entrp_user_timeline_post_comments AS ETC
	//LEFT JOIN entrp_login AS EP ON EP.clientid=ETC.commented_by 
	//LEFT JOIN client_profile AS CP ON EP.clientid=CP.clientid
	//WHERE ETC.post_id=1 AND ETC.status=1
	
	$i=0;
	$member_default_cover			='assets/img/members/member-default.jpg';
   $member_default_avatar			='assets/img/members/member-default.jpg';
   
	$data= array();
	$qry="SELECT ETC.post_comments_id,ETC.post_id,ETC.comment,ETC.commented_by,ETC.commented_at,
			EP.firstname,EP.lastname,EP.username,
			CP.avatar,CP.designation,CP.company_name
			FROM entrp_user_timeline_post_comments AS ETC
			LEFT JOIN entrp_login AS EP ON EP.clientid=ETC.commented_by 
			LEFT JOIN client_profile AS CP ON EP.clientid=CP.clientid
			WHERE ETC.post_id=".$post_id." AND ETC.status=1
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
	
	$data0= array();
	$data= array();
	$likes_count= 0;
	$member_default_cover			='assets/img/members/member-default.jpg';
   $member_default_avatar			='assets/img/members/member-default.jpg';
	
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
function getMyNewsFeed()
{
	$data= array();
	$member_default_cover		='assets/img/members/member-default.jpg';
   $member_default_avatar		='assets/img/members/member-default.jpg';
   
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
		
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.created_at,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar 
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid
			WHERE EUT.posted_by=".$my_session_id." AND EUT.status=1 
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