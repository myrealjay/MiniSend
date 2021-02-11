<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailSendingRequest extends FormRequest
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
            'from_email'=>'required|email',
            'from_name'=>'nullable|string',
            'to_email'=>'required|email',
            'to_name'=>'nullable|string',
            'subject'=>'required|string',
            'text_content'=>'nullable|required_if:html_content,=,null',
            'html_content'=>'nullable|required_if:text_content,=,null',
            'attachments'=>'nullable|array',
            'attchments.*'=>'required|image|mimes:jpg,png,pdf,gif,jpeg,txt,docx'
        ];
    }
}
