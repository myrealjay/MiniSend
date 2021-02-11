<?php

namespace App\Jobs;

use App\Mail\TransactionalEmail;
use App\Models\Company;
use App\Models\CompanyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Mail;

class EmailSendingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public array $from;
    public array $to;
    public string $subject;
    public string $textContent;
    public string $htmlContent;
    public $attachments;
    public int $tracker_id;

    /**
     * Email job constructor
     *
     * @param array $from
     * @param array $to
     * @param string $subject
     * @param string $textContent
     * @param string $htmlContent
     * @param  $attachments
     */
    public function __construct(array $from, array $to, string $subject, string $textContent, string $htmlContent, $attachments)
    {
        $this->from=$from;
        $this->to=$to;
        $this->subject=$subject;
        $this->textContent=$textContent;
        $this->htmlContent=$htmlContent;
        $this->attachments=json_encode($attachments);

        //keep a copy of the email that is sent to the recipient
        $key=\request()->header('minisend-key');
        $company = Company::findByKey($key);
      
        $email = new CompanyEmail();
        $email->from=json_encode($from);
        $email->to=json_encode($to);
        $email->subject=$subject;
        $email->text_content=$textContent;
        $email->html_content=$htmlContent;
        $email->attachments=json_encode($attachments);
        $email->company_id=$company->id;
        $email->status="posted";
        $email->save();
        $this->tracker_id=$email->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Redis::throttle('key')->block(0)->allow(1)->every(5)->then(function () {
            $tracker=CompanyEmail::where('id', $this->tracker_id)->first();
            try {
                Mail::to($this->to['email'], $this->to['name'])
                ->send(new TransactionalEmail($this->from, $this->subject, $this->textContent, $this->htmlContent, $this->attachments));
                $tracker->status='sent';
                $tracker->save();
            } catch (\Swift_TransportException $e) {
                $tracker->status='failed';
                $tracker->save();
                throw new \Swift_TransportException($e->getMessage());
            }
        }, function () {
            return $this->release(5);
        });
    }
}
