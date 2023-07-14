<?php

declare(strict_types=1);

namespace Keven\Stream;

use Keven\Stream\Api\Readable;
use Keven\Stream\Api\Seekable;
use Keven\Stream\Api\Writable;
use Keven\Stream\Exception\CannotOpenStream;
use Keven\Stream\Exception\InvalidStream;
use Keven\Stream\Exception\UnknownSource;
use Keven\Stream\Trait\BaseTrait;
use Keven\Stream\Trait\ReadableTrait;
use Keven\Stream\Trait\SeekableTrait;
use Keven\Stream\Trait\WritableTrait;

final class Stream
{
    public const
        DROP_NEW_LINE = \SplFileObject::DROP_NEW_LINE,
        SKIP_EMPTY    = \SplFileObject::SKIP_EMPTY
    ;

    public static function create($source): Readable|Writable|Seekable
    {
        if (is_resource($source)) {
            return self::instantiate($source);
        }

        if ($source instanceof \SplFileInfo) {
            return self::instantiate($source->getRealPath());
        }

        if (!is_string($source)) {
            throw UnknownSource::create(gettype($source), 'Accepted sources: string, file path, stream resource, SplFileInfo.');
        }

        if (file_exists($source)) {
            if (!is_readable($source)) {
                throw UnknownSource::create(gettype($source), 'File is not readable.');
            }

            return self::open($source);
        }

        $stream = fopen('php://memory','r+');
        fwrite($stream, $source);
        rewind($stream);

        return self::instantiate($stream);

    }

    /**
     * @see https://php.net/fopen
     */
    public static function open(string $filePath, string $mode = 'r', bool $use_include_path = false, $context = null): Readable|Writable|Seekable
    {
        $resource = fopen($filePath, $mode, $use_include_path, $context);

        if (false === $resource) {
            $e = error_get_last();
            throw CannotOpenStream::create($filePath, $e ? $e['message'] : null);
        }

        return self::instantiate($resource);
    }

    private static function instantiate($resource): Readable|Writable|Seekable
    {
        $mode = Mode::fromStream($resource);
        $meta = stream_get_meta_data($resource);

        $R = $mode->isReadable();
        $W = $mode->isWritable();
        $S = isset($meta['seekable']) && true === $meta['seekable'];

        if (!$R && !$W && !$S) {
            throw InvalidStream::create('It is neither writable, nor seekable, and not even readable.');
        }

        return match(true) {
             $R &&  $W &&  $S => new class($resource, $mode) implements Readable, Writable, Seekable {use BaseTrait, ReadableTrait, WritableTrait, SeekableTrait;},
             $R && !$W &&  $S => new class($resource, $mode) implements Readable,           Seekable {use BaseTrait, ReadableTrait,                SeekableTrait;},
             $R &&  $W && !$S => new class($resource, $mode) implements Readable, Writable           {use BaseTrait, ReadableTrait, WritableTrait               ;},
             $R && !$W && !$S => new class($resource, $mode) implements Readable                     {use BaseTrait, ReadableTrait                              ;},
            !$R &&  $W &&  $S => new class($resource, $mode) implements           Writable, Seekable {use BaseTrait,                WritableTrait, SeekableTrait;},
            !$R && !$W &&  $S => new class($resource, $mode) implements                     Seekable {use BaseTrait,                               SeekableTrait;},
            !$R &&  $W && !$S => new class($resource, $mode) implements           Writable           {use BaseTrait,                WritableTrait               ;},
        };
    }
}
