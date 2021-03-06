<?php
declare(strict_types=1);

namespace OpenAPI\Server\Validator;

use OpenAPI\Server\Strategy\QueryParameterArray;

/**
 * Class QueryParameterArrayType
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class QueryParameterArrayType extends QueryParameterType
{
    /**
     * @var null|string
     */
    protected $format;

    /**
     * @return string
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return self
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    protected function checkType($value): bool
    {
        $result = true;
        if (!\array_key_exists($this->format, QueryParameterArray::DELIMITER_MAP)) {
            throw new \InvalidArgumentException(\sprintf('Can not check for format %s.', $this->format));
        }
        $delimiter = QueryParameterArray::DELIMITER_MAP[$this->format];
        if ($delimiter === null) {
            if (\is_array($value)) {
                foreach ($value as $item) {
                    $result = $result && parent::checkType($item);
                }
            } else {
                $result = false;
            }
        } else {
            switch ($this->type) {
                case QueryParameterArray::TYPE_INT:
                    $result = \is_string($value) && \preg_match(self::prepareRepeatingTypeRegExp(self::RE_INT, $delimiter), $value);
                    break;
                case QueryParameterArray::TYPE_BOOL:
                    $result = \is_string($value) && \preg_match(self::prepareRepeatingTypeRegExp(self::RE_BOOL, $delimiter), $value);
                    break;
                case QueryParameterArray::TYPE_FLOAT:
                    $result = \is_string($value) && \preg_match(self::prepareRepeatingTypeRegExp(self::RE_FLOAT, $delimiter), $value);
                    break;
                case QueryParameterArray::TYPE_STRING:
                    $result = \is_string($value);
                    break;
                default:
                    throw new \InvalidArgumentException(\sprintf('Can not check for type %s.', $this->type));
            }
        }
        return $result;
    }

    /**
     * @param string $typeRegExp
     * @param string $delimiter
     *
     * @return string
     */
    protected static function prepareRepeatingTypeRegExp(string $typeRegExp, string $delimiter): string
    {
        $escapedDelimiter = \preg_quote($delimiter, '/');
        return '/^(' . $typeRegExp . ')(' . $escapedDelimiter . '(' . $typeRegExp . '))*$/';
    }
}
