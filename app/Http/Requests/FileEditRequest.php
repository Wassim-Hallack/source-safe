<?php

namespace App\Http\Requests;

use App\Models\File;
use App\Models\Group;
use App\Models\UserFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class FileEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $data = $this->all();
        $user = Auth::user();

        $group_id = $data['group_id'];
        $file = $data['file'];
        $file_name = $file->getClientOriginalName();

        $file = File::where('name', $file_name)
            ->where('group_id', $group_id)
            ->where('isFree', false)
            ->first();
        if ($file !== null) {
            $user_file = UserFile::where('user_id', $user['id'])
                ->where('file_id', $file['id'])
                ->first();

            if ($user_file === null) {
                $this->failedAuthorizationResponse('The logged in user does not have this file');
            }
        } else {
            $this->failedAuthorizationResponse('There is no file with these attributes.');
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
            'file' => 'required|file|max:2048',
            'group_id' => 'required|integer|exists:groups,id'
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
