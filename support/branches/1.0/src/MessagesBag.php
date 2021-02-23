<?php

declare(strict_types=1);

namespace Pollen\Support;

use Exception;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * @see https://fr.wikipedia.org/wiki/Syslog
 */
class MessagesBag implements MessagesBagInterface
{
    /**
     * Niveau des messages de débogage.
     * @var int
     */
    public const DEBUG = 100;

    /**
     * Niveau des messages d'information.
     * @var int
     */
    public const INFO = 200;

    /**
     * Niveau des messages de succès.
     * @var int
     */
    public const SUCCESS = 225;

    /**
     * Niveau des messages de notification standard.
     * @var int
     */
    public const NOTICE = 250;

    /**
     * Niveau des messages d'avertissement.
     * @var int
     */
    public const WARNING = 300;

    /**
     * Niveau des messages d'erreur.
     * @var int
     */
    public const ERROR = 400;

    /**
     * Niveau des messages d'erreur critique.
     * @var int
     */
    public const CRITICAL = 500;

    /**
     * Niveau des messages d'alerte notifiant une intervention immédiate.
     * @var int
     */
    public const ALERT = 550;

    /**
     * Niveau des messages d'urgence notifiant le système inutilisable.
     * @var int
     */
    public const EMERGENCY = 600;

    /**
     * liste des niveaux de messages supportés.
     *
     * @var array<int, string> $levels Logging levels
     */
    protected static $levels = [
        self::DEBUG     => 'DEBUG',
        self::INFO      => 'INFO',
        self::SUCCESS   => 'SUCCESS',
        self::NOTICE    => 'NOTICE',
        self::WARNING   => 'WARNING',
        self::ERROR     => 'ERROR',
        self::CRITICAL  => 'CRITICAL',
        self::ALERT     => 'ALERT',
        self::EMERGENCY => 'EMERGENCY',
    ];

    /**
     * Niveau de traitement des messages.
     * @var int
     */
    protected $handlingLevel = self::DEBUG;

    /**
     * Liste des enregistrements
     * @var ParamsBag
     */
    private $records;

    /**
     * Liste des enregistrement collectés (dédiés à la recherche).
     * @var Collection|null
     */
    private $collectedRecords;

    /**
     * Récupération d'un élément d'itération.
     *
     * @param int $offset
     *
     * @return array
     */
    public function __get(int $offset): array
    {
        return $this->offsetGet($offset);
    }

    /**
     * Définition d'un élément d'itération.
     *
     * @param int $offset
     * @param mixed $value
     *
     * @return void
     */
    public function __set(int $offset, $value): void
    {
    }

    /**
     * Vérification d'existence d'un élément d'itération.
     *
     * @param int $offset
     *
     * @return bool
     */
    public function __isset(int $offset): bool
    {
        return $this->offsetExists($offset);
    }

    /**
     * Suppression d'un élément d'itération.
     *
     * @param int $offset
     *
     * @return void
     */
    public function __unset(int $offset): void
    {
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return isset($this->collectedRecords()[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): array
    {
        return $this->collectedRecords()[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
    }

    /**
     * Récupération de la liste de tous les niveaux de messages supportés.
     *
     * @return array<string, int>
     */
    public static function getLevels(): array
    {
        return array_flip(static::$levels);
    }

    /**
     * Récupération de l'intitulé de qualification d'un niveau de traitement.
     *
     * @param int $level
     *
     * @return string
     */
    public static function getLevelName(int $level): string
    {
        if (!isset(static::$levels[$level])) {
            throw new InvalidArgumentException(
                sprintf(
                    'Level [%s] is not defined, use one of: %s',
                    $level,
                    implode(', ', array_keys(static::$levels))
                )
            );
        }

        return static::$levels[$level];
    }

    /**
     * Conversion d'un niveau de journalisation au format PSR-3.
     * {@internal Hack en vue de la validation du niveau success}
     *
     * @param string|int $level
     *
     * @return int
     *
     * @throws InvalidArgumentException If level is not defined
     */
    public static function toMessageBagLevel($level): int
    {
        if (is_string($level)) {
            if (is_numeric($level)) {
                return (int)$level;
            }

            $upper = strtr($level, 'abcdefgilmnorstuwy', 'ABCDEFGILMNORSTUWY');
            if (defined(__CLASS__ . '::' . $upper)) {
                return constant(__CLASS__ . '::' . $upper);
            }

            throw new InvalidArgumentException(
                'Level "' . $level . '" is not defined, use one of: ' . implode(', ', array_keys(static::$levels))
            );
        }

        if (!is_int($level)) {
            throw new InvalidArgumentException(
                'Level "' . var_export($level, true) . '" is not defined, use one of: ' . implode(
                    ', ',
                    array_keys(static::$levels)
                )
            );
        }

        return $level;
    }

    /**
     * Récupération de la liste des enregistrements collectés.
     *
     * @return Collection
     */
    protected function collectedRecords(): Collection
    {
        if ($this->collectedRecords === null) {
            $this->collectedRecords = new Collection();
        }

        return $this->collectedRecords;
    }

    /**
     * Définition|Récupération|Instance des enregistrement.
     *
     * @param array|string|null $key
     * @param mixed $default
     *
     * @return string|int|array|mixed|ParamsBag
     */
    protected function records($key = null, $default = null)
    {
        if (!$this->records instanceof ParamsBag) {
            $this->records = new ParamsBag();
        }

        if (is_null($key)) {
            return $this->records;
        }

        if (is_string($key)) {
            return $this->records->get($key, $default);
        }

        if (is_array($key)) {
            return $this->records->set($key);
        }

        throw new InvalidArgumentException('Invalid ParamsBag passed method arguments');
    }

    /**
     * @inheritDoc
     */
    public function addRecord(int $level, string $message = '', ?array $context = null, ?string $code = null): string
    {
        $code = $code ?: Str::random();

        $this->records(
            [
                "codes.{$level}"            => $code,
                "messages.{$level}.{$code}" => $message,
                "contexts.{$level}.{$code}" => $context,
            ]
        );

        $level_name = self::getLevelName($level);

        $this->collectedRecords()->add(compact('code', 'context', 'level', 'level_name', 'message'));

        return $code;
    }

    /**
     * @inheritDoc
     */
    public function allCodes(?int $level = null): array
    {
        return $level === null ? $this->records('codes', []) : $this->getLevelCodes($level);
    }

    /**
     * @inheritDoc
     */
    public function allContexts(?int $level = null): array
    {
        return $level === null ? $this->records('contexts', []) : $this->getLevelContexts($level);
    }

    /**
     * @inheritDoc
     */
    public function allMessages(?int $level = null): array
    {
        return $level === null ? $this->records('messages', []) : $this->getLevelMessages($level);
    }

    /**
     * @inheritDoc
     */
    public function count(?int $level = null): int
    {
        $codes = $this->allCodes($level);

        return count($codes);
    }

    /**
     * @inheritDoc
     */
    public function exists(?int $level = null): bool
    {
        return $this->count($level) > 0;
    }

    /**
     * @inheritDoc
     */
    public function existsForContext(array $context, ?int $level = null): bool
    {
        return (bool)$this->collectedRecords()->first(
            function ($item) use ($context, $level) {
                $sameLevel = ($level !== null) ? ($level === $item['level']) : true;

                return $sameLevel && @array_intersect($item['context'] ?? [], $context);
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function fetch(?int $level = null, $code = null): array
    {
        $items = [];

        if ($level === null) {
            foreach (self::getLevels() as $l) {
                $items += $this->fetch($l);
            }
            return $items;
        }

        if (!$this->isHandling($level)) {
            return [];
        }

        if ($code === null) {
            foreach ($this->getLevelCodes($level) as $c) {
                $message = $this->getLevelMessages($level, $c);
                $context = $this->getLevelContexts($level, $c);
                $items[] = array_merge(['code' => $c], compact('context', 'level', 'message'));
            }
        } else {
            $message = $this->getLevelMessages($level, $code);
            $context = $this->getLevelContexts($level, $code);
            $items[] = compact('code', 'context', 'level', 'message');
        }

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function fetchMessages(array $levelsMap = []): array
    {
        if (empty($levelsMap)) {
            $levelsMap = self::getLevels();
        }

        $messages = [];
        foreach ($levelsMap as $level) {
            $level = self::toMessageBagLevel($level);
            $name = self::getLevelName($level);

            if ($this->isHandling($level) && ($levelMessages = $this->getLevelMessages($level))) {
                $messages[$name] = $levelMessages;
            }
        }

        return $messages;
    }

    /**
     * @inheritDoc
     */
    public function flush(?int $level = null): MessagesBagInterface
    {
        $this->records()->forget('codes' . ($level ? ".{$level}" : null));
        $this->records()->forget('context' . ($level ? ".{$level}" : null));
        $this->records()->forget('messages' . ($level ? ".{$level}" : null));

        if (!$level === null) {
            $this->collectedRecords = null;
        } else {
            $this->collectedRecords = $this->collectedRecords()->filter(
                function ($item) use ($level) {
                    return $item['level'] !== $level;
                }
            );
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getForContext(array $context, ?int $level = null): array
    {
        return $this->collectedRecords()->filter(
            function ($item) use ($context, $level) {
                $sameLevel = ($level !== null) ? ($level === $item['level']) : true;

                return $sameLevel && @array_intersect($item['context'] ?? [], $context);
            }
        )->all();
    }

    /**
     * @inheritDoc
     */
    public function getLevelCodes(int $level): array
    {
        return $this->records("codes.{$level}", []);
    }

    /**
     * @inheritDoc
     */
    public function getLevelContexts(int $level, $code = null): array
    {
        if ($code === null) {
            return $this->records("contexts.{$level}", []);
        }

        return $this->records("contexts.{$level}.{$code}", []);
    }

    /**
     * @inheritDoc
     */
    public function getLevelMessages(int $level, $code = null): array
    {
        if ($code === null) {
            return $this->records("messages.{$level}", []);
        }

        return $this->records("messages.{$level}.{$code}", []);
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->collectedRecords();
    }

    /**
     * @inheritDoc
     */
    public function hasLevel(int $level): bool
    {
        return in_array($level, self::getLevels(), true) && $this->isHandling($level);
    }

    /**
     * @inheritDoc
     */
    public function hasLevelName(string $levelName): bool
    {
        $level = self::toMessageBagLevel($levelName);

        return $this->hasLevel($level);
    }

    /**
     * @inheritDoc
     */
    public function isHandling(int $level): bool
    {
        return $level >= $this->handlingLevel;
    }

    /**
     * @inheritDoc
     */
    public function json(): string
    {
        try {
            return json_encode($this->fetchMessages(), JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->fetchMessages();
    }

    /**
     * @inheritDoc
     */
    public function setHandlingLevel(int $level): MessagesBagInterface
    {
        $this->handlingLevel = $level;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function alert(string $message = '', ?array $context = null, ?string $code = null): string
    {
        return $this->addRecord(self::ALERT, $message, $context, $code);
    }

    /**
     * @inheritDoc
     */
    public function critical(string $message = '', ?array $context = null, ?string $code = null): string
    {
        return $this->addRecord(self::CRITICAL, $message, $context, $code);
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message = '', ?array $context = null, ?string $code = null): string
    {
        return $this->addRecord(self::DEBUG, $message, $context, $code);
    }

    /**
     * @inheritDoc
     */
    public function emergency(string $message = '', ?array $context = null, ?string $code = null): string
    {
        return $this->addRecord(self::EMERGENCY, $message, $context, $code);
    }

    /**
     * @inheritDoc
     */
    public function error(string $message = '', ?array $context = null, ?string $code = null): string
    {
        return $this->addRecord(self::ERROR, $message, $context, $code);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message = '', ?array $context = null, ?string $code = null): string
    {
        return $this->addRecord(self::INFO, $message, $context, $code);
    }

    /**
     * @inheritDoc
     */
    public function log($level, string $message = '', ?array $context = null, ?string $code = null): string
    {
        $level = static::toMessageBagLevel($level);

        return $this->addRecord($level, $message, $context, $code);
    }

    /**
     * @inheritDoc
     */
    public function notice(string $message = '', ?array $context = null, ?string $code = null): string
    {
        return $this->addRecord(self::NOTICE, $message, $context, $code);
    }

    /**
     * @inheritDoc
     */
    public function success(string $message = '', ?array $context = null, ?string $code = null): string
    {
        return $this->addRecord(self::SUCCESS, $message, $context, $code);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message = '', ?array $context = null, ?string $code = null): string
    {
        return $this->addRecord(static::WARNING, $message, $context, $code);
    }
}