<?php

declare(strict_types=1);

namespace Keven\Stream\Trait;

use Keven\Stream\Stream;

trait ReadableTrait
{
    public function isEOF(): bool
    {
        return feof($this->resource);
    }

    public function hasMoreContent(): bool
    {
        return !feof($this->resource);
    }

    public function readChar(): string|false
    {
        return fgetc($this->resource);
    }

    public function readString(?int $length = null): string|false
    {
        return fgets($this->resource, $length);
    }

    /**
     * Return a line from the stream
     *
     * @see https://www.php.net/splfileobject#splfileobject.constants
     *
     * @param int $flags Combination of SplFileObject::DROP_NEW_LINE and SplFileObject::SKIP_EMPTY
     */
    public function readLine(int $flags = Stream::DROP_NEW_LINE): ?string
    {
        $line = fgets($this->resource);

        if (false === $line) {
            return null;
        }

        if ($flags & Stream::DROP_NEW_LINE) {
            $line = mb_substr($line, 0, -1);
        }

        if ($flags & Stream::SKIP_EMPTY & !trim($line)) {
            return null;
        }

        return $line;
    }

    /**
     * Read all lines from the stream
     *
     * @see https://www.php.net/splfileobject#splfileobject.constants
     *
     * @param int $flags Combination of SplFileObject::DROP_NEW_LINE and SplFileObject::SKIP_EMPTY
     *
     * @return iterable string[]
     */
    public function readLines(int $flags = Stream::DROP_NEW_LINE): iterable
    {
        while (!feof($this->resource)) {
            $line = fgets($this->resource);

            if (false === $line) {
                return;
            }

            if ($flags & Stream::DROP_NEW_LINE) {
                $line = mb_substr($line, 0, -1);
            }

            if ($flags & Stream::SKIP_EMPTY & !trim($line)) {
                continue;
            }

            yield $line;
        }
    }
}
