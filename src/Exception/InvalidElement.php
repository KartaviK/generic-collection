<?php

namespace kartavik\Support\Exception;

use kartavik\Support\Exception;

/**
 * Class InvalidElementException
 * @package kartavik\Support\Exceptions
 */
class InvalidElement extends \InvalidArgumentException implements Exception
{
    /** @var mixed */
    protected $var;

    /** @var string */
    protected $needType;

    public function __construct(
        $var,
        string $needType,
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->var = $var;
        $this->needType = $needType;

        $objectType = get_class($var);

        parent::__construct(
            "Element {$objectType} must be instance of " . $needType,
            $code,
            $previous
        );
    }

    public function getVar()
    {
        return $this->var;
    }

    public function getNeedType(): string
    {
        return $this->needType;
    }
}
