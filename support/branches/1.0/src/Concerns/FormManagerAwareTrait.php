<?php

declare(strict_types=1);

namespace Pollen\Support\Concerns;

use Pollen\Form\FormManager;
use Pollen\Form\FormManagerInterface;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

trait FormManagerAwareTrait
{
    /**
     * Instance du gestionnaire de formulaires.
     * @var FormManagerInterface
     */
    private $formManager;

    /**
     * Instance du gestionnaire de formulaires.
     *
     * @return FormManagerInterface
     */
    public function formManager(): FormManagerInterface
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

        return $this->formManager;
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