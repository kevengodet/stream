<?php

declare(strict_types=1);

namespace Keven\Stream\Adapter;

interface AdapterInterface
{
    public function supports($source): bool;

    /**
     * @return resource "stream" resource
     */
    public function createResource($source);
}
