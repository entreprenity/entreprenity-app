<?php

/* My Company Profile Services Begins  */


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

//Function to insert company categories
//May 05,2016
function	update_company_categories($company_id,$company_categories)
{
	//INSERT INTO entrp_company_categories (`id`, `companyid`, `category`) VALUES (NULL, '1', 'xcvxc');
	$qry1="SELECT id FROM entrp_company_categories WHERE companyid=".$company_id."";
	$res=getData($qry1);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
   {
   	$qry2="UPDATE entrp_company_categories SET category='".$company_categories."' WHERE companyid=".$company_id." ";
		setData($qry2); 
   }
   else
   {
   	$qry3="INSERT INTO entrp_company_categories ( companyid, category) VALUES (".$company_id.", '".$company_categories."')";
		setData($qry3);  
   }
}



//Function to update a user's company profile information
//May 03,2016
function updateMyCompanyDetails()
{

	$data= array();
	$company_categories=array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$companyName		=validate_input($_POST['companyName']);
	$companyDesc		=validate_input($_POST['companyDesc']);
	$email				=validate_input($_POST['email']);
	$website				=validate_input($_POST['website']);
	$mobile				=validate_input($_POST['mobile']);
	$telephone			=validate_input($_POST['tel']);
	$fax					=validate_input($_POST['fax']);
	
	
	if(!empty($_POST['categories']))
	{
		$count_category=count($_POST['categories']);
		for($i=0;$i<$count_category;$i++)	
		{
			$company_categories[$i]=$_POST['categories'][$i]['text'];
		}
	}	
	
	$qry="UPDATE company_profiles SET company_name='".$companyName."', description='".$companyDesc."', email='".$email."',website='".$website."',mobile='".$mobile."'
			,telephone='".$telephone."',fax='".$fax."' 
			WHERE clientid=".$my_session_id." ";
   if(setData($qry))
   {
   	//updation successful
   	$data=fetch_company_information_from_userid($my_session_id);
   	
   	$company_id=$data['id'];
		$category_json= json_encode($company_categories);
		//delete_company_categories($company_id);
		update_company_categories($company_id,$category_json);   	
   	$data['categories']	= fetch_company_categories($company_id);
   	
   	$data['followers']	= entrp_company_follows($company_id);
   	
		$data['success'] 		= true;
		$data['msg'] 			= 'Company Profile updated.'; 
   }
   else
   {
   	$data['success'] 		= false;
		$data['msg'] 			= 'Something went wrong. Profile not updated.'; 
   }
	return $data;

}


//Function to get a user's own company details
//May 03,2016
function getMyCompanyProfileDetails()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
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
			WHERE company_profiles.clientid=".$my_session_id."
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
			$data['tel']		=	$row['telephone'];  			
			$data['fax']				=	$row['fax'];  			
		}
		$company_id=$data['id'];
		$data['categories']	= fetch_company_categories($company_id);	
		$data['followers']	= entrp_company_follows($company_id);	
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
		$data['tel']		=	'';  			
		$data['fax']				=	''; 
	}
	
	return $data;
	/*
	{
	"id": 1,
	"profilePhoto": "member01.jpg",
	"coverPhoto": "memberCover01.jpg",
	"companyName": "vOffice",
	"location": "Fort Legend Tower",
	"companyDesc": "We provide businesses superior reach and access to South East Asia markets like Jakarta, Manila, Kuala Lumpur and Singapore.",
	"email": "info@voffice.com",
	"website": "voffice.com.ph",
	"mobile": "6322242000",
	"category": [
		"Virtual Office",
		"Serviced Office",
		"Coworking Space"
	],
	"allCategory" : []
	};
	*/
}


/* My Company Profile Services Ends */


?>