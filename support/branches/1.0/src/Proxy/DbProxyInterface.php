<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Database\DatabaseManagerInterface;
use Illuminate\Database\Query\Builder;

interface DbProxyInterface
{
    /**
     * Instance du gestionnaire de base de données|Gestionnaire de requête d'une table de la base de données.
     *
     * @param string|null $dbTable
     *
     * @return DatabaseManagerInterface|Builder
     */
    public function db(?string $dbTable = null);

    /**
     * Définition du gestionnaire de base de données.
     *
     * @param DatabaseManagerInterface $dbManager
     *
     * @return void
     */
    public function setDbManager(DatabaseManagerInterface $dbManager): void;
}