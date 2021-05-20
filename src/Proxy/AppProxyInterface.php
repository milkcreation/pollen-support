<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Kernel\ApplicationInterface;

Interface AppProxyInterface
{
    /**
     * Instance de l'application.
     *
     * @param string|null $serviceName
     *
     * @return ApplicationInterface|mixed
     */
    public function app(?string $serviceName = null);

    /**
     * Définition de l'application.
     *
     * @param ApplicationInterface $app
     *
     * @return void
     */
    public function setApp(ApplicationInterface $app): void;
}