<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array { return ['name' => ['sometimes','required','string','max:150'], 'sku' => ['sometimes','required','string','max:80',Rule::unique('products','sku')->ignore($this->route('product'))], 'category' => ['sometimes','required','string','max:80'], 'price' => ['sometimes','required','numeric','min:0'], 'stock_quantity' => ['sometimes','required','integer','min:0'], 'status' => ['sometimes',Rule::in(['Active','Out of stock','Archived'])]]; }
}