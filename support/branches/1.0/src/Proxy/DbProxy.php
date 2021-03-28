<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Illuminate\Database\Query\Builder;
use Pollen\Database\DatabaseManager;
use Pollen\Database\DatabaseManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\DbProxyInterface
 */
trait DbProxy
{
    /**
     * Instance du gestionnaire de base de données.
     * @var DatabaseManagerInterface
     */
    private $dbManager;

    /**
     * Instance du gestionnaire de base de données|Gestionnaire de requête d'une table de la base de données.
     *
     * @param string|null $dbTable
     *
     * @return DatabaseManagerInterface|Builder
     */
    public function db(?string $dbTable = null)
    {
        if ($this->dbManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(DatabaseManagerInterface::class)) {
                $this->dbManager = $container->get(DatabaseManagerInterface::class);
            } else {
                try {
                    $this->dbManager = DatabaseManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->dbManager = new DatabaseManager();
                }
            }
        }

        if ($dbTable === null) {
            return $this->dbManager;
        }

        return $this->dbManager->getConnection()->table($dbTable);
    }

    /**
     * Définition du gestionnaire de base de données.
     *
     * @param DatabaseManagerInterface $dbManager
     *
     * @return static
     */
    public function setDbManager(DatabaseManagerInterface $dbManager): self
    {
        $this->dbManager = $dbManager;

        return $this;
    }
}