<?php

namespace App\Mailing;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MoiJVMailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * MoiJVMailer constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($to, $subject, $template, $context = [])
    {
        $msg = new TemplatedEmail();
        $msg->htmlTemplate($template)
            ->context($context)
            ->subject($subject)
            ->to($to)
            ->from(new Address('mailer@moijv.com', 'Moijv Mail Bot'))
        ;

        $this->mailer->send($msg);

    }
}
