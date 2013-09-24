<?php 

/**
 * 
 * @author Adam Rollinson
 *
 */

class Users {
	
	private $DB, $Session;
	
	function __construct($DB, $Session) {
		
		$this->DB = $DB;
		$this->Session = $Session;
		
	}
	
	function getUsername() {
		
		return $this->Session->useSession("username");
		
	}
	
	function redirect($page) {
		
		header("Location: {$page}");
		exit;
		
	}
	
	function checkLogin() {
		
		if($this->Session->useSession("logged_in") == TRUE) {
			return 1;
		} else {
			return 0;
		}
		
	}
	
	function Logout() {
		
		if(session_destroy()) {
			return 1;
		} else {
			return 0;
		}
	}
	
	function Login($user, $pass) {
		
		if($this->validateLogin($user, $pass) == 1) {
			
			$this->Session->createSession("username", $user);
			$this->Session->createSession("logged_in", TRUE);
			
			return 1;
			
		} else {
			
			return 0;
			
		}
		
	}
	
	function validateLogin($user, $pass) {
		
		$pass = $this->MD52($pass);
		
		$prepare = $this->DB->prepare("select username from users where username = ? and password = ?");
		$prepare->bind_param('ss', $user, $pass);
		$prepare->execute();
		$prepare->store_result();
		$prepare->bind_result($username);
		$prepare->fetch();
		
		if($prepare->num_rows == 1) {
			
			return 1;
			
		} else {
			
			
			return 0;
			
		}
		
		
	}
	
	function Register($user, $pass) {
		
		if($this->userExists($user) != 0) {
			
			$pass = $this->MD52($pass);	
			
			$prepare = $this->DB->prepare("INSERT INTO users (Username, Password) VALUES (?, ?)");
			$prepare->bind_param('ss', $user, $pass);
			$prepare->execute();
			
			return 1;
			
		} else {
			
			return 0;
			
		}
			
		
		
	}

	
	public function userExists($user) {
		
		$prepare = $this->DB->prepare("SELECT `Username` FROM `users` WHERE `Username` = ?");
		$prepare->bind_param('s', $user);
		$prepare->execute();
		
		if($prepare->num_rows != 1) {
			
			return 1;
			
		} else {
			
			return 0;
			
		}
		
		
		
	}
	
	function MD52($password) {
		
		return md5($password . "^&%07021988^&%M4TTH3W^&%R0LLINS0N^&%");
		
	}
	
	
}