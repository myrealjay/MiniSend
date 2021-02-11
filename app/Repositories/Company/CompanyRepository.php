<?php


namespace App\Repositories\Company;

use App\Exceptions\CompanyCreationException;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CompanyRepository implements CompanyRepositoryInterface
{
    /**
     * create a company with user data
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data):Model
    {
        $user=User::create([
            'name'=>$data['user_name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password'])
        ]);

        $company_data=array(
            'user_id'=>$user->id,
            'name'=>$data['company_name'],
            'status'=>'active'
        );

        $keys=Company::generateKey();

        $company_data=array_merge($company_data, $keys);

        $company=Company::create($company_data);

        return $company;
        try {
        } catch (\Exception $e) {
            throw new CompanyCreationException('There was an error creating your company data');
        }
    }

    /**
     * find comppany by API key
     *
     * @param string $apiKey
     * @return Model|null
     */
    public function getCompanyByKey(string $apiKey):?Model
    {
        return Company::findByKey(($apiKey));
    }

    /**
     * get API key with user email
     *
     * @param string $email
     * @return string
     */
    public function getApiKey(string $email):string
    {
        $user=User::with('company')
        ->where('email', $email)
        ->first();

        if (!$user) {
            throw new ModelNotFoundException();
        }
        return $user->company->api_key;
    }
}
