<?PHP
	header('Content-type: application/json');
	$connection = mysql_connect("database-new.cse.tamu.edu", "ctrinh", "quicktalk");
	mysql_select_db("ctrinh-db1");
	
	function write_public_chat($user_name,$display_name,$room,$text) {
	
		GLOBAL $connection;
		$room = mysql_real_escape_string($room);
		$user_name = mysql_real_escape_string($user_name);
		$text = mysql_real_escape_string($text);
		$display_name = mysql_real_escape_string($display_name);
		$save_msg_qry = "INSERT INTO ".$room." Values (".$user_name.",".$display_name.",".date("h:i:s a").",".$text.");";
		
		if(!mysql_query($save_msg_qry,$connection))
        {
            $data=array("message"=>"Error saving message \nquery was\n $save_msg_qry", "error" => true);
            return json_encode($data);
        }
		$data = array("message" => "Successful", "error" => false);
		return json_encode($data);
		
	}
	
	function get_public_chat($user_name,$room){
	 
		$room = mysql_real_escape_string($room);
		$name = mysql_real_escape_string($name);
	 	GLOBAL $connection;

		$create_table_qry = "Create Table IF NOT EXISTS ".$room." (".
                "msg_id INT NOT NULL AUTO_INCREMENT ,".
                "user_name VARCHAR(128),".
                "display_name VARCHAR(128),".
				"msg_time TIME,".
                "message VARCHAR(1024),".
                "PRIMARY KEY ( msg_id ) ".
                ");";
			
		if(!mysql_query($create_table_qry,$connection))
        {
            $data=array("message"=>"Error creating the table \nquery was\n $qry", "error" => true);
            return json_encode($data);
        }
		
		/*
			Query to get messages
		*/
		
		$get_msg_qry = mysql_query("SELECT * FROM ".$room,$connection);
		if(!$get_msg_qry)
        {
            $data=array("message"=>"Error selecting from the table \nquery was\n $get_msg_qry", "error" => true);
            return json_encode($data);
        }
		$data = array();
		while ($message = mysql_fetch_assoc($get_msg_qry)) {
			$data[] = $message;
		}
		
		return json_encode($data);
	}
	
	if ($_POST['mode'] == 'public') {
		echo write_public_chat($_POST['user_name'],$_POST['display_name'],$_POST['room'],$_POST['text']);
	}
	if ($_POST['mode'] == 'check') {
		echo get_public_chat($_POST['user_name'],$_POST['display_name'],$_POST['room']);

	}
?>