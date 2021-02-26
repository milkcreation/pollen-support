<?php

declare(strict_types=1);

namespace Pollen\Support\Concerns;

interface BuildableTraitInterface
{
    /**
     * Vérification de l'état d'initialisation.
     * @return bool
     */
    public function isBuilt(): bool;

    /**
     * Définition de l'état d'initialisation.
     *
     * @param bool $built
     *
     * @return BuildableTrait|static
     */
    public function setBuilt(bool $built = true): BuildableTrait;
}