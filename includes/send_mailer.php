<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendHtmlEmail($subject, $bodyHtml, $toEmail, $replyToEmail = null, $replyToName = null)
{
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'YOUR_EMAIL@gmail.com';
        $mail->Password = 'YOUR_PASSWORD_HERE';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('YOUR_EMAIL@gmail.com', 'Issighen Agency');
        if (!empty($replyToEmail)) {
            $mail->addReplyTo($replyToEmail, $replyToName ?: '');
        }
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $bodyHtml;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $bodyHtml));
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

function sendEmail($fromEmail, $name, $phone, $subject, $comment, $toEmail)
{
    $body = "<h3>New contact details from your Issighen Agency</h3>"
        . "<p>Name: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</p>"
        . "<p>Email: " . htmlspecialchars($toEmail, ENT_QUOTES, 'UTF-8') . "</p>"
        . "<p>Phone: " . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . "</p>"
        . "<p>Comment: " . nl2br(htmlspecialchars($comment, ENT_QUOTES, 'UTF-8')) . "</p>";

    return sendHtmlEmail($subject, $body, $toEmail, $fromEmail, $name);
}
