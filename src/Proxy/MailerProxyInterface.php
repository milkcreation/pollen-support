<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Mail\MailerInterface;

interface MailerProxyInterface
{
    /**
     * Instance du gestionnaire de mail.
     *
     * @return MailerInterface
     */
    public function mailer(): MailerInterface;

    /**
     * Définition du gestionnaire de routage.
     *
     * @param MailerInterface $mailer
     *
     * @return static
     */
    public function setMailer(MailerInterface $mailer): self;
}