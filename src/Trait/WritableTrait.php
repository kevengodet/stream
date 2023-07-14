<?php

declare(strict_types=1);

namespace Keven\Stream\Trait;

trait WritableTrait
{
    public function write(string $data): self
    {
        fwrite($this->resource, $data);

        return $this;
    }

    public function truncate(int $size): self
    {
        ftruncate($this->resource, $size);

        return $this;
    }
}
