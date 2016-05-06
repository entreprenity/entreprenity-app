<?php


//Function to update a user's profile information
//May 03,2016
function updateMyProfileDetails()
{

	$data= array();
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$firstName		=validate_input($_POST['firstName']);
	$lastName		=validate_input($_POST['lastName']);
	$position		=validate_input($_POST['position']);
	$aboutMe			=validate_input($_POST['aboutMe']);
	$website			=validate_input($_POST['website']);
	$mobile			=validate_input($_POST['mobile']);
	$tel				=validate_input($_POST['tel']);
	
	$qry="UPDATE client_profile SET designation='".$position."', mobile='".$mobile."', secondary_mobile='".$tel."',website='".$website."',about_me='".$aboutMe."' WHERE clientid=".$my_session_id." ";
	$qry2="UPDATE client_info SET firstname='".$firstName."', lastname='".$lastName."' WHERE clientid=".$my_session_id." ";
   if(setData($qry) && setData($qry2))
   {
   	//updation successful
   	$data=fetch_user_information_from_id($my_session_id);
		$data['success'] 		= true;
		$data['msg'] 			= 'User Profile updated.'; 
   }
   else
   {
   	$data['success'] 		= false;
		$data['msg'] 			= 'Something went wrong. Profile not updated.'; 
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
	$data= array();
	//$userid=validate_input($_GET['id']);
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$userid=$my_session_id;
	if($userid)
	{
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
		   	 $data['skills']				= $data['userSkills'];
		   	 //fetch user interests
		   	 $data['userInterests'] 	= get_user_interest_sets($userid);
		   	 $data['interests']			= $data['userInterests'];
		   	 
		   	 //Function to get total followers of a user
				 $data['followers'] 	= user_followers($userid);
		
				 //Function to get total followings of a user
				 $data['following'] 	= user_following($userid);
		   	   
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
	
	}
	return $data;
}


?>