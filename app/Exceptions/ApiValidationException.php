<?php
namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class ApiValidationException extends ValidationException
{
    //
    public function __construct($validator)
    {
        dd($validator);
        parent::__construct($validator);
    }

    public function toArray(): array
    {
        return array_map(function ($message) {
            return current($message);
        }, $this->validator->errors()->messages());
    }
}
