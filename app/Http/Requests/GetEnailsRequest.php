<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetEnailsRequest extends FormRequest
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
            'sender'=>'nullable',
            'sender.email'=>'email',
            'sender.name'=>'string',
            'receiver'=>'nullable',
            'receiver.email'=>'email',
            'receiver.name'=>'string',
            'subject'=>'nullable|string',
            'length'=>'required|integer',
            'status'=>'nullable|string|in:posted,sent,failed'
        ];
    }
}
