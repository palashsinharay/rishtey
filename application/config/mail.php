<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//set mail configuration options
$config['mailconfigData'] = array(
                                    'protocol'  => 'smtp',
                                    'smtp_host' => 'ssl://smtp.gmail.com',
                                    'smtp_port'  => '465',
                                    'smtp_timeout'  => '10',
                                    'smtp_user'  => 'bodhisatwa78@gmail.com',
                                    'smtp_pass'  => '#abcd123',
                                    'charset'  => 'utf-8',
                                    'newline'  => "\r\n",
                                    'mailtype'  => 'html',	// or text
                                    'validation'  => TRUE,	// bool whether to validate email or not 
				   );
/*
set sender info, subject, message for sending welcome mail for first time users
*/
$config['fromName'] = 'Rishtey Konnekt';
//$config['fromMail'] = 'anushua@riskhteyconnect.com'; 
$config['welcomeMailSubject'] = 'Welcome to Rishtey Konnekt';
//$config['welcomeMailMessage'] = 'Welcome text...';

/*
set admin mail for sending abuse info and also the mail subject
*/

//$config['adminMail'] = 'anushua@riskhteyconnect.com';
$config['adminMail'] = 'palash.sinharay@indusnet.co.in';

$config['abuseSubject'] = 'Abuse report';
$config['NewCandidateSubject'] = 'New candidate created';

/*
set sender info, subject and message for sending activation mail for first time users
*/
$config['fromNameForAccountReady'] = 'Anushua Roy from Rishtey Konnekt';
//$config['fromMailForAccountReady'] = 'anushua@riskhteyconnect.com';
$config['subject'] = 'Your account is ready.';
//$config['message'] = 'Dear $fname $lname,<br/> Your network is ready, please click on the following link to login:<br/><a href="$baseUrlfacebooker">RishteyConnect</a>';


/*
set sender info, new candidate mail subject and message
*/
$config['fromNameForNewCandidate'] = 'Rishtey Konnekt';
//$config['fromMailForNewCandidate'] = 'anushua@riskhteyconnect.com'; 
$config['candidateMailSubject'] = 'Your candidate has been created';
//$config['candidateMailMessage'] = 'To create a profile, please visit the following link:<br/> <a href="$baseUrlsendmessage/index/$otherfrFbUserId">RishteyConnect</a>';

/*
set initiator mail subject and message
*/
$config['initiatorMailSubject'] = 'Initiator Mail';
$config['initiatorMailMessage'] = 'To create a profile, please visit the following link:<br/> <a href="$baseUrlsendmessage/index/$otherfrFbUserId">RishteyConnect</a>';

/*
set recommendation mail subject and message
*/
$config['recommendationMailSubject'] = 'Recommendation Mail';
$config['recommendationMailMessage'] = 'To recommend a candidate, please visit the following link:<br/><a href="$baseUrlrecommendation/index/$otherfrFbUserId/$fbUserId">RishteyConnect</a>';

/*
set request for change of guardian mail subject and message
*/
$config['changeofguardianMailSubject'] = 'Request for Change of Guardian';
$config['changeofguardianMailMessage'] = 'Message goes here...';

/*
set request for profile creation mail subject and message
*/
$config['profilecreationMailSubject'] = 'Request for Profile Creation';
$config['profilecreationMailMessage'] = 'To create a profile, please visit the following link:<br/> <a href="$baseUrlsendmessage/index/$otherfrFbUserId">RishteyConnect</a>';
