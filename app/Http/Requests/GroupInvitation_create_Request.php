<?php

namespace App\Http\Requests;

use App\Repositories\GroupInvitationRepository;
use App\Repositories\GroupRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class GroupInvitation_create_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this['logged_in_user'] = Auth::user();

        $group = GroupRepository::find($this['group_id']);
        if ($group['user_id'] !== $this['logged_in_user']['id']) {
            $this->failedAuthorizationResponse('The logged in user is not the admin of this group.');
        }

        $conditions = [
            'user_id' => $this['user_id'],
            'group_id' => $this['group_id']
        ];
        $is_exists_previous_invitation = GroupInvitationRepository::existsByConditions($conditions);
        if ($is_exists_previous_invitation) {
            $this->failedAuthorizationResponse('There is previous invitation for this user to this group.');
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
