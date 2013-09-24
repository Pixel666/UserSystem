<?php

if($Users->Logout() != 0) {
	$Users->redirect("index.php?page=login");
}