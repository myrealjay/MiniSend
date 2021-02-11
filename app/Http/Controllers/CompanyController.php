<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyCreationRequest;
use App\Http\Requests\GetCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use JsonResponse;

    public function create(CompanyCreationRequest $request, CompanyRepositoryInterface $company)
    {
        $data=$request->validated();

        $company=$company->create($data);

        return $this->success('Company Created', new CompanyResource($company));
    }

    public function getApiKey(GetCompanyRequest $request, CompanyRepositoryInterface $company)
    {
        $data=$request->validated();

        $key=$company->getApiKey($data['email']);

        return $this->success('ApiKey Fetched', $key);
    }
}
