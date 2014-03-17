<?PHP
	header('Content-type: application/json');
	
	function write_public_chat($name,$room,$text) {
		$filename = $room.'.html';	
		$fp = fopen($filename,'a');
		$msg_style = floor( (ord($name{0}) + ord($name{1})) /10)
		fwrite($fp,"<div class='msg ".$msg_style."'>".date("h:i:s a")."<b>".$name."</b>: ".stripslashes(htmlspecialchars($text))."</br></div>");
		fclose($fp);
	}
	
	function get_public_chat($name,$room) {
		$filename = $room.'.html';
		if (!file_exists($filename)) {
			$fp = fopen($filename,'a+');
			fwrite($fp,"<div class='welcome'><i> Welcome to Casino </i></br><div");
			fclose($fp);
		}
		$data = array("file",$filename);
		echo json_encode($data);
		
	}
	
	if ($_POST['mode'] == 'public') {
		echo write_public_chat($_POST['name'],$_POST['room'],$_POST['text']);
	}
	if ($_POST['mode'] == 'check') {
		echo get_public_chat($_POST['name'],$_POST['room']);
	}
?>