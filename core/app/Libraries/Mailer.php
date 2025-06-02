<?php


namespace App\Libraries;


use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer extends PHPMailer
{
    public function __construct()
    {
        parent::__construct(true);
    }
    public function sendEmail($subject, $message, $email, $name = '')
    {
        try {
            //Server settings
            if(TRUE){
                $this->SMTPDebug = FALSE;                      // Enable verbose debug output
                $this->isSMTP();                                            // Send using SMTP
                $this->Host       = get_option('email_settings_host', '');                    // Set the SMTP server to send through
                $this->SMTPAuth   = true;                                   // Enable SMTP authentication
                $x_email = get_option('email_settings_email_address', '');
                $this->Username   = $x_email;                     // SMTP username
                $this->Password   = get_option('email_settings_email_password', '');                               // SMTP password
                $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $this->Port       = get_option('email_settings_port', 465);                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            }
            //Recipients
            $this->setFrom(get_option('email_settings_email_address', 'e-service@bennito254.com'), get_option('site_title', 'Update Server'));
            $this->addReplyTo(get_option('email_settings_reply_to', $x_email));
            $this->addAddress($email, $name);     // Add a recipient

            // Content
            $this->isHTML(true);                                  // Set email format to HTML
            $this->Subject = $subject;
            $this->Body    = $message;
            $this->AltBody = strip_tags($message);
            
            if ($this->send()) {
                return TRUE;
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }

        return FALSE;
    }
}