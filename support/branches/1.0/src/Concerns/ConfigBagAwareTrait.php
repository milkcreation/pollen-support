<?php

declare(strict_types=1);

namespace Pollen\Support\Concerns;

use Pollen\Support\ParamsBag;
use InvalidArgumentException;

/**
 * @see \Pollen\Support\Concerns\ConfigBagAwareTraitInterface
 */
trait ConfigBagAwareTrait
{
    /**
     * Instance du gestionnaire de paramètres de configuration.
     * @var ParamsBag|null
     */
    private $configBag;

    /**
     * Liste des paramètres de configuration par défaut.
     *
     * @return array
     */
    public function defaultConfig(): array
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
    public function config($key = null, $default = null)
    {
        if (!$this->configBag instanceof ParamsBag) {
            $this->configBag = ParamsBag::createFromAttrs($this->defaultConfig());
        }

        if (is_null($key)) {
            return $this->configBag;
        }

        if (is_string($key)) {
            return $this->configBag->get($key, $default);
        }

        if (is_array($key)) {
            return $this->configBag->set($key);
        }

        throw new InvalidArgumentException('Invalid ConfigBag passed method arguments');
    }

    /**
     * Traitement de la liste des paramètres de configuration.
     *
     * @return void
     */
    public function parseConfig(): void
    {
    }

    /**
     * Définition de la liste des paramètres de configuration.
     *
     * @param array $params
     *
     * @return static
     */
    public function setConfig(array $params): ConfigBagAwareTrait
    {
        $this->config($params);

        return $this;
    }
}