<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class Mail
{
    public function mailFormat($user, $templatePath,$subject)
    {
        $email= (new TemplatedEmail())
            ->from('no.reply@snowtricks.com')
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate($templatePath)
            ->context([
                'userEmail' => $user->getEmail(),
                'token' => $user->getApiToken()
            ]);

        return $email;
    }
}