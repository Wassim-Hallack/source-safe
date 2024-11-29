<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class File_download_version_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $validator = Validator::make($this->all(), [
            'file_id' => 'required|integer|exists:files,id',
            'version_number' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $this->failedAuthorizationResponse();
        }
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

    private function failedAuthorizationResponse()
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'response' => 'There is something wrong in some fields.',
        ], 400));
    }
}
