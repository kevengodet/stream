<?php

declare(strict_types=1);

namespace Keven\Stream\Exception;

final class InvalidStream extends \InvalidArgumentException implements TextStreamExceptionInterface
{
    public static function create(string $reason = null): self
    {
        return new self("The stream is invalid. $reason");
    }
}
