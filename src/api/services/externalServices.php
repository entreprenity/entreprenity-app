<?php

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
	
	//$timeofaction=date('Y-m-d H:i:s');
	
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
		
		//$land = "<script>window.location.href = '$landing';exit();</script>";
		/*
		echo "<script type=\"text/javascript\">
        			window.open('".$landing."', '_blank')
    			</script>";
    			*/
    	$serviceType=1;
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