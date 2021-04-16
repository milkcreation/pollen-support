<?php

declare(strict_types=1);

namespace Pollen\Support;

use Psr\Container\ContainerInterface as Container;
use Pollen\Support\Exception\ProxyRuntimeException;

class StaticProxy
{
    /**
     * @var Container
     */
    protected static $proxyContainer;

    /**
     * Définition du conteneur d'injection de dépendances
     *
     * @param Container $container
     *
     * @return void
     */
    public static function setProxyContainer(Container $container): void
    {
        static::$proxyContainer = $container;
    }

    /**
     * Récupération d'une instance servie par le proxy static.
     *
     * @param string $alias
     * @param string|null $fallbackClassname
     * @param Container|null $container
     *
     * @return object
     */
    public static function getProxyInstance(string $alias, ?string $fallbackClassname, ?Container $container = null): object
    {
        if ($container === null) {
            $container = static::$proxyContainer;
        }

        if ($container instanceof Container && $container->has($alias)) {
            return $container->get($alias);
        }

        if ($fallbackClassname !== null) {
            return new $fallbackClassname();
        }

        throw new ProxyRuntimeException(sprintf('Static Proxy could not retrieves [%s] instance.', $alias));
    }
}
