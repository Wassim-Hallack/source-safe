<?php

namespace App\Http\Requests;

use App\Repositories\GroupRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddFileRequest_get_Request extends FormRequest
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

        $this['user'] = Auth::user();
        $this['group'] = GroupRepository::find($this['group_id']);
        if($this['user']['id'] !== $this['group']['user_id']) {
            return $this->failedAuthorizationResponse('The logged in user is not the admin of the group.');
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
