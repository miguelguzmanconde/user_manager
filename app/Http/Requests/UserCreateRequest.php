<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest {

    public function rules(): array {

        return [
            'type' => ['required', Rule::in(['guest', 'admin'])],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string'],
        ];

    }

}
