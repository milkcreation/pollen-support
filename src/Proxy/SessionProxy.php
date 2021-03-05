<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Session\SessionManager;
use Pollen\Session\SessionManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\SessionProxyInterface
 */
trait SessionProxy
{
    /**
     * Instance du gestionnaire de sessions.
     * @var SessionManagerInterface
     */
    private $sessionManager;

    /**
     * Instance du gestionnaire de sessions.
     *
     * @return SessionManagerInterface
     */
    public function session(): SessionManagerInterface
    {
        if ($this->sessionManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(SessionManagerInterface::class)) {
                $this->sessionManager = $container->get(SessionManagerInterface::class);
            } else {
                try {
                    $this->sessionManager = SessionManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->sessionManager = new SessionManager();
                }
            }
        }

        return $this->sessionManager;
    }

    /**
     * Définition du gestionnaire de sessions.
     *
     * @param SessionManagerInterface $sessionManager
     *
     * @return static
     */
    public function setSessionManager(SessionManagerInterface $sessionManager): self
    {
        $this->sessionManager = $sessionManager;

        return $this;
    }
}