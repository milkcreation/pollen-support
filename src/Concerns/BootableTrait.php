<?php

declare(strict_types=1);

namespace Pollen\Support\Concerns;

trait BootableTrait
{
    /**
     * État de chargement.
     * @var bool
     */
    private $booted = false;

    /**
     * Vérification de l'état de chargement.
     *
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * Définition de l'état de chargement.
     *
     * @param bool $booted
     *
     * @return static
     */
    public function setBooted(bool $booted = true): BootableTrait
    {
        $this->booted = $booted;

        return $this;
    }
}