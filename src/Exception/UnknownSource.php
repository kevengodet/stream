<?php

declare(strict_types=1);

namespace Keven\Stream\Exception;

final class UnknownSource extends \InvalidArgumentException implements TextStreamExceptionInterface
{
    public static function create(string $name, string $reason = null): self
    {
        return new self("Unknown stream '$name'. $reason");
    }
}
