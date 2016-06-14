<?php


//Member
$member_default_cover			='assets/img/members/member-default.jpg';
$member_default_avatar			='assets/img/members/member-default.jpg';
$member_default					='assets/img/members/member-default.jpg';


//Company
$company_default_cover			='assets/img/companies/company-default.jpg';
$company_default_avatar			='assets/img/companies/company-default.jpg';


//Events
$events_default					='assets/img/events/events-default.jpg';
$event_default_poster			='assets/img/events/events-default.jpg';


$myStaticVars = [
						'member_default_cover'  	=> $member_default_cover,
                	'member_default_avatar' 	=> $member_default_avatar,
                	'events_default'				=> $events_default,
                	'member_default'				=> $member_default,
                	'company_default_cover'		=> $company_default_cover,
                	'company_default_avatar'	=>	$company_default_avatar,
                	'event_default_poster'		=>	$event_default_poster
					 ];





/*
 	//the defaults starts
global $myStaticVars;
extract($myStaticVars);  // make static vars local
$member_default_avatar 		= $member_default_avatar;
$member_default_cover		= $member_default_cover;
$member_default				= $member_default;
$company_default_cover		= $company_default_cover;
$company_default_avatar		= $company_default_avatar;
$events_default				= $events_default;
$event_default_poster		= $event_default_poster;
//the defaults ends
*/
?>