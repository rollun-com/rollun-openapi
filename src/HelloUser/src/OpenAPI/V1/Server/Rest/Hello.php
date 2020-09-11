<?php

namespace HelloUser\OpenAPI\V1\Server\Rest;

use OpenAPI\Server\Rest\BaseAbstract;
use rollun\Callables\Task\Result;
use rollun\Callables\Task\ResultInterface;
use rollun\dic\InsideConstruct;

/**
 * Class Hello
 */
class Hello extends BaseAbstract
{
    /**
     * @var User
     */
    protected $userObject;

    /**
     * Hello constructor.
     *
     * @param User|null $userObject
     *
     * @throws \ReflectionException
     */
    public function __construct(User $userObject = null)
    {
        InsideConstruct::init(['userObject' => User::class]);
    }

    /**
     * @inheritDoc
     */
    public function getById($id): ResultInterface
    {
        // get user
        $user = $this->userObject->getById($id)->toArrayForDto();

        return new Result(['message' => "Hello, {$user['data']['name']}!"]);
    }
}
