<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Field\FieldDriverInterface;
use Pollen\Field\FieldManager;
use Pollen\Field\FieldManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\FieldProxyInterface
 */
trait FieldProxy
{
    /**
     * Instance du gestionnaire de champs.
     * @var FieldManagerInterface
     */
    private $fieldManager;

    /**
     * Instance du gestionnaire de champs|Instance d'un champs.
     *
     * @param string|null $alias Alias de qualification.
     * @param mixed $idOrParams Identifiant de qualification|Liste des attributs de configuration.
     * @param array $params Liste des attributs de configuration.
     *
     * @return FieldManagerInterface|FieldDriverInterface
     */
    public function field(?string $alias = null, $idOrParams = null, array $params = [])
    {
        if ($this->fieldManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(FieldManagerInterface::class)) {
                $this->fieldManager = $container->get(FieldManagerInterface::class);
            } else {
                try {
                    $this->fieldManager = FieldManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->fieldManager = new FieldManager();
                }
            }
        }

        return $alias === null ? $this->fieldManager : $this->fieldManager->get($alias, $idOrParams, $params);
    }

    /**
     * Définition du gestionnaire de champs.
     *
     * @param FieldManagerInterface $fieldManager
     *
     * @return static
     */
    public function setFieldManager(FieldManagerInterface $fieldManager): self
    {
        $this->fieldManager = $fieldManager;

        return $this;
    }
}