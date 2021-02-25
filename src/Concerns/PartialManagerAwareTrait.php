<?php

declare(strict_types=1);

namespace Pollen\Support\Concerns;

use Pollen\Partial\PartialManager;
use Pollen\Partial\PartialManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

trait PartialManagerAwareTrait
{
    /**
     * Instance du gestionnaire de portions d'affichage.
     * @var PartialManagerInterface
     */
    private $partialManager;

    /**
     * Instance du gestionnaire de portions d'affichage.
     *
     * @return PartialManagerInterface
     */
    public function partialManager(): PartialManagerInterface
    {
        if ($this->partialManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(PartialManagerInterface::class)) {
                $this->partialManager = $container->get(PartialManagerInterface::class);
            } else {
                try {
                    $this->partialManager = PartialManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->partialManager = new PartialManager();
                }
            }
        }

        return $this->partialManager;
    }

    /**
     * Définition du gestionnaire de portions d'affichage.
     *
     * @param PartialManagerInterface $partialManager
     *
     * @return static
     */
    public function setPartialManager(PartialManagerInterface $partialManager): self
    {
        $this->partialManager = $partialManager;

        return $this;
    }
}