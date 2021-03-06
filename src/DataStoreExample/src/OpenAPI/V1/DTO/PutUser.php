<?php
declare(strict_types=1);

namespace DataStoreExample\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class PutUser
{
    /**
     * @DTA\Data(field="id", nullable=true)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $id;
    /**
     * @DTA\Data(field="name")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $name;
    /**
     * @DTA\Data(field="surname")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $surname;
    /**
     * @DTA\Data(field="active")
     * @DTA\Validator(name="Type", options={"type":"bool"})
     * @var bool
     */
    public $active;
}
