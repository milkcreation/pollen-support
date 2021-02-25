<?php

declare(strict_types=1);

namespace Pollen\Support\Concerns;

use Pollen\Support\ParamsBag;
use InvalidArgumentException;

trait ParamsBagAwareTrait
{
    /**
     * Instance du gestionnaire de paramètres
     * @var ParamsBag|null
     */
    protected $paramsBag;

    /**
     * Liste des paramètres par défaut.
     *
     * @return array
     */
    public function defaultParams(): array
    {
        return [];
    }

    /**
     * Définition|Récupération|Instance des paramètres de configuration.
     *
     * @param array|string|null $key
     * @param mixed $default
     *
     * @return string|int|array|mixed|ParamsBag
     *
     * @throws InvalidArgumentException
     */
    public function params($key = null, $default = null)
    {
        if (!$this->paramsBag instanceof ParamsBag) {
            $this->paramsBag = ParamsBag::createFromAttrs($this->defaultParams());
        }

        if (is_null($key)) {
            return $this->paramsBag;
        }

        if (is_string($key)) {
            return $this->paramsBag->get($key, $default);
        }

        if (is_array($key)) {
            return $this->paramsBag->set($key);
        }

        throw new InvalidArgumentException('Invalid ParamsBag passed method arguments');
    }

    /**
     * Traitement de la liste des paramètres.
     *
     * @return static
     */
    public function parseParams(): self
    {
        return $this;
    }

    /**
     * Définition de la liste des paramètres.
     *
     * @param array $params
     *
     * @return static
     */
    public function setParams(array $params): self
    {
        $this->params($params);

        return $this;
    }
}