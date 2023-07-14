<?php

declare(strict_types=1);

namespace Keven\Stream\Trait;

trait SeekableTrait
{
    public function getPosition(): int|false
    {
        return ftell($this->resource);
    }

    public function rewind(): self
    {
        rewind($this->resource);

        return $this;
    }

    /**
     * Relative cursor seek
     */
    public function moveBy(int $offset): self
    {
        fseek($this->resource, $offset, SEEK_CUR);

        return $this;
    }

    /**
     * Absolute cursor seek
     * 
     * @param int $offset If $offset > 0 : Start from the start
     *                    If $offset < 0 : Start from the end
     */
    public function moveTo(int $offset): self
    {
        if ($offset > 0) {
            fseek($this->resource, $offset, SEEK_CUR);
        } else {
            fseek($this->resource, $offset, SEEK_END);
        }

        return $this;
    }
}
