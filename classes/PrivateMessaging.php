<?php

/**
 *
 * @author Adam Rollinson
 *
 */

class PrivateMessaging {
	
	private $DB, $Session, $Users;
	
	function __construct($DB, $Session, $Users) {
		$this->DB = $DB;
		$this->Session = $Session;
		$this->Users = $Users;
	}
	
	
	function replyMessage($id, $message, $unread) {
		
		$id = mysqli_real_escape_string($this->DB, $id);
		
		$result = $this->DB->query("select * from private_messages WHERE messageid = '{$id}'");
		
		while($rows = $result->fetch_assoc()) {
		
		$usernameto = $rows['usernamefrom'];
		$messagetitle = "RE: ".$rows['messagetitle'];
			
		$prepare = $this->DB->prepare("INSERT INTO private_messages (usernameto, usernamefrom, messagetitle, messagebody, unread) VALUES (?, ?, ?, ?, ?)");
		$prepare->bind_param('ssssi', $usernameto, $this->Session->useSession("username"), $messagetitle, $message, $unread);
		$prepare->execute();
		
		return 1;
		
		}
		
		return 0;
	}
	
	function composeMessage($to, $title, $message, $unread) {
		
		if($this->Users->userExists($to) != 0) {
			
			$prepare = $this->DB->prepare("INSERT INTO private_messages (usernameto, usernamefrom, messagetitle, messagebody, unread) VALUES (?, ?, ?, ?, ?)");
			$prepare->bind_param('ssssi', $to, $this->Session->useSession("username"), $title, $message, $unread);
			$prepare->execute();
			
			return 1;
		} else {
			return 0;
		}
		
		
	} 
	
	function readMessage($id) {
		
		$id = mysqli_real_escape_string($this->DB, $id);
		$this->DB->query("UPDATE private_messages SET unread ='0' WHERE messageid = '{$id}'");
		
		
		echo $this->DB->error;
		
		$prepare = $this->DB->prepare("select messageid,usernameto,usernamefrom,messagetitle,messagebody from private_messages where usernameto = ? and messageid = ?");
		$prepare->bind_param('si', $this->Session->useSession("username"), $id);
		$prepare->execute();
		$prepare->store_result();
		$prepare->bind_result($messageid,$usernameto,$usernamefrom,$messagetitle,$messagebody);
		$prepare->fetch();
		
		
		echo <<<EOT
		
		<div class="well well-sm" style="margin-top: 15px;">
				
		<h2>Subject: {$messagetitle}</h2>

		<p><i>From: {$usernamefrom}</i></p>
		<p>Message: <b>{$messagebody}</b></p>
		
		<h2>Reply</h2>
		<form action="" method="POST">
		<textarea rows="10" cols="150" name="reply_message"></textarea> </br></br>
		<input type="hidden" name="reply_id" value="{$messageid}">
		<button class="btn btn-success" type="submit">Send Reply</button>
		</form>
		</div>

				
		
EOT;
		
	}
	
	function countUnread() {
		
		$prepare = $this->DB->prepare("select messageid from private_messages where usernameto = ? and unread = '1'");
		$prepare->bind_param('s', $this->Session->useSession("username"));
		$prepare->execute();
		$prepare->store_result();
		$prepare->bind_result($messageid);
		$prepare->fetch();
		
		return $prepare->num_rows;
	}
	
	function listMessages() {
		
		$prepare = $this->DB->prepare("select messageid,usernameto,usernamefrom,messagetitle,messagebody from private_messages where usernameto = ? and unread= '1' order by messageid DESC");
		$prepare->bind_param('s', $this->Session->useSession("username"));
		$prepare->execute();
		$prepare->store_result();
		$prepare->bind_result($messageid,$usernameto,$usernamefrom,$messagetitle,$messagebody);
		//$prepare->fetch();
		
		echo <<<EOT
		
			<table class="table table-bordered">
        <thead>
          <tr>
            <th>Message From</th>
            <th>Subject</th>
          </tr>
        </thead>
				
EOT;
		
		while($rows = $prepare->fetch()) {
			
			echo <<<EOT
			
        <tbody>
          <tr>
            <td>{$usernamefrom}</td>
            <td>{$messagetitle}</td>
            <td><a class="btn btn-danger" href="index.php?page=readmessage&messageid={$messageid}">Read</a></td>
          </tr>
EOT;
			
			
		}
		
		echo "</tbody></table>";
		
	}
}

?>