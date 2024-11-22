<?php

namespace App\Http\Requests;

use App\Models\UserGroup;
use App\Repositories\FileRepository;
use App\Repositories\UserGroupRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileOperation_get_file_operations_Request extends FormRequest
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
        $group = FileRepository::find($this['file_id'])->group;

        $conditions = [
            'user_id' => $user['id'],
            'group_id' => $group['id']
        ];
        $is_exists = UserGroupRepository::existsByConditions($conditions);

        if(!$is_exists) {
            $this->failedAuthorizationResponse('The logged-in user is not in the file\'s group.');
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
