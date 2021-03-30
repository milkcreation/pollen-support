<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Log\LogManager;
use Pollen\Log\LogManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\LogProxyInterface
 */
trait LogProxy
{
    /**
     * Instance du gestionnaire de log.
     * @var LogManagerInterface
     */
    private $logManager;

    /**
     * Instance du gestionnaire de log|Enregistrement d'un message de log.
     *
     * @param string|int|null $level
     * @param string $message
     * @param array $context
     *
     * @return LogManagerInterface|bool
     */
    public function log($level = null, string $message = '', array $context = [])
    {
        if ($this->logManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(LogManagerInterface::class)) {
                $this->logManager = $container->get(LogManagerInterface::class);
            } else {
                try {
                    $this->logManager = LogManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->logManager = new LogManager();
                }
            }
        }

        if ($level !== null) {
            return $this->logManager;
        }

        return $this->logManager->addRecord($level, $message, $context);
    }

    /**
     * Définition du gestionnaire de log.
     *
     * @param LogManagerInterface $logManager
     *
     * @return void
     */
    public function setLogManager(LogManagerInterface $logManager): void
    {
        $this->logManager = $logManager;
    }
}