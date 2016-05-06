<?php


//Function to update user avatar
//May 06,2016
function updateMyProfileAvatar()
{
	$data= array();
	
	//$clientid=validate_input($_POST['userId']);
	//$clientid=1;
	//$userAvatar=validate_input($_POST['userAvatar']);
	
	$clientid=1;
	$postdata 		= file_get_contents("php://input");
	$request 		= json_decode($postdata);
	//$clientid 		= $request->userId;
	$userAvatar 	= $request->userAvatar;
	
	$qry="UPDATE client_profiles SET avatar='".$userAvatar."' WHERE clientid=".$clientid." ";
	setData($qry);
	return $qry;
	/*
	$data=fetch_user_information_from_id($clientid);
	return $data;
	*/
}


?>