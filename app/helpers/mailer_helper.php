<?php

/**
 * Mailer Helper
 * Sends HTML emails using PHPMailer via SMTP configuration.
 */

function sendComplaintEmail(string $toEmail, string $toName, string $subject, string $htmlBody): array
{
    $phpmailerPath = dirname(__DIR__) . '/email/PHPMailer/src/';
    $configPath    = dirname(__DIR__) . '/email/config/config.php';

    $smtpConfig = [
        'host'       => 'smtp.gmail.com',
        'port'       => 587,
        'encryption' => 'tls',
        'username'   => 'theekshanathennakoon79@gmail.com',
        'password'   => 'pgqoptmaegfpuozc',
        'from_email' => 'theekshanathennakoon79@gmail.com',
        'from_name'  => 'Public Day Complaint System',
        'auth'       => true,
    ];

    if (file_exists($configPath)) {
        $loadedConfig = require $configPath;
        if (!empty($loadedConfig['smtp'])) {
            $smtpConfig = array_merge($smtpConfig, $loadedConfig['smtp']);
        }
    }

    if (file_exists($phpmailerPath . 'PHPMailer.php')) {
        require_once $phpmailerPath . 'Exception.php';
        require_once $phpmailerPath . 'PHPMailer.php';
        require_once $phpmailerPath . 'SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $smtpConfig['host'];
            $mail->Port       = (int)$smtpConfig['port'];
            $mail->SMTPAuth   = $smtpConfig['auth'] ?? true;
            $mail->Username   = $smtpConfig['username'];
            $mail->Password   = $smtpConfig['password'];

            if (!empty($smtpConfig['encryption']) && strtolower($smtpConfig['encryption']) === 'ssl') {
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            }

            // SSL bypass for local environments / XAMPP Windows
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ]
            ];

            $mail->CharSet = 'UTF-8';
            $fromEmail = !empty($smtpConfig['from_email']) ? $smtpConfig['from_email'] : 'zynovaglobaltech@gmail.com';
            $fromName  = 'Governor\'s Secretariat - Public Day Complaint System';
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags($htmlBody);

            $mail->send();
            return ['success' => true, 'message' => 'Email sent successfully.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Mailer error: ' . $mail->ErrorInfo];
        }
    } else {
        // Fallback: PHP built-in mail()
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Governor's Secretariat <noreply@governorsoffice.lk>\r\n";

        $sent = @mail($toEmail, $subject, $htmlBody, $headers);
        if ($sent) {
            return ['success' => true, 'message' => 'Email sent successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to send email. Check SMTP settings.'];
        }
    }
}
