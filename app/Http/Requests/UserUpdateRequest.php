<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

use App\Models\User;

class UserUpdateRequest extends FormRequest {
    
    public function rules(): array {

        return [
            'id' => ['required', 'numeric'],
            'type' => ['required', Rule::in(['guest', 'admin'])],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ];

    }

    public function after(): array {

        return [
            function (Validator $validator) {

                $validated = $validator->validated();
                $user = User::where('email', $validated['email'])->first();
                if (isset($user) && !(stripos($user->id, $validated['id']) !== false)) {
                    $validator->errors()->add(
                        'email',
                        'There\'s another user with that email.'
                    );
                }
            }
        ];

    }

}
