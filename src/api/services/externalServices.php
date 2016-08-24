<?php

//Function to check facebook connected or not
//August 24,2016
function checkFBConnectedorNot()
{
	$data = array();	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id) 
	{
		$qry="SELECT facebookID FROM entrp_login  
				WHERE clientid=".$my_session_id." ";
		$res=getData($qry);
		while($row=mysqli_fetch_array($res))
		{
			$facebookID		=	$row['facebookID'];  					
		}
		
		if($facebookID=='')
		{
			$data='notconnected';
		}
		else if($facebookID==0)
		{
			$data='notconnected';
		}
		else
		{
			$data='connected';
		}
	}	
	return $data;
}

//Function to revoke facebook connect
//August 24,2016
function unlinkFacebookAccount()
{
	$data = array();	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="UPDATE entrp_login 
				SET facebookID='',facebookEmail='' 
				WHERE clientid=".$my_session_id." ";
	if(setData($qry))
	{
		$data='notconnected';
	}	
	return $data;
}

//Function to save facebook connect data
//August 24,2016
function saveFacebookAuthData()
{
	$data = array();	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id) 
	{
		$fid				=	validate_input($_POST['fid']);
		$firstnameFB	=	validate_input($_POST['first_name']);
		$lastnameFB		=	validate_input($_POST['last_name']);
		$gender			=	validate_input($_POST['gender']);
		$email			=	validate_input($_POST['email']);
		$fbImage			=	validate_input($_POST['fbImage']);

		if($fbImage!='' && $fid!='')
		{
			$imgSRC= "//graph.facebook.com/".$fid."/picture?type=large";
			$qry0="UPDATE client_profile 
					SET avatar='".$imgSRC."'
					WHERE clientid=".$my_session_id." ";
			setData($qry0); 
		}		
		
		$qry="UPDATE entrp_login 
				SET facebookID='".$fid."',facebookEmail='".$email."',firstname='".$firstnameFB."',lastname='".$lastnameFB."',gender='".$gender."' 
				WHERE clientid=".$my_session_id." ";
		if(setData($qry))
		{
			$data='connected';
		}		
	}
	return $data;
}


//Function to invoke spaces service
//July 05,2016
function invokeSpaces()
{
	$data = array();	   
	$myData = array();
	$landing = "index.html";
		   
   $token=validate_input($_POST['token']);
   
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];	
	$userName	= $session_values['username'];	
	
	$myData = fetch_info_from_entrp_login($my_session_id);
		
	$loginEmail = $myData['email'];
	
	$loginPassword = fetchUserLoginPassword($loginEmail,$my_session_id);

	$cid= checkUserLoginClientInfo($loginEmail,$loginPassword);
	
	if($cid!="")
	{
		$authtoken			= md5($loginEmail.date('Y-m-d H:i:s'));
		$_SESSION['token']= $authtoken; 
		$_SESSION["cid"]	 = $cid;
		//setcookie('cid', $_SESSION["cid"], time() +  60 * 60 * 24 * 30, 'http://callanswering.me/app/');
		//setcookie('token', $_SESSION['token'], time() +  60 * 60 * 24 * 30, 'http://callanswering.me/app/');
		
    	$serviceType=2; //represents spaces
    	$logResp=logThisServiceAuthRequest($cid,$loginEmail,$loginPassword,$authtoken,$serviceType);
    	if($logResp!='')
    	{
    		$data=$logResp;
    	}
    	else
    	{
    		$data='failed';
    	}    	
	}
	else
	{
		$data='failed';
	}
	return $data;

}


//Function to invoke call answering service
//June 30,2016
function invokeCallAnswering()
{
	$data = array();	   
	$myData = array();
	$landing = "index.html";
		   
   $token=validate_input($_POST['token']);
	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];	
	$userName	= $session_values['username'];	
	
	$myData = fetch_info_from_entrp_login($my_session_id);
		
	$loginEmail = $myData['email'];
	
	$loginPassword = fetchUserLoginPassword($loginEmail,$my_session_id);

	$cid= checkUserLoginClientInfo($loginEmail,$loginPassword);
	
	if($cid!="")
	{
		$authtoken			= md5($loginEmail.date('Y-m-d H:i:s'));
		$_SESSION['token']= $authtoken; 
		$_SESSION["cid"]	 = $cid;
		//setcookie('cid', $_SESSION["cid"], time() +  60 * 60 * 24 * 30, 'http://callanswering.me/app/');
		//setcookie('token', $_SESSION['token'], time() +  60 * 60 * 24 * 30, 'http://callanswering.me/app/');
		
    	$serviceType=1; //represents call answer
    	$logResp=logThisServiceAuthRequest($cid,$loginEmail,$loginPassword,$authtoken,$serviceType);
    	if($logResp!='')
    	{
    		$data=$logResp;
    	}
    	else
    	{
    		$data='failed';
    	}    	
	}
	else
	{
		$data='failed';
	}
	return $data;
}


//Function to log an external service authentication request
//July 01,2016
function logThisServiceAuthRequest($cid,$loginEmail,$loginPassword,$authtoken,$serviceType)
{
	$createdAt=date('Y-m-d H:i:s');
	$status=1;
	$qry="INSERT INTO entrp_external_services_authentication(cid,email,hashedpass,token,authdatetime,servicetype,status) 
			VALUES(".$cid.",'".$loginEmail."','".$loginPassword."','".$authtoken."','".$createdAt."',".$serviceType.",".$status.")";
	if(setData($qry))
	{
		return $authtoken;	
	}
	else
	{
		return null;
	}
} 

//Function to check user's client_info information (validation for external service)
//June 30,2016
function checkUserLoginClientInfo($loginEmail,$loginPassword)
{
	$qry="SELECT clientid FROM entrp_login  
			WHERE email='".$loginEmail."' AND password='".$loginPassword."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$clientid		=	$row['clientid'];  					
		}
		return $clientid;
	}
	else
	{
		return null;
	}
}


?>