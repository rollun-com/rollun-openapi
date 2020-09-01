<?php
declare(strict_types=1);

namespace Tasks\OpenAPI\Server\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Delete
{
    /**
     * Is deleted ?
     * @DTA\Data(field="isDeleted")
     * @DTA\Validator(name="Type", options={"type":"bool"})
     * @var bool
     */
    public $is_deleted;
}