<?php

declare(strict_types=1);

namespace Keven\Stream\Api;

interface Writable
{
    public function write(string $data): self;
    public function truncate(int $size): self;
}
