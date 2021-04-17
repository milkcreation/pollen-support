<?php

declare(strict_types=1);

namespace Pollen\Support\Proxy;

use Exception;
use Pollen\Support\Exception\ProxyInvalidArgumentException;
use Pollen\Support\StaticProxy;
use Pollen\Validation\Validator;
use Pollen\Validation\ValidatorInterface;
use RuntimeException;

/**
 * @see \Pollen\Support\Proxy\FieldProxyInterface
 */
trait ValidatorProxy
{
    /**
     * Instance du gestionnaire de validation.
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Instance du gestionnaire de validation|Validation.
     *
     * @param string|null $ruleName.
     * @param array|null $args
     *
     * @return ValidatorInterface
     */
    public function validator(?string $ruleName = null, ...$args): ValidatorInterface
    {
        if ($this->validator === null) {
            try {
                $this->validator = Validator::createOrExisting();
            } catch (RuntimeException $e) {
                $this->validator = StaticProxy::getProxyInstance(
                    ValidatorInterface::class,
                    Validator::class,
                    method_exists($this, 'getContainer') ? $this->getContainer() : null
                );
            }
        }

        if ($ruleName === null) {
            return $this->validator;
        }

        try {
            return $this->validator->$ruleName(...$args);
        } catch(Exception $e) {
            throw new ProxyInvalidArgumentException(sprintf('Validator Rule [%s] is unavailable', $ruleName));
        }
    }

    /**
     * Définition du gestionnaire de validation.
     *
     * @param ValidatorInterface $validator
     *
     * @return void
     */
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }
}