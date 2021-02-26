<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Field\FieldManagerInterface;

Interface FieldManagerProxyInterface
{
    /**
     * Instance du gestionnaire de champs.
     *
     * @return FieldManagerInterface
     */
    public function fieldManager(): FieldManagerInterface;

    /**
     * Définition du gestionnaire de champs.
     *
     * @param FieldManagerInterface $fieldManager
     *
     * @return FieldManagerProxy|static
     */
    public function setFieldManager(FieldManagerInterface $fieldManager): FieldManagerProxy;
}