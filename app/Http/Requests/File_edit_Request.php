<?php

namespace App\Http\Requests;

use App\Models\File;
use App\Models\Group;
use App\Models\UserFile;
use App\Repositories\FileRepository;
use App\Repositories\UserFileRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class File_edit_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $validator = Validator::make($this->all(), [
            'file' => 'required|file|max:2048',
            'group_id' => 'required|integer|exists:groups,id'
        ]);

        if ($validator->fails()) {
            $this->failedAuthorizationResponse('There is something wrong in some fields.');
        }

        $data = $this->all();
        $user = Auth::user();

        $group_id = $data['group_id'];
        $file = $data['file'];
        $file_name = $file->getClientOriginalName();

        $conditions = [
            'group_id' => $group_id,
            'isFree' => false
        ];
        $file = FileRepository::findByConditions($conditions);
        if ($file !== null) {
            $conditions = [
                'user_id' => $user['id'],
                'file_id' => $file['id']
            ];
            $user_file = UserFileRepository::findByConditions($conditions);

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
