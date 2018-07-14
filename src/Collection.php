<?php

namespace kartavik\Designer;

/**
 * Class Collection
 * @package kartavik\Designer
 */
abstract class Collection extends \ArrayObject implements \JsonSerializable, CollectionInterface
{
    /** @var string */
    protected $type = null;

    /**
     * Collection constructor.
     *
     * @param array  $elements
     * @param string $type
     * @param int    $flags
     * @param string $iteratorClass
     */
    public function __construct(
        array $elements = [],
        string $type = null,
        int $flags = 0,
        string $iteratorClass = \ArrayIterator::class
    ) {
        $this->type = $type;

        foreach ($elements as $element) {
            $this->instanceOfType($element);
        }

        parent::__construct($elements, $flags, $iteratorClass);
    }

    abstract public function type(): string;

    /**
     * @param mixed $value
     *
     * @throws InvalidElementException
     */
    public function append($value): void
    {
        $this->instanceOfType($value);

        parent::append($value);
    }

    /**
     * @param mixed $index
     * @param mixed $value
     *
     * @throws InvalidElementException
     */
    public function offsetSet($index, $value): void
    {
        $this->instanceOfType($value);

        parent::offsetSet($index, $value);
    }

    public function jsonSerialize(): array
    {
        return (array)$this;
    }

    /**
     * @param $object
     *
     * @throws InvalidElementException
     */
    public function instanceOfType($object): void
    {
        $type = $this->type();

        if (!$object instanceof $type) {
            throw new InvalidElementException($object, $type);
        }
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return Collection
     */
    public static function __callStatic($name, $arguments)
    {
        if (!empty($arguments) && is_array($arguments[0])) {
            $arguments = $arguments[0];
        }

        return new class($arguments, $name) extends Collection
        {
            public function type(): string
            {
                return $this->type;
            }
        };
    }
}
