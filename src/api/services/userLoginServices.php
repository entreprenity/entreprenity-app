<?php


//Function to generate unique password token
//July 22,2016
function generateUniquePasswordToken()
{
	$token = substr(md5(uniqid(rand(), true)),0,10);  // creates a 10 digit token
   $qry = "SELECT id FROM entrp_forgot_password_tokens WHERE forgotToken = '".$token."'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      generateUniquePasswordToken();
   } 
   else 
   {
      return $token;
   }	
}

//Function to destroy a user login token
//May 17,2016
function destroyUserToken()
{
	$resp= array();
	$data = json_decode(file_get_contents("php://input"));
	$token = $data->token;
	$qry="DELETE FROM client_login_tokens WHERE client_token='".$token."'";
	if( setData($qry))
	{
		session_start();
		session_destroy();
		$resp['msg']				=	"Logged out";    
	} 
	else 
	{
		$resp['msg']				=	"Active Session";   
	}
	return $resp;


}



//Function to validate a user token
//May 17,2016
function validateUserToken()
{
	$resp= array();
	session_start();
	if(isset($_SESSION))
 	{
		$data = json_decode(file_get_contents("php://input"));
		$token = $data->token;
		$qry="SELECT * FROM client_login_tokens WHERE client_token='".$token."' AND status=1";
		$res=getData($qry);
		$count_res=mysqli_num_rows($res); 
		if($count_res==1)
		{
			$resp['msg']				=	"authorized";   
			//$resp['msg']				=	$qry;   
		} 
		else 
		{
			if(!isset($_SESSION))
	 		{
	    		session_start();
	  		}
			session_destroy();
			$resp['msg']				=	"unauthorized";   
			//$resp['msg']				=	$qry;  
		}
  	}
	else
	{
		session_start();
		session_destroy();
		$resp['msg']		=	"unauthorized"; 
	}
	return $resp;
}


//Function for forgot password feature
//April 15, 2016
//July 27,2016: SMTP mail sending
function forgot_password()
{
	$data= array();
	$username=validate_input($_POST['username']);
	//check whether this email id exist on database
	$qry="SELECT clientid,firstname,lastname,email FROM entrp_login where email='".$username."' AND status=1 ";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);   
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$firstname		=	$row['firstname'];
			$lastname		=	$row['lastname'];
			$id				=	$row['clientid'];      
			$email			=	$row['email'];      
		}

		//if yes, start password reset process

		$fullname=$firstname.' '.$lastname;
		$passToken=generateUniquePasswordToken();

		$pathToFile				= "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$changePassURL			=  str_replace("api/forgotpassword","others/changepassword.php",$pathToFile);	
		$changePasswordURL	= $changePassURL."?token=".urlencode($passToken);

		$requestTime=date('Y-m-d H:i:s');
		$to_day = new DateTime($requestTime);
		$to_day->modify('+2 day');
		$expireTime= $to_day->format('Y-m-d H:i:s');
		
		ob_start();
		include('email_templates/forgot_password.php');
		$forgot_password_template = ob_get_contents();			
		ob_end_clean();			

		$to=$email; 
		$strSubject=FORGOTPASS_CONST;
		$message =  $forgot_password_template;              

		//$qry2="UPDATE entrp_login SET password='".md5($password)."' where email='".$email."' ";
		$qry2="INSERT INTO entrp_forgot_password_tokens(clientid,emailid,forgotToken,requestTime,expireTime) VALUES(".$id.",'".$email."','".$passToken."','".$requestTime."','".$expireTime."') ";
		if(setData($qry2))
		{
			include('sendmail/sendmail.php');	
			$mail->SetFrom(MS_SENTFROM, MS_SENTFROMNAME);
			$mail->Subject = ($strSubject);
			$mail->MsgHTML($message);
			$mail->AddAddress($to);
			//$mail->AddAddress(RECIPIENTEMAIL1, RECIPIENTNAME1);
			//$mail->AddAddress(RECIPIENTEMAIL2, RECIPIENTNAME2);
			if($mail->Send()) 
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
	$username=validate_input($_POST['username']);
	$password=validate_input($_POST['password']);

	$qry="SELECT * FROM entrp_login where email='".$username."' AND password='".md5($password)."' ";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data['firstname']	=	$row['firstname'];
			$data['lastname']		=	$row['lastname'];
			$data['id']				=	$row['clientid'];
			$data['username']		=	$row['username'];
			$data['success'] 		= true;
			$data['msg'] 			= 'Valid User';

			//generate a client token
			$client_session_token=generate_login_token();

            
            // server should keep session data for AT LEAST 1 hour
            //ini_set('session.gc_maxlifetime', 36000);

            // each client should remember their session id for EXACTLY 1 hour
            //session_set_cookie_params(36000);
            
			//set session
			session_start();
			$_SESSION['id'] 				= $data['id'];
			$_SESSION['firstname'] 		= $data['firstname'];
			$_SESSION['lastname'] 		= $data['lastname'];
			$_SESSION['login_token'] 	= $client_session_token;
			$_SESSION['username'] 	   = $data['username']; //added by arshad

			set_client_session_token($client_session_token,$row['clientid']);
			$data['login_token'] 			= $client_session_token;

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



?>