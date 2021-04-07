<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Routing\Router;
use Pollen\Routing\RouterInterface;
use Pollen\Support\StaticProxy;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\RouterProxyInterface
 */
trait RouterProxy
{
    /**
     * Instance du gestionnaire de routage.
     * @var RouterInterface
     */
    private $router;

    /**
     * Instance du gestionnaire de routage.
     *
     * @return RouterInterface
     */
    public function router(): RouterInterface
    {
        if ($this->router === null) {
            try {
                $this->router = Router::getInstance();
            } catch (RuntimeException $e) {
                $this->router = StaticProxy::getProxyInstance(
                    RouterInterface::class,
                    Router::class,
                    method_exists($this, 'getContainer') ? $this->getContainer() : null
                );
            }
        }

        return $this->router;
    }

    /**
     * Définition du gestionnaire de routage.
     *
     * @param RouterInterface $router
     *
     * @return void
     */
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }
}