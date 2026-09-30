<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array { return ['name' => ['required','string','max:150'], 'phone' => ['required','string','max:40'], 'email' => ['required','email','max:150'], 'address' => ['required','string','max:255']]; }
}