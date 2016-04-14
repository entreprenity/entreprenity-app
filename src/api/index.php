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


Flight::start();

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
			$data[$i]['avatar']			=	'members/'.$row['avatar'];
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
			$data[$i]['avatar']			=	'companies/'.$row['avatar'];
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
			$data[$i]['poster']			=	'events/'.$row['poster'];
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



function validate_input($input) 
{	
  $input = trim($input);
  //$input = stripslashes($input);
  $input = addslashes($input);
  $input = htmlspecialchars($input);
  return $input;
}


function enable_cors() 
{
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: X-Requested-With");	
	date_default_timezone_set('asia/singapore');
}


?>
