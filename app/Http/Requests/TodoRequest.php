<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'assignee' => 'nullable|string|max:255',
            'due_date' => 'required|date|after_or_equal:today',
            'time_tracked' => 'nullable|numeric|min:0',
            'status' => ['nullable', Rule::in(['pending', 'open', 'in_progress', 'completed'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
        ];
    }

    public function messages()
    {
        return [
            'status.in' => 'The status must be one of: pending, open, in_progress, completed',
            'priority.in' => 'The priority must be one of: low, medium, high',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Error validation',
            'errors' => $validator->errors()
        ], 422));
    }

    protected function prepareForValidation()
    {
        if (!$this->has('status')) {
            $this->merge(['status' => 'pending']);
        }
        
        if (!$this->has('time_tracked')) {
            $this->merge(['time_tracked' => 0]);
        }
    }
}
