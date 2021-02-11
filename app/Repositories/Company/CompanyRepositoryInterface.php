<?php

namespace App\Repositories\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface CompanyRepositoryInterface
{
    public function create(array $data):Model;

    public function getCompanyByKey(string $apiKey):?Model;

    public function getApiKey(string $email):string;
}
