<?php
require_once 'firebase-service.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];
    $middle = $_POST['middleName'];
    $email = $_POST['email'];
    $roles = $_POST['userRole'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    
    $firebaseService = new FirebaseService();

     // Check if email already exists
     $users = $firebaseService->getAllUsers();
     foreach ($users as $existingUser) {
         if ($existingUser['email'] === $email) {
             echo json_encode(['status' => 'error', 'message' => 'This email address is already registered. Please use a different email.']);
             exit();
         }
     }
    
    // Generate username if empty
    if (empty($username)) {
        $baseUsername = strtolower($fname . $lname);
        $username = $baseUsername;
    } else {
        $baseUsername = strtolower($username);
        $username = $baseUsername;
    }
    
    // Check for existing similar usernames
    $counter = 1;
    $originalUsername = $username;
    $users = $firebaseService->getAllUsers();
    
    while (true) {
        $usernameExists = false;
        
        if ($users) {
            foreach ($users as $user) {
                if ($user['username'] === $username) {
                    $usernameExists = true;
                    break;
                }
            }
        }
        
        if (!$usernameExists) {
            break;
        }
        
        $username = $originalUsername . $counter;
        $counter++;
    }
    
    $user = [
        'fname' => $fname,
        'lname' => $lname,
        'middle' => $middle,
        'email' => $email,
        'roles' => $roles,
        'password' => $password,
        'username' => $username,
        'status' => 'active'
    ];
    
    
    if ($firebaseService->createUser($user)) {
        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'safenestsystem@gmail.com';
            $mail->Password = 'xmws woiw bgfn iwdv';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('safenestsystem@gmail.com', 'Admin');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'Account Registration';
            $emailTemplate = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Account Registration</title>
            </head>
            <body style="margin: 0; padding: 0; background-color: #f6f9fc; font-family: Arial, sans-serif;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding: 40px 30px; background-color: #1a73e8; border-radius: 10px 10px 0 0;">
                            <h1 style="color: #ffffff; margin: 0; text-align: center; font-size: 24px;">Account Registration</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #555555; font-size: 16px; line-height: 24px; margin: 0 0 20px;">Hello,</p>
                            <p style="color: #555555; font-size: 16px; line-height: 24px; margin: 0 0 20px;">Your account has been successfully created. Here are your login details:</p>
                            
                            <div style="background-color: #f8f9fa; border-radius: 5px; padding: 20px; text-align: center; margin: 30px 0;">
                                <p style="font-size: 16px; font-weight: bold; color: #1a73e8;">Email: ' . $email . '</p>
                                <p style="font-size: 16px; font-weight: bold; color: #1a73e8;">Password: ' . $password . '</p>
                            </div>
                            
                            <div style="background-color: #fff8e1; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
                                <p style="color: #666666; font-size: 14px; margin: 0;">
                                    <strong>Note:</strong> Do not share this login credentials to anyone.
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f5f5f5; padding: 20px 30px; text-align: center; border-radius: 0 0 10px 10px;">
                            <p style="color: #666666; font-size: 12px; margin: 0;">This is an automated message, please do not reply.</p>
                            <p style="color: #666666; font-size: 12px; margin: 5px 0 0;">© ' . date('Y') . ' SafeNest System. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </body>
            </html>';
            
            $mail->Body = $emailTemplate;
            $mail->AltBody = "Your account has been created. Email: $email, Password: $password";
            
            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'User registered successfully and email sent']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'success', 'message' => 'User registered successfully but email could not be sent']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
    }
}
?>