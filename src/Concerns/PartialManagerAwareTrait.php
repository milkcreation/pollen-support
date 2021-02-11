<?php

declare(strict_types=1);

namespace Pollen\Support\Concerns;

use Pollen\Partial\Partial;
use Pollen\Partial\PartialInterface;
use Throwable;

trait PartialManagerAwareTrait
{
    /**
     * Instance du gestionnaire de portions d'affichage.
     * @var PartialInterface
     */
    private $partialManager;

    /**
     * Instance du gestionnaire de portions d'affichage.
     *
     * @return PartialInterface
     */
    public function partialManager(): PartialInterface
    {
        if ($this->partialManager === null) {
            if (in_array(ContainerAwareTrait::class, class_uses($this), true) && $this->containerHas(PartialInterface::class)) {
                $this->partialManager = $this->containerGet(PartialInterface::class);
            } else {
                try {
                    $this->partialManager = Partial::instance();
                } catch(Throwable $e) {
                    $this->partialManager = new Partial();
                }
            }
        }

        return $this->partialManager;
    }

    /**
     * Définition du gestionnaire de portion d'affichage.
     *
     * @param PartialInterface $partialManager
     *
     * @return static
     */
    public function setPartialManager(PartialInterface $partialManager): self
    {
        $this->partialManager = $partialManager;

        return $this;
    }
}