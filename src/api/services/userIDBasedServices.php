<?php

/* Functions and services based on userid begins */


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
	
	//SELECT company_profiles.id,company_profiles.company_name,company_profiles.description,company_profiles.avatar,company_profiles.city,company_profiles.cover_photo,
	//company_profiles.website,company_profiles.email,company_profiles.mobile,company_profiles.telephone,company_profiles.fax,
	//location_info.location_desc
	//FROM company_profiles
	//LEFT JOIN location_info on location_info.id=company_profiles.client_location
	//WHERE company_profiles.clientid=1

	$qry="SELECT company_profiles.id,company_profiles.company_name,company_profiles.description,company_profiles.avatar,company_profiles.city,company_profiles.cover_photo,
			 		 company_profiles.website,company_profiles.email,company_profiles.mobile,company_profiles.telephone,company_profiles.fax,
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
			$data['profilePhoto']	=	$row['avatar'];  			
			$data['coverPhoto']		=	$row['cover_photo'];  			
			$data['companyName']		=	$row['company_name'];  			
			$data['location']			=	$row['location_desc'];  			
			$data['companyDesc']		=	$row['description'];  			
			$data['email']				=	$row['email'];  			
			$data['website']			=	$row['website'];  			
			$data['mobile']			=	$row['mobile'];  			
			$data['tel']				=	$row['telephone'];  			
			$data['fax']				=	$row['fax'];  
			//$company_id=$data['id'];	
			//$data['categories']			= fetch_company_categories($company_id);			
		}
		
			
			
	}
	else 
	{
		$data['id']					=	'';  			
		$data['profilePhoto']	=	''; 		
		$data['coverPhoto']		=	''; 		
		$data['companyName']		=	''; 
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
   		$data[$k] 		= $row['skills'];
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


  $qry="SELECT client_info.clientid,client_info.firstname,client_info.lastname,client_info.city,client_info.country,client_info.email,
			 		 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.website,client_profile.about_me,
			 		 client_profile.secondary_mobile,
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
			$data['position'] 	= 	$row['designation'];
			$data['city'] 			= 	$row['city'];
			
			$data['aboutMe'] 		=  $row['about_me'];
			$data['email'] 		=  $row['email'];
			$data['website'] 		=  $row['website'];
			$data['mobile'] 		=  $row['mobile'];
			$data['tel'] 			=  $row['secondary_mobile'];
			
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
	   		$data['skills'][$k] 		= $row3['skills'];
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
		$data['msg'] = 'User Not Found';
   }
   return $data;
}



/* Functions and services based on userid ends */



?>