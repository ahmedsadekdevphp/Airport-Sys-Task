<?php

namespace App\Services;

use App\Services\EmailService;

class NotifyUser
{
    /**
     * Sends a welcome email to a new user.
     *
     * @param string $to The recipient's email address.
     * @param string $name The name of the recipient.
     * 
     * @return void
     */

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
