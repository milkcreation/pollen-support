<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Session\SessionManagerInterface;

interface SessionProxyInterface
{
    /**
     * Instance du gestionnaire de sessions.
     *
     * @return SessionManagerInterface
     */
    public function session(): SessionManagerInterface;

    /**
     * Définition du gestionnaire de sessions.
     *
     * @param SessionManagerInterface $sessionManager
     *
     * @return SessionProxy|static
     */
    public function setSessionManager(SessionManagerInterface $sessionManager): SessionProxy;
}