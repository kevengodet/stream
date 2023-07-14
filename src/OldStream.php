<?php

declare(strict_types=1);

namespace Keven\Stream;

use Keven\Stream\Api\Readable;
use Keven\Stream\Api\Seekable;
use Keven\Stream\Api\Writable;
use Keven\Stream\Exception\CannotOpenStream;
use Keven\Stream\Exception\UnknownSource;
use Keven\StreamMode\Mode;

final class OldStream implements Readable, Writable, Seekable
{


    public function isSeekable(): bool
    {
        $meta = stream_get_meta_data($this->resource);

        return isset($meta['seekable']) && true === $meta['seekable'];
    }

    public function isWritable(): bool
    {
        return $this->mode->isWritable();
    }

    public function isReadable(): bool
    {
        return $this->mode->isReadable();
    }
}

// class Stream
// {
//     /**
//      * @param string|resource|\SplFileInfo|\SplFileOjbect $source
//      */
//     static function create($source): self {}
//     function read(): string {}
//     function write(string $data): string {}
//     function seek(int $offset): void {}
//     function isWritable(): bool {}
//     function isSeekable(): bool {}
//     function isReadable(): bool {}
// }

class Stream
{
    /**
     * @param string|resource|\SplFileInfo|\SplFileOjbect $source
     */
    static function create($source): Readable|Writable|Seekable
    {
        // Depending on the source:
        return new class() implements Readable, Writable {};
        return new class() implements Readable {};
        return new class() implements Readable, Writable, Seekable {};
        // ...
    }
}

// interface Readable
// {
//     function read(): string {}
// }

// interface Writable
// {
//     function write(string $data): string {}
// }

// interface Seekable
// {
//     function seek(int $offset): void {}
// }


// function foo(Readable&Writable $stream)
// {
//     $stream->read();
//     $stream->write('foo');
//     // ...
// }
