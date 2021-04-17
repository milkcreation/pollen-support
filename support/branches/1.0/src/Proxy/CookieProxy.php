<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Cookie\CookieInterface;
use Pollen\Cookie\CookieJar;
use Pollen\Cookie\CookieJarInterface;
use Pollen\Support\Exception\ProxyInvalidArgumentException;
use Pollen\Support\StaticProxy;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\CookieProxyInterface
 */
trait CookieProxy
{
    /**
     * Instance du gestionnaire de cookies.
     * @var CookieJar
     */
    private $cookieJar;

    /**
     * Instance du gestionnaire de cookies|Instance d'un cookie.
     *
     * @param string|null $alias
     *
     * @return CookieJarInterface|CookieInterface
     */
    public function cookie(?string $alias = null)
    {
        if ($this->cookieJar === null) {
            try {
                $this->cookieJar = CookieJar::getInstance();
            } catch (RuntimeException $e) {
                $this->cookieJar = StaticProxy::getProxyInstance(
                    CookieJarInterface::class,
                    CookieJar::class,
                    method_exists($this, 'getContainer') ? $this->getContainer() : null
                );
            }
        }

        if ($alias === null) {
            return $this->cookieJar;
        }

        if ($cookie = $this->cookieJar->get($alias)) {
            return $cookie;
        }

        throw new ProxyInvalidArgumentException(sprintf('Cookie [%s] is unavailable', $alias));
    }

    /**
     * Définition du gestionnaire de cookies.
     *
     * @param CookieJarInterface $cookieJar
     *
     * @return void
     */
    public function setCookieJar(CookieJarInterface $cookieJar): void
    {
        $this->cookieJar = $cookieJar;
    }
}