<?php
declare(strict_types=1);

namespace Tasks\OpenAPI\Server\V1\Handler\FileSummary;

use Articus\PathHandler\Annotation as PHA;
use Articus\PathHandler\Consumer as PHConsumer;
use Articus\PathHandler\Producer as PHProducer;
use Articus\PathHandler\Attribute as PHAttribute;
use Articus\PathHandler\Exception as PHException;
use OpenAPI\Server\Producer\Transfer;
use Psr\Http\Message\ServerRequestInterface;
use rollun\dic\InsideConstruct;

/**
 * @PHA\Route(pattern="/task/{id}")
 */
class TaskId
{
    /**
     * @var object
     */
    protected $taskObject;

    /**
     * TaskId constructor.
     *
     * @param object|null $taskObject
     *
     * @throws \ReflectionException
     */
    public function __construct($taskObject = null)
    {
        InsideConstruct::init(['taskObject' => 'FileSummary']);
    }

    /**
     * @throws \ReflectionException
     */
    public function __wakeup()
    {
        InsideConstruct::initWakeup(['taskObject' => 'FileSummary']);
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return [];
    }

    /**
     * Delete task
     * @PHA\Delete()
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Tasks\OpenAPI\Server\V1\DTO\DeleteResult::class})
     *
     * @param ServerRequestInterface $request
     *
     * @return array
     * @throws \Exception
     */
    public function deleteById(ServerRequestInterface $request)
    {
        return $this->taskObject->deleteById((string)$request->getAttribute('id'))->toArrayForDto();
    }

    /**
     * Return concreted task info by id
     * @PHA\Get()
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Tasks\OpenAPI\Server\V1\DTO\TaskInfoResult::class})
     *
     * @param ServerRequestInterface $request
     *
     * @return array
     * @throws \Exception
     */
    public function getTaskInfoById(ServerRequestInterface $request)
    {
        return $this->taskObject->getTaskInfoById((string)$request->getAttribute('id'))->toArrayForDto();
    }
}