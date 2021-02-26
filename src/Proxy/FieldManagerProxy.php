<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Field\FieldManager;
use Pollen\Field\FieldManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\FieldManagerProxyInterface
 */
trait FieldManagerProxy
{
    /**
     * Instance du gestionnaire de champs.
     * @var FieldManagerInterface
     */
    private $fieldManager;

    /**
     * Instance du gestionnaire de champs.
     *
     * @return FieldManagerInterface
     */
    public function fieldManager(): FieldManagerInterface
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

        return $this->fieldManager;
    }

    /**
     * Définition du gestionnaire de champs.
     *
     * @param FieldManagerInterface $fieldManager
     *
     * @return static
     */
    public function setFieldManager(FieldManagerInterface $fieldManager): FieldManagerProxy
    {
        $this->fieldManager = $fieldManager;

        return $this;
    }
}