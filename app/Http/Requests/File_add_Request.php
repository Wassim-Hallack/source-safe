<?php

namespace App\Http\Requests;

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use App\Repositories\GroupRepository;
use App\Repositories\UserGroupRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class File_add_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (($this['isFree'][0] !== "0" && $this['isFree'][0] !== "1")) {
            $this->failedAuthorizationResponse('There is something wrong in file_status field.');
        }

        $this['isFree'] = $this['isFree'][0];
        $this['isFree'] = (boolean)$this['isFree'];

        $validator = Validator::make($this->all(), [
            'isFree' => ['required', 'boolean'],
            'file' => 'required|file|max:2048',
            'group_id' => 'required|integer|exists:groups,id'
        ]);

        if ($validator->fails()) {
            $this->failedAuthorizationResponse('There is something wrong in some fields.');
        }

        $group_id = $this['group_id'];
        $file = $this['file'];
        $file_name = $file->getClientOriginalName();
        $isFree = $this['isFree'];
        $user_id = $this['user_id'];

        $group = GroupRepository::find($group_id);
        $files_in_the_same_group = $group->files->pluck('name')->toArray();
        if (in_array($file_name, $files_in_the_same_group)) {
            $this->failedAuthorizationResponse('There is file with the same name in this group.');
        }

        $add_files_requests_in_the_same_group = $group->add_file_requests->pluck('name')->toArray();
        if (in_array($file_name, $add_files_requests_in_the_same_group)) {
            $this->failedAuthorizationResponse('There is add file request with the same name in this group.');
        }

        if (!$isFree) {
            $user = UserRepository::find($user_id);
            if ($user === null) {
                $this->failedAuthorizationResponse('There is no user with this id.');
            } else {
                $conditions = [
                    'user_id' => $user_id,
                    'group_id' => $group_id
                ];
                $is_exists = UserGroupRepository::existsByConditions($conditions);

                if (!$is_exists) {
                    $this->failedAuthorizationResponse('This user does not belong to this group.');
                }

                $user = Auth::user();

                $conditions = [
                    'user_id' => $user['id'],
                    'group_id' => $group_id
                ];
                $is_exists = UserGroupRepository::existsByConditions($conditions);

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
