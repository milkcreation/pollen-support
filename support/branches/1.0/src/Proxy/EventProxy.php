<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Event\EventDispatcher;
use Pollen\Event\EventDispatcherInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\EventProxyInterface
 */
trait EventProxy
{
    /**
     * Instance du répartiteur d'événements.
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Instance du répartiteur d'événements.
     *
     * @return EventDispatcherInterface
     */
    public function event(): EventDispatcherInterface
    {
        if ($this->eventDispatcher === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(EventDispatcherInterface::class)) {
                $this->eventDispatcher = $container->get(EventDispatcherInterface::class);
            } else {
                try {
                    $this->eventDispatcher = EventDispatcher::getInstance();
                } catch(RuntimeException $e) {
                    $this->eventDispatcher = new EventDispatcher();
                }
            }
        }

        return $this->eventDispatcher;
    }

    /**
     * Définition du gestionnaire de events.
     *
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return static
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): EventProxy
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }
}