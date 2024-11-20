<?php

namespace App\Http\Requests;

use App\Repositories\UserGroupRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class File_get_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $validator = Validator::make($this->all(), [
            'group_id' => 'required|integer|exists:groups,id',
        ]);

        if ($validator->fails()) {
            $this->failedAuthorizationResponse('There is something wrong in some fields.');
        }

        $user = Auth::user();
        $group_id = $this->input('group_id');

        if ($group_id === null) {
            $this->failedAuthorizationResponse("There is no group with this id.");
        }

        $conditions = [
            'user_id' => $user->id,
            'group_id' => $group_id,
        ];
        $is_exists_user_group = UserGroupRepository::existsByConditions($conditions);

        if (!$is_exists_user_group) {
            $this->failedAuthorizationResponse("The logged in user does not belong to this group.");
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
