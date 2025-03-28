<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(request()->routeIs('api.tasks.store') && request()->method() === 'POST'){
            return  [
                'title' => 'required|string',
                'description' => 'required|string',
                'status' => 'nullable|'.TaskStatus::validation(),
                'started_at' => 'required|date|after_or_equal:'.now(),
                'ended_at' => 'required|date|after:started_at'
            ];
        }
        if(request()->routeIs('api.tasks.update') && request()->method() === 'PATCH'){
            return  [
                'title' => 'nullable|string',
                'description' => 'nullable|string',
                'status' => 'nullable|'.TaskStatus::validation(),
                'started_at' => 'nullable|date',
                'ended_at' => 'nullable|date|after:started_at'
            ];
        }
    }
}
