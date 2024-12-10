<?php

$tenant_id = '';
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


if (isset($_POST['tenant_register'])) {
	tenant_register();
}

if (isset($_POST['tenant_login'])) {
	tenant_login();
}

if (isset($_POST['tenant_update'])) {
	tenant_update();
}

function tenant_register()
{
	if (isset($_FILES['id_photo'])) {
		$id_photo = 'tenant-photo/' . $_FILES['id_photo']['name'];

		// echo $_FILES['image']['name'].'<br>';

		if (!empty($_FILES['id_photo'])) {
			$path = "tenant-photo/";
			$path = $path . basename($_FILES['id_photo']['name']);
			if (move_uploaded_file($_FILES['id_photo']['tmp_name'], $path)) {
				// echo"The file ". basename($_FILES['id_photo']['name']). " has been uploaded";
			} else {
				echo "There was an error uploading the file, please try again!";
			}
		}

	}
	global $tenant_id, $full_name, $email, $password, $phone_no, $address, $id_type, $id_photo, $errors, $db;
	$tenant_id = validate($_POST['tenant_id']);
	$full_name = validate($_POST['full_name']);
	$email = validate($_POST['email']);
	$password = validate($_POST['password']);
	$phone_no = validate($_POST['phone_no']);
	$address = validate($_POST['address']);
	$id_type = validate($_POST['id_type']);
	$id_photo = $_POST['id_photo'];
	$password = md5($password); // Encrypt password
	$sql = "INSERT INTO tenant(tenant_id,full_name,email,password,phone_no,address,id_type,id_photo) VALUES('$tenant_id','$full_name','$email','$password','$phone_no','$address','$id_type','$path')";
	if ($db->query($sql) === TRUE) {
		header("Location: tenant-login.php?status=registered&message=approval_pending");
		exit;
	}
}



function tenant_login()
{
	global $email, $db;
	$email = validate($_POST['email']);
	$password = validate($_POST['password']);

	$password = md5($password); 

	$sql = "SELECT * FROM tenant WHERE email='$email' AND password='$password' LIMIT 1";
	$result = $db->query($sql);

	if ($result->num_rows == 1) {
		$data = $result->fetch_assoc();

		if ($data['approved'] == 1) {
			// User is approved
			session_start();
			$_SESSION['email'] = $email;
			header('location:index.php');
		} else {
			// User is not approved
			showAlert("Your registration is still pending approval by the admin. Please wait.");
		}
	} else {
		showAlert("Incorrect Email/Password or not registered. Click here to <a href='tenant-register.php' style='color: lightblue;'><b>Register</b></a>.");
	}
}



function tenant_update()
{
	global $owner_id, $full_name, $email, $password, $phone_no, $address, $id_type, $id_photo, $errors, $db;
	$tenant_id = validate($_POST['tenant_id']);
	$full_name = validate($_POST['full_name']);
	$email = validate($_POST['email']);
	$phone_no = validate($_POST['phone_no']);
	$address = validate($_POST['address']);
	$id_type = validate($_POST['id_type']);
	$password = md5($password); // Encrypt password
	$sql = "UPDATE tenant SET full_name='$full_name',email='$email',phone_no='$phone_no',address='$address',id_type='$id_type' WHERE tenant_id='$tenant_id'";
	$query = mysqli_query($db, $sql);
	if (!empty($query)) {
		showAlert("Your Information has been updated.");
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