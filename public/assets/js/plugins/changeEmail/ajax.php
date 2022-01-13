<?php 	

$host         = localhost;
$username     = admin_laravel;
$password     = oSCZwHxIm;
$dbname       = admin_laravel;

include_once(__DIR__ .'/../../../../config.php');

$id = htmlspecialchars_decode(trim($_GET['id']));
$email = htmlspecialchars_decode(trim($_GET['email']));

if (!empty( $id ) && !empty( $email ) ) {
	/* Create connection */
	$conn = new mysqli($host, $username, $password, $dbname);

	/* Check connection  */
	if ($conn->connect_error) {
		 die("Connection to database failed: " . $conn->connect_error);
	}

	$sql = empty( $element ) ? "UPDATE `urls` SET `email` = '". $email ."' WHERE `id` = '". $id ."';" : '' ;
	$result = $conn->query($sql);

	$conn->close();
}

?>