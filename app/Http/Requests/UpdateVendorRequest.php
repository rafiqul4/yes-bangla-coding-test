<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isAdmin() ?? false; }
    public function rules(): array { return ['name' => ['sometimes','required','string','max:150'], 'contact' => ['sometimes','required','string','max:150'], 'email' => ['sometimes','required','email','max:150',Rule::unique('vendors','email')->ignore($this->route('vendor'))], 'category' => ['sometimes','required','string','max:80'], 'rating' => ['sometimes','required','numeric','min:0','max:5'], 'status' => ['sometimes',Rule::in(['Active','Review','Paused'])]]; }
}