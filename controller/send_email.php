<?php
require './../PHPMailer/src/Exception.php';
require './../PHPMailer/src/PHPMailer.php';
require './../PHPMailer/src/SMTP.php';
require 'constant.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function company() {
    return "Barangay Marinig";
}

function email_template($type, $content) {
    return match ($type) {
        'new_account'=> new_account($content),
        'forgot_password' => forgot_password($content),
        'account_rejected'=> account_rejected($content),
        default => '',
    };
}

function send_mail($type, $email, $name, $subject, $content) {
    $mail = new PHPMailer(true);
    
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        // Email Content
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = email_template($type, $content);

        return $mail->send();
    } catch (Exception $e) {
        return $e;
    }
}

function new_account($content) {
    $company = company();
    return <<<TEXT
    <p>Hi {$content['name']},</p>
    <p>Welcome to $company! Your account has been successfully created.</p>
    <p><strong>Your login details:</strong></p>
    <p><strong>Username:</strong> {$content['username']}</p>
    <p><strong>Password:</strong> {$content['password']}</p>
    <p>To access your account, click the button below:</p>
    <p><a href="{$content['url']}" style="display:inline-block; padding:10px 15px; color:#fff; background:#28a745; text-decoration:none; border-radius:5px;">Login to Your Account</a></p>
    <p>For security, we recommend changing your password after logging in.</p>
    <p>Best regards,<br> $company Support Team</p>
    TEXT;
}


function account_rejected($content) {
    $company = company(); // Barangay Name or System Name
    return <<<TEXT
    <p>Hi {$content['name']},</p>
    <p>Thank you for registering with $company's online system.</p>
    <p>We regret to inform you that your account application has been <strong>rejected</strong>.</p>
    <p>If you believe this is a mistake or need further assistance, you may visit the Barangay Hall or contact us at <a href="mailto:support@$company.com">support@$company.com</a>.</p>
    <p><strong>Next Steps:</strong></p>
    <ul>
        <li>Ensure that all information provided is correct and complete.</li>
        <li>If necessary, reapply with the correct details.</li>
        <li>For further inquiries, visit the Barangay Office.</li>
    </ul>
    <p>We appreciate your cooperation and look forward to assisting you.</p>
    <p>Best regards,<br> $company Support Team</p>
    TEXT;
}



function forgot_password($content) {
    $company = company();
    return <<<TEXT
    <p>Dear {$content['name']},</p>
    <p>We received a request to reset your password.</p>
    <p><strong>Click below to reset your password:</strong></p>
    <p><a href="{$content['url']}" style="display:inline-block; padding:10px 15px; color:#fff; background:#007bff; text-decoration:none; border-radius:5px;">Reset Password</a></p>
    <p>This link is valid for 30 minutes. If you did not request a reset, ignore this email.</p>
    <p>Best regards,<br> $company Support Team</p>
    TEXT;
}
?>
