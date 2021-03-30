<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Field\FieldDriverInterface;
use Pollen\Field\FieldManagerInterface;

Interface FieldProxyInterface
{
    /**
     * Instance du gestionnaire de champs|Instance d'un champs.
     *
     * @param string|null $alias Alias de qualification.
     * @param mixed $idOrParams Identifiant de qualification|Liste des attributs de configuration.
     * @param array $params Liste des attributs de configuration.
     *
     * @return FieldManagerInterface|FieldDriverInterface
     */
    public function field(?string $alias = null, $idOrParams = null, array $params = []);

    /**
     * Définition du gestionnaire de champs.
     *
     * @param FieldManagerInterface $fieldManager
     *
     * @return void
     */
    public function setFieldManager(FieldManagerInterface $fieldManager): void;
}