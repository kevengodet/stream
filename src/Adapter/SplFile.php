<?php

declare(strict_types=1);

namespace Keven\Stream\Adapter;

final class SplFile extends FilePath implements AdapterInterface
{
    public function supports($source): bool
    {
        return $source instanceof \SplFileInfo;
    }

    /**
     * @return resource "stream"
     */
    public function createResource($source)
    {
        return $this->createResourceFromFilePath($source->getRealPath());
    }
}
