<?php

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Anonymize
{
    public string $type;

    public function __construct(string $type = "string")
    {
        $this->type = $type;
    }
}
