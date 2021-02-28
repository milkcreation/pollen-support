<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Partial\PartialDriverInterface;
use Pollen\Partial\PartialManagerInterface;

interface PartialProxyInterface
{
    /**
     * Instance du gestionnaire de portions d'affichage|Instance d'une portion d'affichage.
     *
     * @param string|null $alias Alias de qualification.
     * @param mixed $idOrParams Identifiant de qualification|Liste des attributs de configuration.
     * @param array $params Liste des attributs de configuration.
     *
     * @return PartialManagerInterface|PartialDriverInterface
     */
    public function partial(?string $alias = null, $idOrParams = null, array $params = []);

    /**
     * Définition du gestionnaire de portions d'affichage.
     *
     * @param PartialManagerInterface $partialManager
     *
     * @return PartialProxy|static
     */
    public function setPartialManager(PartialManagerInterface $partialManager): PartialProxy;
}