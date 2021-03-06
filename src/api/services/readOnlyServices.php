<?php

//Function to get all interest set
//May 02, 2016
function get_all_interest_sets()
{
	$i=0;
	$data= array();
	$qry="SELECT *  
			FROM entrp_interests
			WHERE status=1 
			";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			//$data[$i]['id']		=	$row['id'];
			$data[$i]		=	$row['interest'];  			
			$i++;
		}		
	}
	return $data;

}

//Function to get all skill set
//May 02, 2016
function get_all_skill_sets()
{
	$i=0;
	$data= array();
	$qry="SELECT *  
			FROM entrp_skills
			WHERE status=1 
			";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			//$data[$i]['id']		=	$row['id'];
			$data[$i]		=	$row['skills'];  			
			$i++;
		}		
	}
	return $data;
}

//Function to get all languages to show in the profile preferences
//May 24, 2016
//Arshad
function get_all_languages()
{
	$i = 0;
	$data = array();
	$qry = "SELECT *  
			 FROM entrp_languages
			 WHERE lang_status = 1 
			 ";
   $res = getData($qry);
	$count_res = mysqli_num_rows($res);
	if($count_res > 0)
	{
		while($row = mysqli_fetch_array($res))
		{
			$data[$i]['id']		=	$row['lang_id'];
			$data[$i]['text']		=	$row['lang_name'];
			$data[$i]['image']		=	$row['lang_image'];
			$i++;
		}		
	}
	return $data;
}

//Function to get all timezones to show in the profile preferences
//May 24, 2016
//Arshad
function get_all_timezones()
{
	$i = 0;
	$data = array();
	$qry = "SELECT *  
			 FROM entrp_timezones
			 WHERE timezone_status = 1 
			 ";
   $res = getData($qry);
	$count_res = mysqli_num_rows($res);
	if($count_res > 0)
	{
		while($row = mysqli_fetch_array($res))
		{
			$data[$i]['id']		=	$row['timezone_id'];
			$data[$i]['text']		=	$row['timezone_name'];
			$i++;
		}		
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



?>