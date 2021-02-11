<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmailCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'sender'=>$this->from,
            'receiver'=>$this->to,
            'subject'=>$this->subject,
            'text_content'=>$this->text_content,
            'html_content'=>$this->html_content,
            'attachments'=>$this->attachments,
            'status'=>$this->status,
        ];
    }
}
