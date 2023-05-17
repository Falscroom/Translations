<?php

declare(strict_types=1);

namespace Translation\Entity;

use Stringable;

class Translation implements Stringable
{
    private array $comments = [];

    public function __construct(
        private string $key,
        private string $value
    ) {
    }

    public function addComments(array $comments): self
    {
        $this->comments = array_merge($this->comments, $comments);

        return $this;
    }

    public static function compare(self $a, self $b): int
    {
        return $a->key <=> $b->key;
    }

    public function __toString(): string
    {
        return implode('', $this->comments)
            . $this->key
            . '='
            . $this->value
        ;
    }
}