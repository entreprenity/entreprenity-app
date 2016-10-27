<?php



//Function for universal search
//October 25,2016
function searchResultsFor()
{

	//Search against 
	//Client Profiles - first name, last name, email, clientid, username, location
	//Company Profiles- company name, company username, location
	//Upcoming Events- Event Name, location, category
	//Timeline Posts- posts
	//BO Posts- posts, tags
	$data= array();
	$query = validate_input($_POST['query']);
	
	$data["users"]			=	searchUserInfo($query);
	$data["companies"]	=	searchCompanyInfo($query);
	$data["events"]		=	searchEvents($query);
	$data["bussOpp"]		=	searchBusinessOpportunities($query);
	return $data;
}

//Function to do a search on business opportunities
//October 27,2016
function searchBusinessOpportunities($query)
{
	//SELECT entrp_user_timeline.*
	//FROM entrp_user_timeline
	//LEFT JOIN entrp_user_timeline_businessopp_tags ON entrp_user_timeline.post_id=entrp_user_timeline_businessopp_tags.postid
	//WHERE status=1 AND entrp_user_timeline.business_opp=1 

	$data= array();
	$statusQuery='status=1 AND entrp_user_timeline.business_opp=1';
	$qry="SELECT entrp_user_timeline.*
			FROM entrp_user_timeline
			LEFT JOIN entrp_user_timeline_businessopp_tags ON entrp_user_timeline.post_id=entrp_user_timeline_businessopp_tags.postid
			WHERE (".$statusQuery.") 
			AND (entrp_user_timeline_businessopp_tags.business_tags like '%$query%' OR entrp_user_timeline.content like '%$query%' ) ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0;
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']			=	$row['post_id'];
      	$data[$i]['content']	=	$row['content'];
      	
      	$i++;
      }
   }
   return $data;
}

//Function to do a search on events
//October 27,2016
function searchEvents($query)
{
	//SELECT entrp_events.*,entrp_event_categories.category_name,location_info.location_desc
	//FROM entrp_events
	//LEFT JOIN entrp_event_categories ON entrp_event_categories.id=entrp_events.category
	//LEFT JOIN entrp_login ON entrp_login.clientid=entrp_events.clientid
	//LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
	//LEFT JOIN location_info ON location_info.id=client_profile.client_location
	//WHERE entrp_events.status IN (1,2) AND location_info.location_desc like '%global%'

	$data= array();
	$statusQuery='entrp_events.status IN (1,2)';
	$qry="SELECT entrp_events.*,entrp_event_categories.category_name,location_info.location_desc  
			FROM entrp_events 
			LEFT JOIN entrp_event_categories ON entrp_event_categories.id=entrp_events.category
			LEFT JOIN entrp_login ON entrp_login.clientid=entrp_events.clientid
			LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
			LEFT JOIN location_info ON location_info.id=client_profile.client_location
			WHERE (".$statusQuery.") 
			AND (location_info.location_desc like '%$query%' OR entrp_events.eventName like '%$query%' 
			     OR entrp_events.city like '%$query%' OR entrp_events.eventTagId like '%$query%' OR entrp_event_categories.category_name like '%$query%' ) ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0;
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']			=	$row['id'];
      	$data[$i]['eventName']	=	$row['eventName'];
      	$data[$i]['eventTagId']	=	$row['eventTagId'];
      	$data[$i]['eventDate']	=	$row['event_date'];
      	$data[$i]['location']	=	$row['location_desc'];
      	
      	$i++;
      }
   }
   return $data;
}


//Function to do a basic search on user information
//October 26,2016
function searchUserInfo($query)
{	
	//SELECT entrp_login.username, entrp_login.firstname,entrp_login.lastname 
	//FROM entrp_login 
	//WHERE entrp_login.firstname like '%dominic%' OR entrp_login.lastname like '%dominic%' OR CONCAT(entrp_login.firstname, ' ',entrp_login.lastname) like '%dominic%' 
	//AND entrp_login.status=1
	$data= array();
	$statusQuery='entrp_login.status=1';
	$qry="SELECT entrp_login.clientid,entrp_login.username, entrp_login.firstname,entrp_login.lastname 
			FROM entrp_login 
			LEFT JOIN entrp_user_skills ON entrp_user_skills.user_id=entrp_login.clientid 
			LEFT JOIN entrp_user_interests ON entrp_user_interests.user_id=entrp_login.clientid 
			WHERE (".$statusQuery.") 
			AND (entrp_login.firstname like '%$query%' OR entrp_login.lastname like '%$query%' OR CONCAT(entrp_login.firstname, ' ',entrp_login.lastname) like '%$query%'
			     OR entrp_user_interests.interests like '%$query%' OR entrp_user_skills.skills like '%$query%' ) ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0;
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['clientid']	=	$row['clientid'];
      	$data[$i]['username']	=	$row['username'];
      	$data[$i]['firstname']	=	$row['firstname'];
      	$data[$i]['lastname']	=	$row['lastname'];
      	
      	$i++;
      }
   }
   return $data;
}


//Function to fetch basic company info
//October 27, 2016
function searchCompanyInfo($query)
{
	//SELECT company_profiles.* 
	//FROM company_profiles 
	//LEFT JOIN entrp_company_categories ON entrp_company_categories.companyid=company_profiles.id
	//WHERE company_profiles.company_username like '%dominic%' OR company_profiles.company_name like '%dominic%' OR entrp_company_categories.category like '%first%'
	$data= array();
	$qry="SELECT company_profiles.*,location_info.location_desc  
			FROM company_profiles 
			LEFT JOIN entrp_company_categories ON entrp_company_categories.companyid=company_profiles.id 
			LEFT JOIN location_info ON location_info.id=company_profiles.client_location
			WHERE company_profiles.company_username like '%$query%' OR company_profiles.company_name like '%$query%' 
			      OR entrp_company_categories.category like '%$query%' OR location_info.location_desc like '%$query%' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0;
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['compId']	=	$row['id'];
      	$data[$i]['compUName']	=	$row['company_username'];
      	$data[$i]['compName']	=	$row['company_name'];
      	$data[$i]['city']			=	$row['city'];
      	$data[$i]['location']	=	$row['location_desc'];
      	
      	$i++;
      }
   }
   return $data;
}

?>