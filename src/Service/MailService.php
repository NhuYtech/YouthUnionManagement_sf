<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// require_once __DIR__ . '/../../vendor/phpmailer/Exception.php';
// require_once __DIR__ . '/../../vendor/phpmailer/PHPMailer.php';
// require_once __DIR__ . '/../../vendor/phpmailer/SMTP.php';

class MailService
{
    public function sendEmail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Cấu hình SMTP
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host = 'smtp.gmail.com';            //Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication
            $mail->Username = $_ENV['MAIL_USERNAME'];                        //SMTP username
            $mail->Password = $_ENV['MAIL_PASSWORD'];                              //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port = 465;
            $mail->SMTPDebug = 0;

            // Thiết lập mã hóa ký tự
            $mail->CharSet = 'UTF-8'; // Thêm dòng này để hỗ trợ tiếng Việt

            // Thiết lập người gửi và người nhận
            $mail->setFrom($_ENV['MAIL_USERNAME'], 'Hệ thống quản lý đoàn viên');
            $mail->addAddress($to);

            // Nội dung email
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);

            // Gửi email
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail error: " . $mail->ErrorInfo);              // Ghi log lỗi
            return false;
        }
    }
}