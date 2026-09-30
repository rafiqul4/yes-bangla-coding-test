<?php

namespace App\Http\Requests;

class UpdateCustomerRequest extends StoreCustomerRequest
{
    public function authorize(): bool { return $this->user() !== null; }
}