<?php
require_once '../app/services/EmailService.php';

class NotifyUser
{
    public static function  sendWelcomeEmail($to, $name)
    {
        $emailService = new EmailService();
        $subject = trans('welcome_email_subject');
        $body = '<h1>' . trans('welcome_greeting') . $name . '</h1>' .
            '<p>' . trans('welcome_body') . '</p>';
        $CompanyName = trans('company_name');
        $emailService->sendEmail($subject, $body, $to, $CompanyName);
    }
}
