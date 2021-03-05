<?php

declare(strict_types=1);

namespace Pollen\Support\Concerns;

use Pollen\Support\MessagesBag;

interface MessagesBagAwareTraitInterface
{
    /**
     * Définition d'un message|Instance du gestionnaire de message.
     *
     * @param string|null $message
     * @param string|int $level
     * @param mixed $datas
     *
     * @return string|MessagesBag
     */
    public function messages(?string $message = null, $level = MessagesBag::ERROR, array $datas = []);

    /**
     * Définition de l'instance du gestionnaire de messages.
     *
     * @param MessagesBag $messagesBag
     *
     * @return static
     */
    public function setMessagesBag(MessagesBag $messagesBag): self;
}