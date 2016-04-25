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

Flight::start();


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
			//$data['followers'] 	= 	$row['clientid'];
			$data['followers'] 	= 	10;
			$data['following'] 	=  20;
			//$data['following'] 	=  $row['clientid'];
			//$data['aboutMe'] 		=  $row['about_me'];
			$data['aboutMe'] 		=  "This is hardcoded from backend. About me.";
			$data['email'] 		=  $row['email'];
			//$data['website'] 		=  $row['website'];
			$data['website'] 		=  "www.staticwebs.com";
			//$data['mobile'] 		=  $row['mobile'];
			$data['mobile'] 		=  "0123456789";
			
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
   	
   	//SELECT entrp_user_skills.skill_id,entrp_skills.skills FROM entrp_user_skills LEFT JOIN entrp_skills ON entrp_user_skills.skill_id=entrp_skills.id WHERE entrp_user_skills.user_id=1
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
	
	$qry="SELECT * FROM events";
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
