<?php
declare(strict_types=1);

namespace Task\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class PostFileSummary
{
    /**
     * N parameter
     * @DTA\Data(field="n", nullable=true)
     * @DTA\Validator(name="Type", options={"type":"int"})
     * @var int
     */
    public $n;
}
