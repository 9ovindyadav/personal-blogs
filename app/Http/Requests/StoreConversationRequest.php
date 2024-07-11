<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|string|in:private,group',
            'name' => 'required_if:type,group|string|max:255',
            'description' => 'nullable|string',
            'participants' => 'required|array|min:1',
            'admin_ids' => 'required_if:type,group|array|min:1', 
        ];
    }
}
