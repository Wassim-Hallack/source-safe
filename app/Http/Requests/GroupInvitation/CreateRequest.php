<?php

namespace App\Http\Requests\GroupInvitation;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CreateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

//    public function failedValidation(Validator $validator)
//    {
//        $response = response()->json([
//            'status' => false,
//            'errors' => $validator->errors(),
//            'message' => 'There is something wrong in some fields.',
//        ], 400);
//
//        throw new ValidationException($validator, $response);
//    }
}
