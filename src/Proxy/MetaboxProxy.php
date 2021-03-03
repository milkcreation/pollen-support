<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Metabox\MetaboxManager;
use Pollen\Metabox\MetaboxManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\MetaboxProxyInterface
 */
trait MetaboxProxy
{
    /**
     * Instance du gestionnaire de metaboxes.
     * @var MetaboxManagerInterface|null
     */
    private $metaboxManager;

    /**
     * Instance du gestionnaire de metaboxes.
     *
     * @return MetaboxManagerInterface
     */
    public function metabox(): MetaboxManagerInterface
    {
        if ($this->metaboxManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(MetaboxManagerInterface::class)) {
                $this->metaboxManager = $container->get(MetaboxManagerInterface::class);
            } else {
                try {
                    $this->metaboxManager = MetaboxManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->metaboxManager = new MetaboxManager();
                }
            }
        }

        return $this->metaboxManager;
    }

    /**
     * Définition de l'instance du gestionnaire de metaboxes.
     *
     * @param MetaboxManagerInterface $metaboxManager
     *
     * @return static
     */
    public function setMetaboxManager(MetaboxManagerInterface $metaboxManager): MetaboxProxy
    {
        $this->metaboxManager = $metaboxManager;

        return $this;
    }
}