<?php
require_once 'firebase-service.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    $firebaseService = new FirebaseService();
    
    // Fetch all users to find the user with the given email
    $users = $firebaseService->getAllUsers();
    $userFound = false;
    $userId = null;
    
    foreach ($users as $id => $user) {
        if ($user['email'] === $email) {
            $userFound = true;
            $userId = $id;
            break;
        }
    }
    
    if (!$userFound) {
        echo json_encode(['status' => 'error', 'message' => 'Email not found']);
        exit;
    }
    
    // Generate OTP
    $otp = sprintf("%06d", random_int(0, 999999));
    
    // Update user's OTP in Firebase
    $updateData = ['otp_code' => $otp];
    
    if ($firebaseService->updateUser($userId, $updateData)) {
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
            $mail->Subject = 'Password Reset OTP';
            $emailTemplate = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Password Reset OTP</title>
            </head>
            <body style="margin: 0; padding: 0; background-color: #f6f9fc; font-family: Arial, sans-serif;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding: 40px 30px; background-color: #1a73e8; border-radius: 10px 10px 0 0;">
                            <h1 style="color: #ffffff; margin: 0; text-align: center; font-size: 24px;">Password Reset Code</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #555555; font-size: 16px; line-height: 24px; margin: 0 0 20px;">Hello,</p>
                            <p style="color: #555555; font-size: 16px; line-height: 24px; margin: 0 0 20px;">You have requested to reset your password. Please use the following OTP code to complete your password reset:</p>
                            
                            <div style="background-color: #f8f9fa; border-radius: 5px; padding: 20px; text-align: center; margin: 30px 0;">
                                <span style="font-size: 32px; font-weight: bold; color: #1a73e8; letter-spacing: 5px;">' . $otp . '</span>
                            </div>
                            
                            <div style="background-color: #fff8e1; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
                                <p style="color: #666666; font-size: 14px; margin: 0;">
                                    <strong>Note:</strong> Do not share this code to others. If you did not request this password reset, please ignore this email.
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
            $mail->AltBody = "Your OTP for password reset is: $otp";
            
            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Could not send email']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update OTP in Firebase']);
    }
}
?>