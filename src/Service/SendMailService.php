<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(
        string $from,
        string $name,
        string $to,
        string $subject,
        string $template,
        array $context
    )

    {
        // $email = new Email();
        $email = new TemplatedEmail();
        $email->from(new Address($from, $name))
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);
        $this->mailer->send($email);
    }
    
}