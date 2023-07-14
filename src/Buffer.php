<?php

declare(strict_types=1);

namespace Keven\Stream;

use Keven\Stream\Api\Readable;
use Keven\Stream\Api\Seekable;
use Keven\Stream\Api\Writable;

/**
 * Add seekable capability to a non-seekable stream
 */
final class Buffer implements Readable, Writable, Seekable
{
    private Stream $buffer;

    public function __construct(private Stream $stream)
    {
        $this->buffer = new Stream(fopen('php://memory', 'w+'));
    }

    public function readChar(): string|false;
    public function readString(?int $length = null): string|false;
    public function isEOF(): bool;
    public function hasMoreContent(): bool;
    public function readLine(int $flags = 0): ?string;
    public function readLines(int $flags = 0): iterable;

    public function getPosition(): int|false;
    public function rewind(): self;
    public function moveBy(int $offset): self;
    public function moveTo(int $offset): self;

    public function write(string $data): self;
    public function truncate(int $size): self;
}
