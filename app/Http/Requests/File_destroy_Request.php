<?php

namespace App\Http\Requests;

use App\Models\File;
use App\Repositories\FileRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class File_destroy_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $validator = Validator::make($this->all(), [
            'file_id' => 'required|integer|exists:files,id',
        ]);

        if ($validator->fails()) {
            $this->failedAuthorizationResponse('There is something wrong in some fields.');
        }

        $user = Auth::user();
        $file_id = $this['file_id'];
        $group = FileRepository::find($file_id)->group;

        if ($group['user_id'] !== $user['id']) {
            $this->failedAuthorizationResponse('The logged-in user is not the admin of this group.');
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    private function failedAuthorizationResponse($message)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'response' => $message,
        ], 400));
    }
}
