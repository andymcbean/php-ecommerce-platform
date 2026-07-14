<?php
include 'connect.php';
//config.php

//Include Google Client Library for PHP autoload file
require_once '../../../vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('262258960221-elcn17mo703efo68kdeh35v5oji99ask.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('f165JZezkZEpCvApgvR-msW3');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('https://dqdev.co.uk/users/');

//
$google_client->addScope('email');

$google_client->addScope('profile');


$login_button = '';

//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if(isset($_GET["code"]))
{
 //It will Attempt to exchange a code for an valid authentication token.
 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

 //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
 if(!isset($token['error']))
 {
  //Set the access token used for requests
  $google_client->setAccessToken($token['access_token']);

  //Store "access_token" value in $_SESSION variable for future use.
  $_SESSION['g_access_token'] = $token['access_token'];

  //Create Object of Google Service OAuth 2 class
  $google_service = new Google_Service_Oauth2($google_client);

  //Get user profile data from google
  $data = $google_service->userinfo->get();

  //Below you can find Get profile data and store into $_SESSION variable
  if(!empty($data['given_name']))
  {
   $_SESSION['g_user_first_name'] = $data['given_name'];
  }

  if(!empty($data['family_name']))
  {
   $_SESSION['g_user_last_name'] = $data['family_name'];
  }

  if(!empty($data['email']))
  {
   $_SESSION['g_user_email_address'] = $data['email'];
  }

  if(!empty($data['gender']))
  {
   $_SESSION['g_user_gender'] = $data['gender'];
  }

  if(!empty($data['picture']))
  {
   $_SESSION['g_user_image'] = $data['picture'];
  }
 }
}

//This is for check user has login into system by using Google account, if User not login into system then it will execute if block of code and make code for display Login link for Login using Google account.
if(!isset($_SESSION['g_access_token']))
{
 //Create a URL to obtain user authorization
 $login_button = '<a href="'.$google_client->createAuthUrl().'"><img src="../images/g-login.png" class="img-fluid"/></a>';
}

$facebook = new \Facebook\Facebook([
    'app_id'      => '209765480590396',
    'app_secret'     => '1b16065968a2b5bb96c0ebd1c0315a63',
    'default_graph_version'  => 'v2.10'
  ]);
  $facebook_output = '';
  
  $facebook_helper = $facebook->getRedirectLoginHelper();
  
  if(isset($_GET['code']))
  {
   if(isset($_SESSION['access_token']))
   {
    $access_token = $_SESSION['access_token'];
   }
   else
    {
      $access_token = $facebook_helper->getAccessToken();
  
      $_SESSION['access_token'] = $access_token;
  
      $facebook->setDefaultAccessToken($_SESSION['access_token']);
    }
  
   $_SESSION['user_id'] = '';
   $_SESSION['user_name'] = '';
   $_SESSION['user_email_address'] = '';
   $_SESSION['user_image'] = '';
  
   $graph_response = $facebook->get("/me?fields=name,email", $access_token);
  
   $facebook_user_info = $graph_response->getGraphUser();
  
   if(!empty($facebook_user_info['id']))
    {
      $_SESSION['user_image'] = 'https://graph.facebook.com/'.$facebook_user_info['id'].'/picture';
    }
  
   if(!empty($facebook_user_info['name']))
    {
      $_SESSION['user_name'] = $facebook_user_info['name'];
    }
  
   if(!empty($facebook_user_info['email']))
    {
      $_SESSION['user_email_address'] = $facebook_user_info['email'];
    }

    $sql = "SELECT
                email
            FROM
                users
            WHERE
                email = '".$_SESSION['user_email_address']."'
            ";
    $statement = $db->prepare($sql);
    $statement ->execute();
    $result = $statement ->fetchAll();
    $total_row = $statement->rowCount();
    if($total_row < 1)
      {
        $date         = date("Y-m-d H:i:s");
        $ip           = $_SERVER['REMOTE_ADDR'];
        $user_level   = 'user';
        $password = "";
        $email = $_SESSION['user_email_address'];
        $name = $_SESSION['user_name'];
        $sql = "INSERT INTO 
                    users (name, email, password, reg_date, ip, user_level)
                VALUES
                    (:name, :email, :password, :reg_date, :ip, :user_level)
                ";

        $statement = $db->prepare($sql);
        
        $statement->bindParam(':name'	          ,	$name       , PDO::PARAM_STR);
        $statement->bindParam(':email'	        ,	$email      , PDO::PARAM_STR);
        $statement->bindParam(':password'	      ,	$password   , PDO::PARAM_STR);
        $statement->bindParam(':reg_date'      	,	$date       , PDO::PARAM_STR);
        $statement->bindParam(':ip'      	      ,	$ip         , PDO::PARAM_STR);
        $statement->bindParam(':user_level'     ,	$user_level , PDO::PARAM_STR);
        try
            {
                $statement->execute();
                //send_welcome($name, $email);
            }

        catch(PDOException $e)
            {
                echo $e;
                $failed = "<div class='container alert alert-danger'>Registration failed. Please try again.</div>";
            }
      }
   
  }
  else
    {
    // Get login url
        $facebook_permissions = ['email']; // Optional permissions
  
        $facebook_login_url = $facebook_helper->getLoginUrl('https://dqdev.co.uk/users/', $facebook_permissions);
        
        // Render Facebook login button
        $facebook_login_url = '<div align="center"><a href="'.$facebook_login_url.'"><img src="../images/fb-login.png" class="img-fluid"></a></div>';
    }
?>