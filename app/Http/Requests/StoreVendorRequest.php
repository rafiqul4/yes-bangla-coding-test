<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isAdmin() ?? false; }
    public function rules(): array { return ['name' => ['required','string','max:150'], 'contact' => ['required','string','max:150'], 'email' => ['required','email','max:150','unique:vendors,email'], 'category' => ['required','string','max:80'], 'rating' => ['required','numeric','min:0','max:5'], 'status' => ['required', Rule::in(['Active','Review','Paused'])]]; }
}