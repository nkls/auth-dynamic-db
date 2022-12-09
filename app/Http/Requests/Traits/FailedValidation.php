<?php

namespace App\Http\Requests\Traits;

use App\Helpers\Message;
use Illuminate\Contracts\Validation\Validator;

trait FailedValidation
{

    protected function failedValidation(Validator $validator): void
    {
        Message::get(400, null, $validator->errors()->toArray());
    }
}
