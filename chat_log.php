<?PHP
	header('Content-type: application/json');
	$connection = mysql_connect("database-new.cse.tamu.edu", "ctrinh", "quicktalk");
	mysql_select_db("ctrinh-db1");
	
	function write_public_chat($user_name,$display_name,$room,$text) {
	
		GLOBAL $connection;
		$result = array();
		$room = mysql_real_escape_string($room);
		$user_name = mysql_real_escape_string($user_name);
		$text = mysql_real_escape_string($text);
		$display_name = mysql_real_escape_string($display_name);
		$save_msg_qry = "INSERT INTO ".$room." (user_name,display_name,msg_time,message) Values ('".$user_name."','".$display_name."','".date("h:m:s")."','".$text."');";
		
		if(!mysql_query($save_msg_qry,$connection))
        {
            $result['response']=array("message"=>"Error saving message \nquery was\n $save_msg_qry", "error" => true);
            return json_encode($result);
        }
		$result['response'] = array("message" => "Successful", "error" => false);
		return json_encode($result);
		
	}
	
	function get_public_chat($user_name,$display_name,$room){
	 
		$room = mysql_real_escape_string($room);
		$name = mysql_real_escape_string($name);
		$display_name = mysql_real_escape_string($display_name);
		$result = array();
	 	GLOBAL $connection;

		$create_table_qry = "Create Table IF NOT EXISTS ".$room." (".
                "msg_id INT NOT NULL AUTO_INCREMENT ,".
                "user_name VARCHAR(128) NOT NULL,".
                "display_name VARCHAR(128),".
				"msg_time TIME,".
                "message VARCHAR(1024),".
                "PRIMARY KEY ( msg_id ) ".
                ");";
			
		if(!mysql_query($create_table_qry,$connection))
        {
            $result['response']=array("message"=>"Error creating the table \nquery was\n $qry", "error" => true);
            return json_encode($result);
        }
		
		/*
			Query to get messages
		*/
		
		$get_msg_qry = mysql_query("SELECT * FROM ".$room,$connection);
		if(!$get_msg_qry)
        {
            $result['response']=array("message"=>"Error selecting from the table \nquery was\n $get_msg_qry", "error" => true);
            return json_encode($result);
        }
		$result['response'] = array("message"=>"Successfully getting message", "error" => false);
		while ($message = mysql_fetch_assoc($get_msg_qry)) {
			$result['data'][] = $message;
		}
		return json_encode($result);
	}
	
	if ($_POST['mode'] == 'write_msg') {
		echo write_public_chat($_POST['user_name'],$_POST['display_name'],$_POST['room'],$_POST['text']);
	}
	if ($_POST['mode'] == 'get_msg') {
		echo get_public_chat($_POST['user_name'],$_POST['display_name'],$_POST['room']);

	}
?>