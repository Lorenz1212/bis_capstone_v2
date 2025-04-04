<?php 
	include '../connection/connect.php';

    $id 	= $conn->real_escape_string($_POST['id']);
	$purok    = $conn->real_escape_string($_POST['purok']);
	$message 	= $conn->real_escape_string($_POST['message']);
	$title 	= $conn->real_escape_string($_POST['title']);

	if(!empty($id)){
		$query 		= "UPDATE announcement SET `title`='$title', `purok`='$purok', `message`='$message' WHERE id=$id;";	
		$result 	= $conn->query($query);

		if($result === true){
            
			$_SESSION['message'] = 'Announcement details has been updated!';
			$_SESSION['success'] = 'success';

		}else{

			$_SESSION['message'] = 'Somethin went wrong!';
			$_SESSION['success'] = 'danger';
		}

	}else{
		$_SESSION['message'] = 'No Announcement ID found!';
		$_SESSION['success'] = 'danger';
	}

    header("Location: announcement.php");

	$conn->close();
?>