<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Metabox\MetaboxManagerInterface;

interface MetaboxProxyInterface
{
    /**
     * Instance du gestionnaire de metaboxes.
     *
     * @return MetaboxManagerInterface
     */
    public function metabox(): MetaboxManagerInterface;

    /**
     * Définition de l'instance du gestionnaire de metaboxes.
     *
     * @param MetaboxManagerInterface $metaboxManager
     *
     * @return MetaboxProxy|static
     */
    public function setMetaboxManager(MetaboxManagerInterface $metaboxManager): MetaboxProxy;
}