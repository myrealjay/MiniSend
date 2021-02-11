<?php
namespace App\Classes;

use Exception;

class IDFilter
{
    public const TYPE_PASSPORT='PASSPORT';
    public const TYPE_NATIONAL_ID='NATIONAL_ID';
    public const TYPE_SSNIT='SSNIT';
    public const TYPE_VOTER_CARD='VOTER_ID';
    public const TYPE_ALIEN_CARD='ALIEN_CARD';
    public const TYPE_BVN='BVN';
    public const TYPE_NIN='NIN';
    public const TYPE_NIN_SLIP='NIN_SLIP';
    public const TYPE_DRIVERS_LICENSE='DRIVERS_LICENSE';
    public const TYPE_TIN='TIN';
    public const TYPE_CAC='CAC';
    public const NATIONAL_ID_NO_PHOTO='NATIONAL_ID_NO_PHOTO';
    public const TELCO_SUBSCRIBER='telco_subscriber';
    public const TYPE_CUSTOMER_PROFILE='CUSTOMER_PROFILE';

    private ?string $country;
    private string $id_type;
    private ?string $id_number;
    private ?string $first_name;
    private ?string $last_name;
    private ?string $dob;
    private ?string $phone_number;
    private ?string $middle_name;
    private string $user_id;
    private ?string $gender;
    private ?string $expiry;
    private ?string $address;
    private ?string $IdentificationProof;
    private ?string $faceProof;
    private array $data=[];
    private bool $success=false;
    private array $error=[];
    private bool $withImage=false;

    private string $handler='';
    private array $credeqProfile=[];

    /**
     * ID filter constructor
     *
     * @param string $country
     * @param string $id_type
     * @param string $id_number
     * @param string $first_name
     * @param string $last_name
     * @param string|null $middle_name
     * @param string|null $dob
     * @param string|null $phone_number
     * @param string $user_id
     */
    public function __construct(
        string $country='NG',
        string $id_type,
        string $id_number=null,
        ?string $first_name=null,
        ?string $last_name=null,
        ?string $middle_name=null,
        ?string $dob=null,
        ?string $phone_number=null,
        string $user_id,
        ?string $gender=null,
        ?string $expiry=null,
        ?string $address=null,
        ?string $IdentificationProof=null,
        ?string $faceProof=null
    ) {
        $this->country=$country;
        $this->id_type=$id_type;
        $this->id_number=$id_number;
        $this->first_name=$first_name;
        $this->last_name=$last_name;
        $this->middle_name=$middle_name;
        $this->dob =$dob;
        $this->phone_number=$phone_number;
        $this->user_id=$user_id;
        $this->gender=$gender;
        $this->expiry=$expiry;
        $this->address=$address;
        $this->IdentificationProof=$IdentificationProof;
        $this->faceProof=$faceProof;
    }

    public function getIDNumber():string
    {
        return $this->id_number;
    }
    public function getCountry():string
    {
        return $this->country;
    }

    public function getIDType():string
    {
        return $this->id_type;
    }
    public function getFirstName():?string
    {
        return $this->first_name;
    }
    public function getMiddleName():?string
    {
        return $this->middle_name;
    }

    public function getLastName():?string
    {
        return $this->last_name;
    }

    public function getGender():?string
    {
        return $this->gender;
    }
    public function getAddress():?string
    {
        return $this->address;
    }
    public function getExpiry():?string
    {
        return $this->expiry;
    }
    public function getDOB():?string
    {
        return $this->dob;
    }

    public function getIdentificationProof():?string
    {
        return $this->IdentificationProof;
    }

    public function getFaceProof():?string
    {
        return $this->faceProof;
    }

    public function setWithImage()
    {
        $this->withImage=true;
    }

    public function isWithImage()
    {
        return $this->withImage;
    }

    public function setCredeqProfile(string $nin, string $frscno, string $bvn):void
    {
        $this->credeqProfile=[$nin,$frscno,$bvn];
    }

    public function getCredeqProfile()
    {
        return $this->credeqProfile;
    }

    /**
     * returns user phone
     *
     * @return string|null
     */
    public function getPhoneNumber():?string
    {
        return $this->phone_number;
    }

    /**
     * returns the user id
     *
     * @return string
     */
    public function getUserId():string
    {
        return $this->user_id;
    }
    /**
     * sets the success to true
     *
     * @return void
     */
    public function confirmSuccess():void
    {
        $this->success=true;
    }

    /**
     * sets the pipe that handleld the request
     *
     * @param string $handler
     * @return void
     */
    public function setHandler(string $handler):void
    {
        $this->handler=$handler;
    }

    /**
     * gets the pipe that hanlded the request
     *
     * @return string
     */
    public function getHandler():string
    {
        return $this->handler;
    }

    /**
     * sets data returned from the request
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data):void
    {
        $this->data=$data;
    }

    /**
     * sets the error associated with request
     *
     * @param array $error
     * @return void
     */
    public function setError(array $error):void
    {
        $this->error=$error;
    }

    /**
     * return error associated with request
     *
     * @return string
     */
    public function getError():string
    {
        return $this->error['error'];
    }

    /**
     * returns the data gotten from the request
     *
     * @return array
     */
    public function getData():array
    {
        return $this->data;
    }

    /**
     * checks if the request is successful
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->success;
    }
}
