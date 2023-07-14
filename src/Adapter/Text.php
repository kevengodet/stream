<?php

declare(strict_types=1);

namespace Keven\Stream\Adapter;

final class Text implements AdapterInterface
{
    public function supports($source): bool
    {
        return is_string($source);
    }

    /**
     * @return resource "stream" resource
     */
    public function createResource($source)
    {
        $stream = fopen('php://memory','r+');
        fwrite($stream, $source);
        rewind($stream);

        return $stream;
    }
}
