<?php 

$db = new mysqli('localhost','root','','renthouse', 8080, null);

if($db->connect_error){
	echo "Error connecting database";
}

 ?>