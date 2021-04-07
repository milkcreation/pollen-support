<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Illuminate\Database\Query\Builder;
use Pollen\Database\DatabaseManager;
use Pollen\Database\DatabaseManagerInterface;
use Pollen\Support\StaticProxy;
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
            try {
                $this->dbManager = DatabaseManager::getInstance();
            } catch (RuntimeException $e) {
                $this->dbManager = StaticProxy::getProxyInstance(
                    DatabaseManagerInterface::class,
                    DatabaseManager::class,
                    method_exists($this, 'getContainer') ? $this->getContainer() : null
                );
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
     * @return void
     */
    public function setDbManager(DatabaseManagerInterface $dbManager): void
    {
        $this->dbManager = $dbManager;
    }
}