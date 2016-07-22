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



if (!defined('TIMELINE_POST_PIC')) define('TIMELINE_POST_PIC', 'assets/img/timeline/');
if (!defined('TIMELINE_POST_PIC_UPL')) define('TIMELINE_POST_PIC_UPL', '../assets/img/timeline/');
 
if (!defined('BUSSOPP_POST_PIC')) define('BUSSOPP_POST_PIC', 'assets/img/businessopp/');
if (!defined('BUSSOPP_POST_PIC_UPL')) define('BUSSOPP_POST_PIC_UPL', '../assets/img/businessopp/');
 
if (!defined('JPEG')) define('JPEG', '.jpeg');
if (!defined('GIF')) define('GIF', '.gif');
if (!defined('PNG')) define('PNG','.png');

if (!defined('PROFILE_PIC')) define('PROFILE_PIC', 'assets/img/members/');
if (!defined('COMPANY_PIC')) define('COMPANY_PIC', 'assets/img/companies/');
if (!defined('EVENT_POSTER')) define('EVENT_POSTER', 'assets/img/events/');

if (!defined('PROFILE_PIC_UPL')) define('PROFILE_PIC_UPL', '../assets/img/members/');
if (!defined('COMPANY_PIC_UPL')) define('COMPANY_PIC_UPL', '../assets/img/companies/');
if (!defined('EVENT_POSTER_UPL')) define('EVENT_POSTER_UPL', '../assets/img/events/');




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