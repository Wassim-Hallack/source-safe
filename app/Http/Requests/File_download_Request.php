<?php

namespace App\Http\Requests;

use App\Repositories\FileRepository;
use App\Repositories\UserFileRepository;
use App\Repositories\UserGroupRepository;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class File_download_Request extends FormRequest
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
        $this['file'] = FileRepository::find($this['file_id']);
        $this['group'] = $this['file']->group;

        $conditions = [
            'user_id' => $user['id'],
            'group_id' => $this['group']['id']
        ];
        $is_exists = UserGroupRepository::existsByConditions($conditions);
        if(!$is_exists) {
            $this->failedAuthorizationResponse('The logged-in user does not belong to this group.');
        }

        $conditions = [
            'user_id' => $user['id'],
            'file_id' => $this['file']['id']
        ];
        $is_exists = UserFileRepository::findByConditions($conditions);
        if($this['file']['isFree'] || !$is_exists) {
            $this->failedAuthorizationResponse('This file did not checked in by this user.');
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

    private function failedAuthorizationResponse($message)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'response' => $message,
        ], 400));
    }
}
