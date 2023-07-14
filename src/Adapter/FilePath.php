<?php

declare(strict_types=1);

namespace Keven\Stream\Adapter;

use Keven\Stream\Exception\CannotOpenStream;

class FilePath implements AdapterInterface
{
    public function supports($source): bool
    {
        return is_string($source) && file_exists($source);
    }

    /**
     * @return resource "stream" resource
     */
    public function createResource($source)
    {
        return $this->createResourceFromFilePath($source);
    }

    /**
     * @param string $filePath
     * @return resource
     */
    protected function createResourceFromFilePath(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw CannotOpenStream::create($filePath, 'File does not exist.');
        }

        if (!is_readable($filePath)) {
            throw CannotOpenStream::create($filePath, 'File is not readable.');
        }

        $resource = is_writable($filePath) ?
                        fopen($filePath, 'w+') :
                        fopen($filePath, 'r');

        if (false === $resource) {
            $e = error_get_last();
            throw CannotOpenStream::create($filePath, $e ? $e['message'] : null);
        }

        return $resource;
    }
}
