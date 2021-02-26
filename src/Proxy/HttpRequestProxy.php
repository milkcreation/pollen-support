<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Http\Request;
use Pollen\Http\RequestInterface;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ServerRequestInterface as PsrRequestInterface;
use Symfony\Component\HttpFoundation\Request as BaseRequest;

/**
 * @see \Pollen\Support\Proxy\HttpRequestProxyInterface
 */
trait HttpRequestProxy
{
    /**
     * Instance de la requête HTTP.
     * @var RequestInterface
     */
    private $httpRequest;

    /**
     * Instance de la requête HTTP.
     *
     * @return RequestInterface|BaseRequest
     */
    public function httpRequest(): RequestInterface
    {
        if ($this->httpRequest === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(RequestInterface::class)) {
                $this->httpRequest = $container->get(RequestInterface::class);
            } else {
                $this->httpRequest = Request::createFromGlobals();
            }
        }

        return $this->httpRequest;
    }

    /**
     * Instance de la requête HTTP au format PSR-7.
     *
     * @return PsrRequestInterface
     */
    public function httpPsrRequest(): PsrRequestInterface
    {
        return $this->httpRequest()->psr();
    }

    /**
     * Définition de la requête HTTP.
     *
     * @param RequestInterface $httpRequest
     *
     * @return static
     */
    public function setHttpRequest(RequestInterface $httpRequest): HttpRequestProxy
    {
        $this->httpRequest = $httpRequest;

        return $this;
    }
}