<?php

/**
 *
 * @author Adam Rollinson
 *
 */


include('classes/global.php');

//REPLY MESSAGE //


if(isset($_POST['reply_id']) && isset($_POST['reply_message'])) {
	
	if($PM->replyMessage($_POST['reply_id'], $_POST['reply_message'], '1')) {
		
	echo <<<EOT

				<div class="alert alert-success alert-dismissable" style="width: 300px; margin-right: auto; margin-left: auto;">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong>Success!</strong> Message Sent.
			</div>
		
EOT;
		
	} else {
		
		echo <<<EOT
		
		<div class="alert alert-danger alert-dismissable" style="width: 300px; margin-right: auto; margin-left: auto;">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong>Error!</strong> Cannot be sent.
			</div>
		
EOT;
		
	}
	
}

// END REPLY MESSAGE //

//COMPOSE MESSAGE//


if(isset($_POST['compose_title']) && isset($_POST['compose_message']) && isset($_POST['compose_to'])) {
	
	if($PM->composeMessage($_POST['compose_to'], $_POST['compose_title'], $_POST['compose_message'], '1') != 0) {
		
		echo <<<EOT

				<div class="alert alert-success alert-dismissable" style="width: 300px; margin-right: auto; margin-left: auto;">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong>Success!</strong> Message Sent.
			</div>
		
EOT;
		
	} else {
		
		echo <<<EOT
		
		<div class="alert alert-danger alert-dismissable" style="width: 300px; margin-right: auto; margin-left: auto;">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong>Error!</strong> Username you have selected doesn't exist.
			</div>
		
EOT;
		
	}
	
}


//END COMPOSE MESSAGE//

// LOGIN //

if(isset($_POST['login_username']) && isset($_POST['login_password'])) {
	if($Users->Login($_POST['login_username'], $_POST['login_password']) == 1) {
		$Users->redirect("index.php?page=home");
	} else {
		echo <<<EOT
			<div class="alert alert-danger alert-dismissable" style="width: 300px; margin-right: auto; margin-left: auto;">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong>Error!</strong> Invalid Username / Password
			</div>
EOT;
	}
}

// END LOGIN //

// REGISTER //

if(isset($_POST['register_username']) && isset($_POST['register_password'])) {
	if($Users->Register($_POST['register_username'], $_POST['register_password']) == 1) {
		$Users->Login($_POST['register_username'], $_POST['register_password']);
	} else {
		echo <<<EOT
			<div class="alert alert-danger alert-dismissable" style="width: 300px; margin-right: auto; margin-left: auto;">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong>Error!</strong> Username already exists.
			</div>
EOT;
	}
}

// END REGISTER //

// PAGE LOADER //

$path = 'pages/';
$default_page = 'home';
$default_404_page = '404';
if(empty($_GET['page'])) {
	$page = $default_page;
}else{
	$page = (preg_match('/[^a-zA-Z0-9\ _-]/', $_GET['page']) || !file_exists($path . $_GET['page'] . '.php') ? $default_404_page : $_GET['page']);
}
require_once $path . $page . '.php';

// END PAGE LOADER //

