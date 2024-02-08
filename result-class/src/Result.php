<?php

class Result
{
    private bool $success = false;

    /**
     * @template ValueType
     * @template ErrorType
     * @param ?ValueType $value
     * @param ?ErrorType $error
     */
    public function __construct(
        private mixed $value,
        private mixed $error
    ) {
        if ($value !== null) {
            $this->success = true;
        }
    }

    /**
     * @template ValueType
     * @param ValueType $value
     * @return Result<ValueType,null>
     */
    public static function ok(mixed $value): Result
    {
        return new Result($value, null);
    }

    /**
     * @template ErrorType
     * @param ErrorType $error
     * @return Result<null,ErrorType>
     */
    public static function fail(mixed $error): Result
    {
        return new Result(null, $error);
    }

    /**
     * @template ReturnType
     * 
     * @param class-string<ReturnType> $expect
     * @return ReturnType
     */
    public function match($expect, callable $onValue, callable $onError)
    {
        if ($this->success) {
            return $onValue($this->value);
        }
        return $onError($this->error);
    }
}
