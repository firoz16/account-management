<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $accountNo = $this->route('account'); // Get account ID from route

        return [
            'account_name' => [
                'required',
                'string',
                Rule::unique('accounts', 'account_name')->ignore($accountNo,'account_number') // Ignore existing record when updating
            ],
            'account_type' => 'required|in:Personal,Business',
            'currency' => 'required|in:USD,EUR,GBP',
            'initial_balance' => 'nullable|numeric|min:0'
        ];
    }

}
