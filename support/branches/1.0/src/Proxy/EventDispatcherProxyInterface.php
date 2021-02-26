<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Event\EventDispatcherInterface;

interface EventDispatcherProxyInterface
{
    /**
     * Instance du répartiteur d'événements.
     *
     * @return EventDispatcherInterface
     */
    public function eventDispatcher(): EventDispatcherInterface;

    /**
     * Définition du gestionnaire de events.
     *
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return EventDispatcherProxy|static
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): EventDispatcherProxy;
}