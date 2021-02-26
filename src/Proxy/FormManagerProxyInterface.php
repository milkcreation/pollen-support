<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Pollen\Form\FormManagerInterface;

interface FormManagerProxyInterface
{
    /**
     * Instance du gestionnaire de formulaires.
     *
     * @return FormManagerInterface
     */
    public function formManager(): FormManagerInterface;

    /**
     * Définition du gestionnaire de formulaires.
     *
     * @param FormManagerInterface $formManager
     *
     * @return FormManagerProxy|static
     */
    public function setFormManager(FormManagerInterface $formManager): FormManagerProxy;
}