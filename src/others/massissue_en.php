<?php

require_once ('../api/Query2.php'); 

//Step 1: Feed client ids from client_info to the script
//Step 2: Fetch data from client_info wrt clientid
//Step 3: Check whether users have already been added to EN
//Step 4: Add users to EN entrp_login
//Step 5: Create user profile in client_profile
//Step 6: Add Connect plan
//Step 7: Add entrp_credit_core
//Step 8: Send welcome email


//SELECT coname, COUNT( * ) c FROM client_info GROUP BY coname HAVING c > 1 ORDER BY c DESC

//Declarations
$clientIDArray=array(10000,10001,10002,10003,10004,10005,10006,10007,10008);
$data=array();


if(!empty($clientIDArray))
{
	
	
	//To add users based on their clientid, uncomment next two lines
	//$clientIDString = implode(",", $clientIDArray);
	//$qry="SELECT clientid FROM client_info WHERE clientid IN(".$clientIDString.") AND status=1";
	//SELECT clientid FROM client_info WHERE clientid IN(10000,10001,2) (sample query)

	//To add users based on location, uncomment the next two lines
	$locationString=getLocations();	
	$qry="SELECT clientid FROM client_info WHERE location IN(".$locationString.") AND status=1 AND email!='' AND email!='n/a n/a' ";
	
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$clientIDs[]			=	$row['clientid']; //feeded
		}
		
		//print_r($clientIDs);	
		$qry2="SELECT vof_clientid FROM entrp_login";
		
		$res2=getData($qry2);
   	$count_res2=mysqli_num_rows($res2);
   	if($count_res2>0)
   	{
   		while($row2=mysqli_fetch_array($res2))
      	{
      		$clientENVOF[]	=	$row2['vof_clientid']; //fetched
			}
   	}
   	//print_r($clientENVOF);	
   	
   	if( !empty($clientIDs) && !empty($clientENVOF) )
   	{
   		$result=array_filter(array_diff($clientIDs,$clientENVOF)); //Step 3: Check whether users have already been added to EN
   			   		
   		foreach($result as $key)
			{
			 $addToEN[]= $key;
			}
			
			if(!empty($addToEN))
			{
				$addToENString = implode(",", $addToEN);
				//echo $addToENString;
				
				//Step 2 begins				
				$i=0;
				//equivalent to SELECT clientid, firstname,lastname,coname,address,postcode,city,state,country,email,website,location,dob FROM client_info
				$qry3="SELECT * FROM client_info WHERE clientid IN(".$addToENString.") AND status=1 ";
				$res3=getData($qry3);
   			$count_res3=mysqli_num_rows($res3);
				if($count_res3>0)
				{
					while($row3=mysqli_fetch_array($res3))
		      	{
		      		$data[$i]["clientid"] 		= $row3['clientid']; 
		      		$data[$i]["firstname"] 		= $row3['firstname']; 
		      		$data[$i]["lastname"] 		= $row3['lastname']; 
		      		$data[$i]["coname"] 			= strip_tags($row3['coname']); 
		      		$data[$i]["address"] 		= $row3['address']; 
		      		$data[$i]["postcode"] 		= $row3['postcode']; 
		      		$data[$i]["city"] 			= $row3['city']; 
		      		$data[$i]["state"] 			= $row3['state']; 
		      		$data[$i]["country"] 		= $row3['country']; 
		      		$data[$i]["email"] 			= $row3['email']; 
		      		$data[$i]["website"] 		= $row3['website']; 
		      		$data[$i]["location"] 		= $row3['location']; 
		      		$data[$i]["dob"] 				= $row3['dob'];
		      		
		      		$i++; 
					}	
						
				}
				
				$repeatedEmailIds=repeatedEmailIds();
				if(!empty($data))
				{
					echo '<table>';
					echo'
					<tr>
						<td>clientid</td>
						<td>Name</td>
						<td>coname</td>
						<td>email</td>
						<td>location</td>
					</tr>';
					for($i=0;$i<count($data);$i++)
					{
						if (in_array($data[$i]["email"], $repeatedEmailIds)) 
						{ 
							$color='red'; 
						} 
						else
						{
							$color='black';
						}
						echo'
						<tr>
							<td>'.$data[$i]["clientid"].'</td>
							<td>'.$data[$i]["firstname"].' '.$data[$i]["lastname"].'</td>
							<td>'.$data[$i]["coname"].'</td>
							<td ><font color="'.$color.'">'.$data[$i]["email"].'</font></td>
							<td>'.$data[$i]["location"].'</td>
						</tr>';
					}
					echo '</table>';
				}
				
			}
   	}
   	//style='color:red'
   	  					 	
   }
   
}
else
{
	echo 'No users to add';
}


function repeatedEmailIds()
{
	//SELECT email, COUNT( * ) c FROM client_info WHERE status=1 AND location IN(14,33,21) AND email!='' GROUP BY email HAVING c > 1 ORDER BY c DESC
	$locationString=getLocations();
	$qry="SELECT email, COUNT( * ) c FROM client_info WHERE status=1 AND location IN(".$locationString.") AND email!='' GROUP BY email HAVING c > 1 ORDER BY c DESC ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$repeatedEmailIds[]			=	$row['email']; //feeded
		}
	}
	return $repeatedEmailIds;

}


function getLocations()
{
	$locationString='';
	//SELECT id FROM location_info WHERE currency='PHP'
	$qry="SELECT id FROM location_info WHERE currency='PHP'";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$locations[]			=	$row['id']; //feeded
		}
		$locationString = implode(",", $locations);
	}
	return $locationString;

}


?>