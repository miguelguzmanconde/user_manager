<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class DeleteRequest extends FormRequest {

    public function rules(): array {

        return [
            'id' => ['required', 'numeric'],
            'password' => ['required', 'string', Password::defaults(), 'current_password']
        ];

    }

}
