<?php

namespace WpToolKit\Controller;

use WPToolkit\Entity\MailEnvelope;
use WPToolkit\Entity\SmtpSettings;

class MailController
{
    public function send(
        string $email,
        string $subject,
        string $message,
        array $attachments = array()
    ): void {
        wp_mail(
            $email,
            $subject,
            $message,
            array('Content-Type: text/html; charset=UTF-8'),
            $attachments
        );
    }

    public function setSmtpSettings(SmtpSettings $settings, MailEnvelope $envelope): void
    {
        add_action('phpmailer_init', function ($phpmailer) use ($settings, $envelope) {
            $phpmailer->isSMTP();
            $phpmailer->Host       = $settings->host;
            $phpmailer->SMTPAuth   = $settings->auth;
            $phpmailer->Username   = $settings->username;
            $phpmailer->Password   = $settings->password;
            $phpmailer->Port       = $settings->port;
            $phpmailer->SMTPSecure = $settings->secure;
            $phpmailer->SMTPDebug  = $settings->debugMode;

            $phpmailer->Debugoutput = fn($str, $level) => file_put_contents(
                $settings->debugFileOutput,
                gmdate("Y-m-d H:i:s") . " [$level] $str\n",
                FILE_APPEND
            );

            $form = $envelope->getFrom();
            $reply = $envelope->getReply();

            if (!empty($form)) {
                $phpmailer->setFrom($form[0], $form[1] ?? '');
            }

            if (!empty($reply)) {
                $phpmailer->addReplyTo($reply[0], $reply[1] ?? '');
            }

            foreach ($envelope->getCarbonCopies() as $email => $name) {
                $phpmailer->addCC($email, $name);
            }

            foreach ($envelope->getBlindCarbonCopies() as $email => $name) {
                $phpmailer->addBCC($email, $name);
            }
        });
    }
}
