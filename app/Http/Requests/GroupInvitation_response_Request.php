<?php

namespace App\Http\Requests;

use App\Models\GroupInvitation;
use App\Repositories\GroupInvitationRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupInvitation_response_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $validator = Validator::make($this->all(), [
            'group_id' => 'required|integer|exists:groups,id',
            'response' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            $this->failedAuthorizationResponse('There is something wrong in some fields.');
        }

        $group_id = $this['group_id'];
        $user = Auth::user();

        $conditions = [
            'user_id' => $user['id'],
            'group_id' => $group_id
        ];
        $invitation = GroupInvitationRepository::findAllByConditions($conditions);

        if (!count($invitation)) {
            $this->failedAuthorizationResponse('There is no invitation for this user to this group.');
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
