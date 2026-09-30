<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array { return ['name' => ['required','string','max:150'], 'sku' => ['required','string','max:80','unique:products,sku'], 'category' => ['required','string','max:80'], 'price' => ['required','numeric','min:0'], 'stock_quantity' => ['required','integer','min:0'], 'status' => ['required', Rule::in(['Active','Out of stock','Archived'])]]; }
}