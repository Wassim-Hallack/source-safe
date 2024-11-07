<?php

namespace App\Http\Requests;

use App\Models\GroupInvitation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class GroupInvitationResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $group_id = $this['group_id'];
        $user = Auth::user();

        $invitation = GroupInvitation::where('user_id', $user['id'])
            ->where('group_id', $group_id)
            ->get();
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
            'group_id' => 'required|integer|exists:groups,id',
            'response' => 'required|boolean'
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
