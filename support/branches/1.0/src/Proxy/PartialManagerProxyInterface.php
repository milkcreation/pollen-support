<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Partial\PartialManagerInterface;

interface PartialManagerProxyInterface
{
    /**
     * Instance du gestionnaire de portions d'affichage.
     *
     * @return PartialManagerInterface
     */
    public function partialManager(): PartialManagerInterface;

    /**
     * Définition du gestionnaire de portions d'affichage.
     *
     * @param PartialManagerInterface $partialManager
     *
     * @return PartialManagerProxy
     */
    public function setPartialManager(PartialManagerInterface $partialManager): PartialManagerProxy;
}