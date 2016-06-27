<?php


//Function to update user avatar, company avatar, event poster
//May 06,2016
//June 16,2016: convert base64 to img, save path to db
function uploadTheImage()
{
	$data= array();
	$data='not uploaded';
	
	define('PROFILE_PIC', 'assets/img/members/');
	define('COMPANY_PIC', 'assets/img/companies/');
	define('EVENT_POSTER', 'assets/img/events/');
	
	define('PROFILE_PIC_UPL', '../assets/img/members/');
	define('COMPANY_PIC_UPL', '../assets/img/companies/');
	define('EVENT_POSTER_UPL', '../assets/img/events/');
	
	$session_values=get_user_session();
	$my_session_id				= $session_values['id'];
	$my_session_firstname 	= $session_values['firstname'];
	$my_session_lastname		= $session_values['lastname'];
	$my_session_username		= $session_values['username'];
	
	$upAt=date('YmdHis');
	
	$postdata 		= file_get_contents("php://input");
	$request 		= json_decode($postdata);
	
	$uploadType 	= (int)$request->uploadType;
	$uploadImg 		= $request->uploadImg;
	
	// $uploadType values
	// 1- member profile pic
   // 2- company profile pic
	// 3- events poster
	// 4- client profile cover photo
	// 5- client company profile photo

	$img = str_replace('data:image/png;base64,', '', $uploadImg);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	
	if($uploadType==1)
	{
		$fileName 	 = PROFILE_PIC.$my_session_username.$upAt.'.png';
		$filePath 	 = PROFILE_PIC_UPL.$my_session_username.$upAt.'.png';
	}
	
	if($uploadType==2)
	{
		$fileName     = COMPANY_PIC.$my_session_username.$upAt.'.png';
		$filePath 	  = COMPANY_PIC_UPL.$my_session_username.$upAt.'.png';
	}
	
	if($uploadType==3)
	{
		$fileName 	 = EVENT_POSTER.$my_session_username.$upAt.'.png';
		$filePath 	 = EVENT_POSTER_UPL. $my_session_username.$upAt.'.png';
	}
	
	$success = file_put_contents($filePath, $data);
	$result  = $success ? 1 : 0;	
	
	//client profile pic
	if($uploadType==1)
	{
		$qry1="UPDATE client_profile SET avatar='".$fileName."' WHERE clientid=".$my_session_id." ";
		if(setData($qry1))
		{
			$data='success';
		}
	}
	
	//client company profile pic
	if($uploadType==2)
	{
		$qry2="UPDATE company_profiles SET avatar='".$fileName."' WHERE clientid=".$my_session_id." ";
		if(setData($qry2))
		{
			$data='success';
		}
	}
	
	//event poster
	if($uploadType==3)
	{			
		$qry4="SELECT eventTag FROM entrp_events_users_tags WHERE userID=".$my_session_id."";
		$res4=getData($qry4);
	   $count_res4=mysqli_num_rows($res4);
	   if($count_res4>0)
	   {
	   	while($row4=mysqli_fetch_array($res4))
	      {
	      	$eventTag				=	$row4['eventTag'];
	      }
	      
	      $qry3="UPDATE entrp_events SET poster='".$fileName."' WHERE eventTagId='".$eventTag."' ";
			if(setData($qry3))
			{
				$data='success';
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
	}
	
	return $data;
	
}


?>