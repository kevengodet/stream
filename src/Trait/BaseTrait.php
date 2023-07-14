<?php

declare(strict_types=1);

namespace Keven\Stream\Trait;

use Keven\Stream\Exception\UnknownSource;
use Keven\Stream\Mode;

trait BaseTrait
{
    public function __construct(private $resource, private ?Mode $mode = null)
    {
        if (!is_resource($resource)) {
            throw UnknownSource::create(gettype($resource), 'Resource type is required.');
        }

        if (get_resource_type($resource) !== 'stream') {
            throw UnknownSource::create(get_resource_type($resource), "Resource of type 'stream' is required.");
        }

        $this->mode ??= Mode::fromStream($resource);
    }
}
