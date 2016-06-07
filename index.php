<?php

	define("DB_HOST", "localhost");
	define("DB_USER", "db_username");
	define("DB_PASS", "db_password");
	define("DB_NAME", "db_databasename");

	$exist_joomla = 0; // 1 - User exist in Joomla OR 0 - User not exist 
	
	// Following is need for registeration and signing in. 

	$username 	= "USERNAME";
	$name 		= "FULLNAME";
	$email 		= "EMAIL";
	$type       = '0';
	$mobile     = 'MOBILE_NO';
	$password   = 'defaultpass'; // You can give any password.
	$block      = 0;
	$sendEmail  = 1;
	$activation = 1;


	//Connect to Joomla

	define('_JEXEC', 1);
	define('JPATH_BASE', '../community/');
	define('DS', DIRECTORY_SEPARATOR);

	require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
	require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');
	require_once (JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'factory.php' );
	require_once(JPATH_BASE . DS . 'components' . DS . 'com_users' . DS . 'models' . DS . 'registration.php');

	$app = JFactory::getApplication('site');
	$app->initialise();
	$model = new UsersModelRegistration();
	jimport('joomla.mail.helper');
	jimport('joomla.user.helper');
	$language = JFactory::getLanguage();
	$language->load('com_users', JPATH_SITE);

		if ($exist_joomla == 0 ) {
	
			//Start Register into Joomla.
			$data = array(	'username'   => $username,
						    'name'       => $name,
						    'email1'     => $email,
						    'password1'  => $password, // First password field
						    'password2'  => $password, // Confirm password field
						    'block'  	 => $block,
						    'sendEmail'  => $sendEmail,
						    'activation' => $activation,
						    'mobile'     => $mobile,
						    'groups'     => array("2", "10")); // groups is user group. 

			$response   = $model->register($data);

			// After register success, update block = 0, in Joomla DB. to bypass email verification.

			$connectionj = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			$sql_update = 'update jomh_users set block=0 where username="'.$username.'"';
		    $result=mysqli_query($connectionj, $sql_update);
			
			// End Register into Joomla
		}	
	
		//**********  Logging in

	    $mainframe =& JFactory::getApplication('site');
	
		$credentials = array();
		$credentials['username'] = $username;
		$credentials['password'] = $password;

		//perform the login action
		$error = $mainframe->login($credentials);
		$user = JFactory::getUser();

		if ($error == 1) { echo "Success Login."; } 
		else { echo "Successful Registered. But Login Fail."; }

		//*********  End Login into Joomla. 

	//end connect to joomla

?>