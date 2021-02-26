<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Mail\Mailer;
use Pollen\Mail\MailerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\MailerProxyInterface
 */
trait MailerProxy
{
    /**
     * Instance du gestionnaire de mails.
     * @var MailerInterface
     */
    private $mailer;

    /**
     * Instance du gestionnaire de mail.
     *
     * @return MailerInterface
     */
    public function mailer(): MailerInterface
    {
        if ($this->mailer === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(MailerInterface::class)) {
                $this->mailer = $container->get(MailerInterface::class);
            } else {
                try {
                    $this->mailer = Mailer::getInstance();
                } catch(RuntimeException $e) {
                    $this->mailer = new Mailer();
                }
            }
        }

        return $this->mailer;
    }

    /**
     * Définition du gestionnaire de routage.
     *
     * @param MailerInterface $mailer
     *
     * @return static
     */
    public function setMailer(MailerInterface $mailer): MailerProxy
    {
        $this->mailer = $mailer;

        return $this;
    }
}