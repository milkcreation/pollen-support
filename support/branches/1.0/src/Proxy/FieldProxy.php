<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Field\FieldDriverInterface;
use Pollen\Field\FieldManager;
use Pollen\Field\FieldManagerInterface;
use Pollen\Support\Exception\ProxyInvalidArgumentException;
use Pollen\Support\StaticProxy;
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
            try {
                $this->fieldManager = FieldManager::getInstance();
            } catch (RuntimeException $e) {
                $this->fieldManager = StaticProxy::getProxyInstance(
                    FieldManagerInterface::class,
                    FieldManager::class,
                    method_exists($this, 'getContainer') ? $this->getContainer() : null
                );
            }
        }

        if ($alias === null) {
            return $this->fieldManager;
        }

        if ($field = $this->fieldManager->get($alias, $idOrParams, $params)) {
            return $field;
        }

        throw new ProxyInvalidArgumentException(sprintf('Field [%s] is unavailable', $alias));
    }

    /**
     * Définition du gestionnaire de champs.
     *
     * @param FieldManagerInterface $fieldManager
     *
     * @return void
     */
    public function setFieldManager(FieldManagerInterface $fieldManager): void
    {
        $this->fieldManager = $fieldManager;
    }
}