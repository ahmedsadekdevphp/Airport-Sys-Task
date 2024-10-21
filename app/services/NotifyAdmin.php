<?php
namespace App\Services;
use App\Models\User;
use App\Services\EmailService;

class NotifyAdmin
{
    public static function sendActionEmail($action, $airportName)
    {
        $emailService = new EmailService();
        $user = new User();
        $adminEmails = $user->getAdminEmails();
        $emailList = array_column($adminEmails, 'email'); // Extract emails as array
        $subject = 'Airport ' . ucfirst($action) . ' Notification';
        $body = '<p>An airport has been ' . htmlspecialchars($action) . ': ' . htmlspecialchars($airportName) . '.</p>';
        $CompanyName = trans('company_name');
        $emailService->sendEmail($subject, $body, $emailList, $CompanyName);
    }
}
