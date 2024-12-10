<?php

$owner_id = '';
$full_name = '';
$email = '';
$password = '';
$phone_no = '';
$address = '';
$id_type = '';
$id_photo = '';

$errors = array();

$db = new mysqli('localhost', 'root', '', 'renthouse', 8080, null);

if ($db->connect_error) {
	echo "Error connecting database";
}

if (isset($_POST['owner_register'])) {
	owner_register();
}

if (isset($_POST['owner_login'])) {
	owner_login();
}

function owner_register()
{
	if (isset($_FILES['id_photo'])) {
		$id_photo = 'owner-photo/' . $_FILES['id_photo']['name'];

		if (!empty($_FILES['id_photo'])) {
			$path = "owner-photo/";
			$path = $path . basename($_FILES['id_photo']['name']);
			if (move_uploaded_file($_FILES['id_photo']['tmp_name'], $path)) {
				// echo "The file " . basename($_FILES['id_photo']['name']) . " has been uploaded";
			} else {
				echo "There was an error uploading the file, please try again!";
			}
		}
	}

	global $owner_id, $full_name, $email, $password, $phone_no, $address, $id_type, $id_photo, $errors, $db;
	// $owner_id = validate($_POST['owner_id']);
	$full_name = validate($_POST['full_name']);
	$email = validate($_POST['email']);
	$password = validate($_POST['password']);
	$phone_no = validate($_POST['phone_no']);
	$address = validate($_POST['address']);
	$id_type = validate($_POST['id_type']);
	// $id_photo = $_POST['id_photo'];
	$password = md5($password); // Encrypt password

	$sql = "INSERT INTO owner(full_name, email, password, phone_no, address, id_type, id_photo, approved) 
            VALUES('$full_name', '$email', '$password', '$phone_no', '$address', '$id_type', '$path', 0)";

	if ($db->query($sql) === TRUE) {
		// Redirect with an alert that the user needs admin approval
		// showAlert("Registration successful. You need to wait for admin approval before you can log in.");
		header("Location: owner-login.php?status=registered&message=approval_pending");
		exit;

	}
}

function owner_login()
{
	global $email, $db;
	$email = validate($_POST['email']);
	$password = validate($_POST['password']);
	$password = md5($password);

	$sql = "SELECT * FROM owner WHERE email='$email' AND password='$password' LIMIT 1";
	$result = $db->query($sql);

	if ($result->num_rows == 1) {
		$data = $result->fetch_assoc();

		if ($data['approved'] == 1) {
			// User is approved
			session_start();
			$_SESSION['email'] = $email;
			header('location:owner/owner-index.php');
		} else {
			// User is not approved
			showAlert("Your registration is still pending approval by the admin. Please wait.");
		}
	} else {
		// Incorrect credentials
		showAlert("Incorrect Email/Password or not registered. Click here to <a href='owner-register.php' style='color: lightblue;'><b>Register</b></a>.");
	}
}

function validate($data)
{
	$data = trim($data);
	$data = stripcslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function showAlert($message)
{
	echo "
    <style>
        .alert {
            padding: 20px;
            background-color: #DC143C;
            color: white;
        }
        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        .closebtn:hover {
            color: black;
        }
    </style>
    <div class='container'>
        <div class='alert'>
            <span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span> 
            <strong>$message</strong>
        </div>
    </div>";
}
?>