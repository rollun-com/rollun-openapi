<?php
declare(strict_types=1);

namespace OpenAPI\Server\Attribute;

use Articus\PathHandler\Exception;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class Transfer
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Transfer extends \Articus\PathHandler\Attribute\Transfer
{
    /**
     * @inheritDoc
     */
    public function __invoke(Request $request): Request
    {
        // get classname
        $className = $this->options->getType();

        if (substr($className, -2) != '[]') {
            return parent::__invoke($request);
        }

        // prepare class name
        $className = str_replace('[]', '', $className);

        // get requested data
        $data = $this->getData($request);

        $error = [];
        if (!isset($data[0])) {
            $error[] = "Array of '$className' expected";
        }

        if (empty($error)) {
            $objects = [];
            foreach ($data as $item) {
                $object = new $className();
                $error = array_merge($error, $this->dtService->transfer($item, $object));
                $objects[] = $object;
            }
        }

        if (empty($error)) {
            $request = $request->withAttribute($this->options->getObjectAttr(), $objects);
        } elseif (empty($this->options->getErrorAttr())) {
            throw new Exception\UnprocessableEntity($error);
        } else {
            $request = $request->withAttribute($this->options->getErrorAttr(), $error);
        }

        return $request;
    }
}