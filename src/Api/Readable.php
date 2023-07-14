<?php

declare(strict_types=1);

namespace Keven\Stream\Api;

interface Readable
{
    public function readChar(): string|false;
    public function readString(?int $length = null): string|false;
    public function isEOF(): bool;
    public function hasMoreContent(): bool;
    public function readLine(int $flags = 0): ?string;
    public function readLines(int $flags = 0): iterable;
}
