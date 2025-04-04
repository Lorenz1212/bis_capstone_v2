<?php 
    include '../connection/connect.php';
    
	$purok    = $conn->real_escape_string($_POST['purok']);
	$message 	= $conn->real_escape_string($_POST['message']);
    $title 	= $conn->real_escape_string($_POST['title']);

    if(!empty($purok) && !empty($message)){

        $insert  = "INSERT INTO announcement (`purok`, `message`, `title`) 
                    VALUES ('$purok', '$message', '$title')";
        $result  = $conn->query($insert);

        if($result === true){
            $_SESSION['message'] = 'Announcement added!';
            $_SESSION['success'] = 'success';

        }else{
            $_SESSION['message'] = 'Something went wrong!';
            $_SESSION['success'] = 'danger';
        }

    }else{

        $_SESSION['message'] = 'Please fill up the form completely!';
        $_SESSION['success'] = 'danger';
    }

    header("Location: announcement.php");

	$conn->close();
?>