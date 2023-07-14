<?php

declare(strict_types=1);

namespace Keven\Stream\Api;

interface Seekable
{
    public function getPosition(): int|false;
    public function rewind(): self;
    public function moveBy(int $offset): self;
    public function moveTo(int $offset): self;
}
