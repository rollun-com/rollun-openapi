<?php
declare(strict_types=1);

namespace DataStoreExample\OpenAPI\Server\V1\Rest;

use Articus\PathHandler\Exception\HttpCode;
use Articus\PathHandler\Exception\NotFound;
use OpenAPI\Server\Rest\BaseAbstract;
use rollun\Callables\Task\Result;
use rollun\Callables\Task\ResultInterface;
use rollun\datastore\DataStore\Interfaces\DataStoreInterface;
use rollun\datastore\Rql\RqlParser;
use rollun\datastore\Rql\RqlQuery;
use rollun\dic\InsideConstruct;
use Xiag\Rql\Parser\Node\LimitNode;
use Xiag\Rql\Parser\Node\SortNode;

/**
 * Class User
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class User extends BaseAbstract
{
    /**
     * @var DataStoreInterface
     */
    protected $dataStore;

    /**
     * User constructor.
     *
     * @param DataStoreInterface $dataStore
     *
     * @throws \ReflectionException
     */
    public function __construct(DataStoreInterface $dataStore = null)
    {
        InsideConstruct::init(['dataStore' => 'exampleUserDataStore']);
    }

    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\UserDELETEQueryData $queryData
     */
    public function delete($queryData = null): ResultInterface
    {
        $query = empty($queryData->rql) ? new RqlQuery() : RqlParser::rqlDecode($queryData->rql);

        $result = $this->dataStore->queriedDelete($query);
        if (empty($result)) {
            throw new HttpCode(404, "No records exists for such query");
        }

        return new Result(null);
    }

    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\UserGETQueryData $queryData
     */
    public function get($queryData = null): ResultInterface
    {
        $limit = empty($queryData->limit) ? 1000 : $queryData->limit;
        $offset = empty($queryData->offset) ? 0 : $queryData->offset;

        $sortBy = empty($queryData->sort_by) ? 'name' : $queryData->sort_by;
        $sortOrder = $queryData->sort_order == 'asc' ? 1 : -1;

        $query = empty($queryData->rql) ? new RqlQuery() : RqlParser::rqlDecode($queryData->rql);
        $query->setLimit(new LimitNode($limit, $offset));
        $query->setSort(new SortNode([$sortBy => $sortOrder]));

        // get result from dataStore
        $result = $this->dataStore->query($query);

        // prepare result fields types
        foreach ($result as $k => $row) {
            $result[$k] = $this->prepareResultFieldsTypes($row);
        }

        return new Result($result);
    }

    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\UserPATCHQueryData $queryData
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\User               $bodyData
     */
    public function patch($queryData, $bodyData): ResultInterface
    {
        $query = empty($queryData->rql) ? new RqlQuery() : RqlParser::rqlDecode($queryData->rql);

        $data = [];
        foreach ((array)$bodyData as $name => $value) {
            if ($value !== null) {
                $data[$name] = $value;
            }
        }

        $result = $this->dataStore->queriedUpdate($data, $query);

        return $this->getUpdateResult($result);
    }

    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\PostUser[] $bodyData
     */
    public function post($bodyData): ResultInterface
    {
        // prepare input data
        $inputData = [];
        foreach ($bodyData as $item) {
            $inputData[] = (array)$item;
        }

        $result = $this->dataStore->multiCreate($inputData);

        return $this->getUpdateResult($result);
    }


    /**
     * @inheritDoc
     */
    public function deleteById($id): ResultInterface
    {
        $result = $this->dataStore->delete($id);

        if (empty($result)) {
            throw new NotFound();
        }

        return new Result(null);
    }


    /**
     * @inheritDoc
     */
    public function getById($id): ResultInterface
    {
        $result = $this->dataStore->read($id);

        if (empty($result)) {
            throw new NotFound();
        }

        // prepare result fields types
        $result = $this->prepareResultFieldsTypes($result);

        return new Result($result);
    }


    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\User $bodyData
     */
    public function patchById($id, $bodyData): ResultInterface
    {
        $result = $this->dataStore->read($id);

        if (empty($result)) {
            throw new NotFound();
        }

        foreach ((array)$bodyData as $name => $value) {
            if ($value !== null && $name != 'id') {
                $result[$name] = $value;
            }
        }

        // prepare result fields types
        $result = $this->prepareResultFieldsTypes($result);

        $this->dataStore->rewrite($result);

        return new Result($result);
    }


    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\PutUser $bodyData
     */
    public function putById($id, $bodyData): ResultInterface
    {
        return $this->patchById($id, $bodyData);
    }

    /**
     * @param array $data
     *
     * @return ResultInterface
     * @throws \Exception
     */
    protected function getUpdateResult(array $data): ResultInterface
    {
        if (!empty($data)) {
            $ids = [];
            foreach ($data as $id) {
                $ids[] = "eq(id,$id)";
            }

            $queryData = new \DataStoreExample\OpenAPI\Server\V1\DTO\UserGETQueryData();
            $queryData->rql = "or(eq(id,nosuchid)," . implode(",", $ids) . ")";

            return $this->get($queryData);
        }

        return new Result(null);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function prepareResultFieldsTypes(array $data): array
    {
        $data['id'] = (string)$data['id'];
        $data['active'] = (bool)$data['active'];

        return $data;
    }
}