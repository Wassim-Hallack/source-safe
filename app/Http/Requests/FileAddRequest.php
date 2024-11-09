<?php

namespace App\Http\Requests;

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $data = $this->all();
        if (($data['file_status'][0] !== "0" && $data['file_status'][0] !== "1")) {
            $this->failedAuthorizationResponse('There is something wrong in file_status field.');
        }

        $data['file_status'] = $data['file_status'][0];
        $data['file_status'] = (boolean)$data['file_status'];

        $validator = Validator::make($data, [
            'file_status' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            $this->failedAuthorizationResponse('There is something wrong in some fields.');
        }

        $group_id = $data['group_id'];
        $file = $data['file'];
        $file_name = $file->getClientOriginalName();
        $file_status = $data['file_status'];
        $user_id = $data['user_id'];

        $group = Group::find($group_id);
        $files_in_the_same_group = $group->files->pluck('name')->toArray();
        if (in_array($file_name, $files_in_the_same_group)) {
            $this->failedAuthorizationResponse('There is file with the same name in this group.');
        }

        if ($file_status) {
            $user = User::find($user_id);
            if ($user === null) {
                $this->failedAuthorizationResponse('There is no user with this id.');
            } else {
                $is_exists = UserGroup::where('user_id', $user_id)
                    ->where('group_id', $group_id)
                    ->exists();

                if (!$is_exists) {
                    $this->failedAuthorizationResponse('This user does not belong to this group.');
                }

                $user = Auth::user();
                $is_exists = UserGroup::where('user_id', $user['id'])
                    ->where('group_id', $group_id)
                    ->exists();

                if (!$is_exists) {
                    $this->failedAuthorizationResponse('The logged-in user does not belong to this group.');
                }
            }
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
