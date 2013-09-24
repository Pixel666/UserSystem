<?php

/**
 *
 * @author Adam Rollinson
 *
 */

class Session {
	
	function __construct() {
		
		session_start();
		
	}
	
	function createSession($name, $data) {
		
		return $_SESSION[$name] = $data;
		
	}
	
	function useSession($name) {
		
		return $_SESSION[$name];
	}
	
	
}