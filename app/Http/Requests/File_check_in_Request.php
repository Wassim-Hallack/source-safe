<?php

namespace App\Http\Requests;

use App\Repositories\FileRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class File_check_in_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $validator = Validator::make($this->all(), [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:files,id'],
        ]);

        if ($validator->fails()) {
            $this->failedAuthorizationResponse('There is something wrong in some fields.');
        }

        $this['files'] = FileRepository::getFilesByIds($this['ids']);

        $groupIds = $this['files']->pluck('group_id')->unique();
        if ($groupIds->count() > 1) {
            $this->failedAuthorizationResponse('The provided file IDs belong to different groups.');
        }

        $isAllFree = $this['files']->every(fn($file) => $file->isFree);
        if (!$isAllFree) {
            $this->failedAuthorizationResponse("Some files not free");
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
