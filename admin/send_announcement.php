<?php 
try {
    include '../connection/connect.php';
    include '../controller/send_email.php';
    
    $id = $conn->real_escape_string($_GET['id']);

    if (!empty($id)) {
        $current_date = date('Y-m-d');
        $query = "SELECT * FROM announcement WHERE id=$id";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $announcement = $result->fetch_assoc();
          
            // Fetch residents
            if ($announcement['purok'] == 'ALL') {
                $query = "SELECT * FROM resident_list WHERE email IS NOT NULL";
            } else {
                $query = "SELECT * FROM resident_list WHERE address='" . $announcement['purok'] . "' AND email IS NOT NULL";
            }
            $resident = $conn->query($query);

            while ($row = $resident->fetch_assoc()) {
                
                $resident_id = $row['accountID'];

                $email = $row['email'];
            
                $fname = $row['firstName'];
                
                // Check if SMS already sent
                $query = "SELECT * FROM email_sent WHERE announcement_id= $id AND resident_id = $resident_id";

                $result = $conn->query($query);
                
                if (!$result || $result->num_rows == 0) {

                    $subject = $announcement['title'];

                    $content = [
                        'subject'=>$subject,
                        'message'=>$announcement['message']
                    ];


                    $response = send_mail('announcement',$email,$fname,$subject,$content);
                    
                    if ($response) {
                        // Insert SMS sent record
                        $insert = "INSERT INTO email_sent (`resident_id`, `announcement_id`, `email`, `email_status`) 
                                   VALUES ('$resident_id', '$id', '$email','1')";
                        $conn->query($insert);
                        
                        // Update announcement date
                        $query = "UPDATE announcement SET `date_announced`= '$current_date',`status`='POSTED' WHERE id=$id";  
                        $conn->query($query);
                    }else{
                        $_SESSION['message'] = $response['message'];
                        $_SESSION['success'] = 'error';
                        header("Location: announcement.php");
                        exit();
                    }
                }
            }

            $_SESSION['message'] = "Announcement has been sent via email!";
            $_SESSION['success'] = 'success';
        } else {
            $_SESSION['message'] = 'Something went wrong!';
            $_SESSION['success'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'No Announcement ID found!';
        $_SESSION['success'] = 'danger';
    }

    header("Location: announcement.php");
    exit();

} catch (\Throwable $th) {
    $_SESSION['message'] = $th->getMessage();
    $_SESSION['success'] = 'danger';
}
?>

