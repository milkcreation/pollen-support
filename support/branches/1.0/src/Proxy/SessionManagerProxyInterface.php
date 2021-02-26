<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Session\SessionManagerInterface;

interface SessionManagerProxyInterface
{
    /**
     * Instance du gestionnaire de sessions.
     *
     * @return SessionManagerInterface
     */
    public function sessionManager(): SessionManagerInterface;

    /**
     * Définition du gestionnaire de sessions.
     *
     * @param SessionManagerInterface $sessionManager
     *
     * @return SessionManagerProxy
     */
    public function setSessionManager(SessionManagerInterface $sessionManager): SessionManagerProxy;
}