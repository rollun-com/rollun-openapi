<?php
declare(strict_types=1);

namespace DataStoreExample\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 * Query parameters for userPATCH
 */
class UserPATCHQueryData
{
    /**
     * @DTA\Data(field="rql", nullable=true)
     * @DTA\Strategy(name="QueryParameter", options={"type":"string"})
     * @DTA\Validator(name="QueryParameterType", options={"type":"string"})
     * @var string
     */
    public $rql;
}
