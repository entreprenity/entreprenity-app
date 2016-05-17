<?php


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
		session_destroy();
		$resp['msg']				=	"unauthorized";   
		//$resp['msg']				=	$qry;  
	}
	return $resp;
}


//Function for forgot password feature
//April 15, 2016
function forgot_password()
{
	$data= array();
	$username=validate_input($_POST['username']);
	//check whether this email id exist on database
	$qry="SELECT clientid,firstname,lastname FROM entrp_login AS CI where CI.email='".$username."'";
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
 
 		$qry2="UPDATE entrp_login SET password='".md5($password)."' where email='".$username."' ";
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