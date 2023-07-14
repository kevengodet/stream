<?php

declare(strict_types=1);

namespace Keven\Stream\Exception;

final class CannotOpenStream extends \InvalidArgumentException implements TextStreamExceptionInterface
{
    public static function create(string $stream, string $message = null): self
    {
        return new self("Cannot open stream '$stream'. $message");
    }
}
