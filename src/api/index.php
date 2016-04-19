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

Flight::start();


//Function for forgot password feature
//April 15, 2016
function forgot_password()
{

}

//Function to login
//April 15, 2016
function login()
{
	$data= array();
	$content=json_decode(file_get_contents('php://input'));
	$ncontent=(array)	$content;
	//$username='kurt@petstudio.com';
	//$password=123456;
	$username=validate_input($ncontent['username']);
	$password=validate_input($ncontent['password']);
	
	$qry="SELECT * FROM client_info where email='".$username."' AND password='".md5($password)."' ";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data['firstname']	=	$row['firstname'];
			$data['lastName']	=	$row['lastname'];
			$data['id']				=	$row['clientid'];
			$data['success'] = true;
			$data['msg'] = 'Valid User';
			
			//generate a client token
			$client_session_token=generate_login_token();
			
			//session_start();
			//set session
			
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
	$data= array();	
	$qry="SELECT CI.clientid,CI.firstname,CI.lastname,CP.designation,CP.company_name,CP.avatar,CI.city 
	      FROM client_info AS CI 
	      LEFT JOIN client_profile AS CP ON CP.clientid=CI.clientid";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']				=	$row['clientid'];
			$data[$i]['firstName']		=	$row['firstname'];
			$data[$i]['lastName']		=	$row['lastname'];
			$data[$i]['avatar']			=	$row['avatar'];
			$data[$i]['position']		=	$row['designation'];
			$data[$i]['companyName']	=	$row['company_name'];
			$data[$i]['city']				=	$row['city'];
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
	$qry="SELECT  id,clientid,company_name,description,avatar,city FROM company_profiles";
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
