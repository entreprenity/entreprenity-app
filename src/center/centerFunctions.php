<?php

//Function to get user's company name
function getCompanyName($entrpID)
{
	$companyName 	= 'Not Specified';
	//SELECT company_profiles.company_name FROM entrp_company_members LEFT JOIN company_profiles ON company_profiles.id=entrp_company_members.companyid WHERE entrp_company_members.clientid=1
	$qry = "SELECT company_profiles.company_name 
			  FROM entrp_company_members 
			  LEFT JOIN company_profiles ON company_profiles.id=entrp_company_members.companyid 
			  WHERE entrp_company_members.clientid=".$entrpID."";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      while($row = mysqli_fetch_array($res))
      {
      	$companyName 	= $row['company_name'];
		}
   } 
	return $companyName;
}


function fullURL()
{
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['REQUEST_URI']
  );
}

//Function to login a user into center
function logUserIntoThisCenter($clientid,$vofClientId,$locId)
{
	date_default_timezone_set('UTC');
	$loginDate=date("Y-m-d");
	$loginDateTime=date("Y-m-d H:i:s");
	$status=1;
	$qry="INSERT INTO entrp_center_login(entrpID,voffID,locID,loginDate,checkIn,status) VALUES(".$clientid.",".$vofClientId.",".$locId.",'".$loginDate."','".$loginDateTime."',".$status.") ";
	setData($qry);
}

//Function to fetch user info using qrcode token
function fetchUserInfoUsingQRCode($qrCode)
{
	$data = array();		
	$qry="SELECT L.*,C.avatar
			FROM entrp_login as L
			LEFT JOIN client_profile AS C ON L.clientid=C.clientid
			WHERE L.qrCode='".$qrCode."'
	      ";
	$res = getData($qry);
   $count_res = mysqli_num_rows($res);
	if($count_res > 0)
   {
   	while($row = mysqli_fetch_array($res))
      {
      	$data['clientid'] 	= $row['clientid'];
      	$data['username'] 	= $row['username'];
      	$data['email'] 		= $row['email'];
      	$data['firstname'] 	= $row['firstname'];
      	$data['lastname'] 	= $row['lastname'];
      	$data['voffStaff']	= $row['voff_staff'];
			$data['vofClientId']	= $row['vof_clientid'];			      	
			
			if($row['avatar']!='')
			{
				$data['avatar']	=	$row['avatar'];
			}
			else
			{
				$data['avatar']	=	'assets/img/members/member-default.jpg';
			}	      	
			
			$data['success'] = 'true';
		}
   }
   return $data;

}


//Function to check whether user qrcode is valid or not
function validateUserQRCode($qrCode)
{
	$qry = "SELECT * FROM entrp_login WHERE qrCode='".$qrCode."'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      return 1;
   } 
   else 
   {
      return 0;
   }
}


?>