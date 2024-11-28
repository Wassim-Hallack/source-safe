<?php

namespace App\Http\Requests;

use App\Repositories\GroupRepository;
use App\Repositories\UserGroupRepository;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class FileOperation_get_user_operations_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $validator = Validator::make($this->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'group_id' => 'required|integer|exists:groups,id',
        ]);

        if ($validator->fails()) {
            $this->failedAuthorizationResponse('There is something wrong in some fields.');
        }

        $group = GroupRepository::find($this['group_id']);
        if (!$group['is_owner']) {
            return $this->failedAuthorizationResponse('The logged-in user is not the admin of this group');
        }

        $conditions = [
            'user_id' => $this['user_id'],
            'group_id' => $this['group_id']
        ];
        $is_exists = UserGroupRepository::existsByConditions($conditions);
        if (!$is_exists) {
            return $this->failedAuthorizationResponse('This user does not belong to this group.');
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
