<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Partial\PartialManager;
use Pollen\Partial\PartialManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\PartialProxyInterface
 */
trait PartialProxy
{
    /**
     * Instance du gestionnaire de portions d'affichage.
     * @var PartialManagerInterface
     */
    private $partialManager;

    /**
     * Instance du gestionnaire de portions d'affichage|Instance d'une portion d'affichage.
     *
     * @param string|null $alias Alias de qualification.
     * @param mixed $idOrParams Identifiant de qualification|Liste des attributs de configuration.
     * @param array $params Liste des attributs de configuration.
     *
     * @return PartialManagerInterface
     */
    public function partial(?string $alias = null, $idOrParams = null, array $params = [])
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

        return $alias === null ? $this->partialManager: $this->partialManager->get($alias, $idOrParams, $params);
    }

    /**
     * Définition du gestionnaire de portions d'affichage.
     *
     * @param PartialManagerInterface $partialManager
     *
     * @return static
     */
    public function setPartialManager(PartialManagerInterface $partialManager): PartialProxy
    {
        $this->partialManager = $partialManager;

        return $this;
    }
}