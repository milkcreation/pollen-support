<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Asset\AssetInterface;
use Pollen\Asset\AssetManager;
use Pollen\Asset\AssetManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\AssetProxyInterface
 */
trait AssetProxy
{
    /**
     * Instance du gestionnaire de politique de confidentialité.
     * @var AssetManagerInterface|null
     */
    private $assetManager;

    /**
     * Instance du gestionnaire de politique de confidentialité.
     *
     * @param string|null $name
     *
     * @return AssetManagerInterface|AssetInterface|null
     */
    public function asset(?string $name = null)
    {
        if ($this->assetManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(AssetManagerInterface::class)) {
                $this->assetManager = $container->get(AssetManagerInterface::class);
            } else {
                try {
                    $this->assetManager = AssetManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->assetManager = new AssetManager();
                }
            }
        }

        if ($name === null) {
            return $this->assetManager;
        }

        return $this->assetManager->get($name);
    }

    /**
     * Définition du gestionnaire de politique de confidentialité.
     *
     * @param AssetManagerInterface $assetManager
     *
     * @return AssetProxy|AssetProxyInterface|static
     */
    public function setAssetManager(AssetManagerInterface $assetManager): self
    {
        $this->assetManager = $assetManager;

        return $this;
    }
}