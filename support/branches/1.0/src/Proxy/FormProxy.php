<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Form\FormInterface;
use Pollen\Form\FormManager;
use Pollen\Form\FormManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\FormProxyInterface
 */
trait FormProxy
{
    /**
     * Instance du gestionnaire de formulaires.
     * @var FormManagerInterface
     */
    private $formManager;

    /**
     * Instance du gestionnaire de formulaires|Instance d'un formulaire.
     *
     * @param string|null $alias
     *
     * @return FormManagerInterface|FormInterface
     */
    public function form(?string $alias = null)
    {
        if ($this->formManager === null) {
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(FormManagerInterface::class)) {
                $this->formManager = $container->get(FormManagerInterface::class);
            } else {
                try {
                    $this->formManager = FormManager::getInstance();
                } catch(RuntimeException $e) {
                    $this->formManager = new FormManager();
                }
            }
        }

        return $alias === null ? $this->formManager: $this->formManager->get($alias);
    }

    /**
     * Définition du gestionnaire de formulaires.
     *
     * @param FormManagerInterface $formManager
     *
     * @return static
     */
    public function setFormManager(FormManagerInterface $formManager): self
    {
        $this->formManager = $formManager;

        return $this;
    }
}