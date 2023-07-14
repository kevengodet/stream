<?php

declare(strict_types=1);

namespace Keven\Stream;

/**
 * @todo Handle option 'e' (close-on-exec)
 */
final class Mode
{
    private const
        // Readable stream
        READ                =  1,

        // Writable stream
        WRITE               =  2,

        // Create the file if it does not already exists
        CREATE              =  4,

        // Move the pointer to the end of the stream (instead of the beginning by default)
        POINTER_END         =  8,

        // Truncate the content of the file
        TRUNCATE            = 16,

        // Text translation mode (instead of binary mode by default)
        TEXT                = 32,

        // Overwrite already existing file
        OVERWRITE           = 64;

    private static $modes = [
        'r'  => self::READ,
        'r+' => self::READ | self::WRITE,
        'w'  =>              self::WRITE | self::CREATE | self::TRUNCATE | self::OVERWRITE,
        'w+' => self::READ | self::WRITE | self::CREATE | self::TRUNCATE | self::OVERWRITE,
        'a'  =>              self::WRITE | self::CREATE                                    | self::POINTER_END,
        'a+' => self::READ | self::WRITE | self::CREATE                                    | self::POINTER_END,
        'x'  =>              self::WRITE | self::CREATE,
        'x+' => self::READ | self::WRITE | self::CREATE,
        'c'  =>              self::WRITE | self::CREATE                  | self::OVERWRITE,
        'c+' => self::READ | self::WRITE | self::CREATE                  | self::OVERWRITE,
    ];

    private string $translation;
    private int $mode;

    public function __construct(string $mode)
    {
        $isText = false !== strpos($mode, 't');
        $isBinary = false !== strpos($mode, 'b');

        if ($isText && $isBinary) {
            throw new \DomainException('Cannot have text and binary mode at the same time.');
        }

        // Binary by default
        $this->translation = $isText ? 't' : 'b';
        $modeWithoutTranslation = str_replace(array('t', 'b'), '', $mode);

        // Put the + sign at the end of the mode
        // if (str_ends_with($modeWithoutTranslation, '+')) {
        //     $normalizedMode = substr($modeWithoutTranslation, 0, -1);
        // } else {
            $normalizedMode = $modeWithoutTranslation;
        // }

        if (!isset(self::$modes[$normalizedMode])) {
            throw new \DomainException("Unknown mode '$mode'");
        }
var_dump($normalizedMode);
        $this->mode = self::$modes[$normalizedMode];
    }

    /**
     * @param resource $stream
     * @return Mode
     */
    public static function fromStream($stream)
    {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException;
        }

        if (get_resource_type($stream) !== 'stream') {
            throw new \InvalidArgumentException;
        }

        $meta = stream_get_meta_data($stream);
print_r($meta);
        return new Mode($meta['mode']);
    }

    public function isReadable(): bool
    {
        return (bool) ($this->mode & self::READ);
    }

    public function isWritable(): bool
    {
        return (bool) ($this->mode & self::WRITE);
    }

    public function isBinary(): bool
    {
        return !$this->isText();

    }

    public function isText(): bool
    {
        return (bool) ($this->mode & self::TEXT);
    }

    public function isCreatable(): bool
    {
        return (bool) ($this->mode & self::CREATE);
    }

    public function isOverwritable(): bool
    {
        return (bool) ($this->mode & self::OVERWRITE);
    }

    public function isTruncatable(): bool
    {
        return (bool) ($this->mode & self::TRUNCATE);
    }

    public function isPointerAtTheBeginning(): bool
    {
        return !$this->isPointerAtTheEnd();
    }

    public function isPointerAtTheEnd(): bool
    {
        return (bool) ($this->mode & self::POINTER_END);
    }

    public function __toString(): string
    {
        $mode = array_search($this->mode, self::$modes, true);

        if ($this->translation === 't') {
            $mode .= 't';
        }

        return  $mode;
    }
}
