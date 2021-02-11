<?php

namespace App\Http\Controllers;

use App\Http\Requests\ByRecipientRequest;
use App\Http\Requests\EmailSendingRequest;
use App\Http\Requests\GetEnailsRequest;
use App\Http\Resources\EmailCollection;
use App\Http\Resources\EmailResource;
use App\Jobs\EmailSendingJob;
use App\Models\Company;
use App\Models\CompanyEmail;
use App\Traits\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;

class CompanyEmailController extends Controller
{
    use JsonResponse;

    /**
     * send email
     *
     * @param EmailSendingRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function send(EmailSendingRequest $request):\Illuminate\Http\JsonResponse
    {
        $attachments=$request->attachments;
        $subject=$request->subject;
        $from=['name'=>$request->from_name,'email'=>$request->from_email];
        $to=['name'=>$request->to_name,'email'=>$request->to_email];
        $textContent=$request->text_content;
        $htmlContent=$request->html_content;
        $attachment_paths=[];

        //extract only the filenames from the attachments for queuing purposes
        if ($attachments) {
            foreach ($attachments as $attachment) {
                $filename = str_replace(' ', '_', $attachment->getClientOriginalName());
                $filePath = $attachment->storeAs('attachments', $filename, 'public');
                $attachment_paths[]=$filename;
            }
        }

        dispatch(new EmailSendingJob($from, $to, $subject, $textContent, $htmlContent, $attachment_paths));

        return $this->success('Email Sent Successfully');
    }

    /**
     * get all emails
     *
     * @param GetEnailsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(GetEnailsRequest $request):\Illuminate\Http\JsonResponse
    {
        $key=\request()->header('minisend-key');
        $company = Company::findByKey($key);

        $data=$request->validated();

        $query=CompanyEmail::query();

        $pipes = [
            \App\Filters\RecipientFilter::class,
            \App\Filters\SenderFilter::class,
            \App\Filters\SubjectFilter::class,
            \App\Filters\StatusFilter::class,
        ];

        $data['query']=$query;

        $result=app(Pipeline::class)
                ->send($data)
                ->through($pipes)->thenReturn();

        $data=$result['query']->where('company_id', $company->id)
                ->orderBy('id', 'desc')
                ->paginate($data['length']);

        return $this->success('Emails fetched Successfully', EmailResource::collection($data)->response()->getData(true));
    }

    /**
     * fetch emails belonging to a recipient
     *
     * @param ByRecipientRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecipient(ByRecipientRequest $request):\Illuminate\Http\JsonResponse
    {
        $key=\request()->header('minisend-key');
        $company = Company::findByKey($key);

        $query=CompanyEmail::where('company_id', $company->id)
        ->where('to', json_encode($request->recipient))
        ->orderBy('id', 'desc')
        ->paginate($request->length);

        return $this->success('Emails fetched Successfully', EmailResource::collection($query)->response()->getData(true));
    }

    /**
     * Fetch a single email
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSingle(int $id):\Illuminate\Http\JsonResponse
    {
        $query=CompanyEmail::where('id', $id)->first();

        if (!$query) {
            throw new ModelNotFoundException();
        }
        return $this->success('Email fetched Successfully', new EmailResource($query));
    }


    public function getDistinct()
    {
        $key=\request()->header('minisend-key');
        $company = Company::findByKey($key);

        $senders=CompanyEmail::select('from')->where('company_id', $company->id)->distinct()->get();
        $receivers=CompanyEmail::select('to')->where('company_id', $company->id)->distinct()->get();
        $subjects=CompanyEmail::select('subject')->where('company_id', $company->id)->distinct()->get();

        $data=[
            'senders'=>$senders,
            'receivers'=>$receivers,
            'subjects'=>$subjects
        ];

        return $this->success('Email fetched Successfully', $data);
    }
}
