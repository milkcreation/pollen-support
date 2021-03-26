<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Mail\MailableInterface;
use Pollen\Mail\MailManager;
use Pollen\Mail\MailManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\MailProxyInterface
 */
trait MailProxy
{
    /**
     * Instance du gestionnaire de mail.
     * @var MailManagerInterface
     */
    private $mailManager;

    /**
     * Instance du gestionnaire de mail|Instance de mail.
     *
     * @param MailableInterface|string|array|null $mailable
     *
     * @return MailManagerInterface|MailableInterface
     */
    public function mail($mailable = null)
    {
        if ($this->mailManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(MailManagerInterface::class)) {
                $this->mailManager = $container->get(MailManagerInterface::class);
            } else {
                try {
                    $this->mailManager = MailManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->mailManager = new MailManager();
                }
            }
        }

        if ($mailable === null) {
            return $this->mailManager;
        }

        return $this->mailManager->setMailable($mailable)->getMailable();
    }

    /**
     * Définition du gestionnaire de mail.
     *
     * @param MailManagerInterface $mailManager
     *
     * @return static
     */
    public function setMailManager(MailManagerInterface $mailManager): self
    {
        $this->mailManager = $mailManager;

        return $this;
    }
}