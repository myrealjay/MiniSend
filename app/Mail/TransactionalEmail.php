<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Storage;

class TransactionalEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
  
    public string $textContent;
    public string $htmlContent;
    public $files;
    public string $mailSubject;
    public array $fromMail;

    /**
     * email mailable constructor
     *
     * @param string $from
     * @param string $subject
     * @param string $textContent
     * @param string $htmlContent
     * @param  $files
     */
    public function __construct(array $from, string $subject, string $textContent, string $htmlContent, $files)
    {
        $this->mailSubject=$subject;
        $this->textContent=$textContent;
        $this->htmlContent=$htmlContent;
        $this->files=$files;
        $this->fromMail=$from;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email=$this->subject($this->mailSubject)
        ->from($this->fromMail['email'], $this->fromMail['name'])
        ->text('mails.textcontent', ['content'=>$this->textContent])
        ->view('mails.htmlcontent', ['content'=>$this->htmlContent]);

        //attache files if present
        if ($this->files) {
            $mailAttachments=json_decode($this->files);

            foreach ($mailAttachments as $file) {
                $file="attachments/$file";
                $mime=Storage::disk('public')->getMimeType($file);
                $realFile=Storage::url($file);
                $fileName=basename($realFile);
                $filePath=public_path($realFile);
            
                $email->attach(
                    realpath($filePath),
                    array(
                        'as'=>$fileName,
                        ['mime' => $mime]
                    )
                );
            }
        }
        
        return $email;
    }
}
