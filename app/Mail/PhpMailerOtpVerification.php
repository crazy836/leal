<?php

namespace App\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PhpMailerOtpVerification
{
    public $otp;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $user)
    {
        $this->otp = $otp;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return bool
     */
    public function send()
    {
        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'sarmientoaldrin04@gmail.com';          // SMTP username
            $mail->Password   = 'iycf tdaw sfbm byfc';                  // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
            $mail->Port       = 587;                                    // TCP port to connect to
            
            $mail->CharSet = 'UTF-8';                                   // Set charset
            
            // Recipients
            $mail->setFrom('sarmientoaldrin04@gmail.com', 'Nike Store');
            $mail->addAddress($this->user->email, $this->user->name);   // Add a recipient
            
            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->Subject = 'OTP Verification Code';
            $mail->Body    = $this->buildHtmlBody();
            $mail->AltBody = $this->buildTextBody();
            
            // Send the email
            $result = $mail->send();
            
            \Log::info("PHPMailer OTP sent successfully to {$this->user->email}");
            return $result;
        } catch (Exception $e) {
            \Log::error("PHPMailer OTP failed to send to {$this->user->email}. Error: " . $mail->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Build HTML email body
     */
    private function buildHtmlBody()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <title>OTP Verification Code</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f8f9fa;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background: #ffffff;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }
                .header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                    font-weight: bold;
                }
                .content {
                    padding: 30px;
                }
                .otp-code {
                    background-color: #f8f9fa;
                    border: 2px dashed #667eea;
                    border-radius: 8px;
                    padding: 20px;
                    text-align: center;
                    font-size: 32px;
                    font-weight: bold;
                    letter-spacing: 5px;
                    color: #333;
                    margin: 20px 0;
                }
                .instructions {
                    background-color: #e9ecef;
                    border-left: 4px solid #667eea;
                    padding: 15px;
                    margin: 20px 0;
                    border-radius: 0 4px 4px 0;
                }
                .footer {
                    background-color: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    font-size: 12px;
                    color: #6c757d;
                    border-top: 1px solid #dee2e6;
                }
                .btn {
                    display: inline-block;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-decoration: none;
                    padding: 12px 24px;
                    border-radius: 5px;
                    font-weight: bold;
                    margin: 10px 0;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>OTP Verification</h1>
                </div>
                
                <div class="content">
                    <p>Hello ' . $this->user->name . ',</p>
                    
                    <p>Thank you for registering with our service. To complete your registration, please use the following One-Time Password (OTP) verification code:</p>
                    
                    <div class="otp-code">
                        ' . $this->otp . '
                    </div>
                    
                    <div class="instructions">
                        <strong>Important Instructions:</strong>
                        <ul>
                            <li>This code will expire in 5 minutes</li>
                            <li>Enter this code on the verification page to complete your registration</li>
                            <li>If you didn\'t request this code, please ignore this email</li>
                        </ul>
                    </div>
                    
                    <p>If you\'re having trouble with the verification process, please contact our support team.</p>
                    
                    <p>Best regards,<br>The Team</p>
                </div>
                
                <div class="footer">
                    <p>This is an automated message, please do not reply to this email.</p>
                    <p>&copy; ' . date('Y') . '. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Build text email body
     */
    private function buildTextBody()
    {
        return "Hello {$this->user->name},

Thank you for registering with our service. To complete your registration, please use the following One-Time Password (OTP) verification code:

{$this->otp}

Important Instructions:
- This code will expire in 5 minutes
- Enter this code on the verification page to complete your registration
- If you didn't request this code, please ignore this email

If you're having trouble with the verification process, please contact our support team.

Best regards,
The Team";
    }
}