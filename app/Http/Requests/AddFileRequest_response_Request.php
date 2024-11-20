<?php

namespace App\Http\Requests;

use App\Repositories\AddFileRequestRepository;
use App\Repositories\FileRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddFileRequest_response_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $validator = Validator::make($this->all(), [
            'add_file_request_id' => 'required|integer|exists:add_file_requests,id',
            'response' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            $this->failedAuthorizationResponse('There is something wrong in some fields.');
        }

        $this['logged_in_user'] = Auth::user();
        $this['add_file_request'] = AddFileRequestRepository::find($this['add_file_request_id']);
        $this['group'] = $this['add_file_request']->group;

        if(!$this['group']['is_owner']) {
            $this->failedAuthorizationResponse('The logged in user is not the admin of this group');
        }

        $fileName = $this['add_file_request']['name'];
        $group_id = $this['group']->id;

        $conditions = [
            'name' => $fileName,
            'group_id' => $group_id
        ];
        if (FileRepository::existsByConditions($conditions)) {
            $this->failedAuthorizationResponse('There is a file with the same name in this group');
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
