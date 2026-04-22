<?php

namespace app\model;

use DateTime;

class CV
{
    private $id;
    private $userId;
    private $fullName;
    private $birthday;
    private $gender;
    private $email;
    private $phoneNumber;
    private $countriesId;
    private $citiesId;
    private $districtId;
    private $address;
    private $postalCode;
    private $categoriesId;
    
    // Relations
    private $skills = [];
    private $workHistory = [];
    private $education = [];
    private $certificates = [];
    private $user;
    private $category;
    private $country;
    private $city;
    private $district;

    public function __construct($userId = null, $fullName = null, $email = null)
    {
        $this->userId = $userId;
        $this->fullName = $fullName;
        $this->email = $email;
    }

    // ID
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; return $this; }

    // User ID
    public function getUserId() { return $this->userId; }
    public function setUserId($userId) { $this->userId = $userId; return $this; }

    // Full Name
    public function getFullName() { return $this->fullName; }
    public function setFullName($fullName) { $this->fullName = $fullName; return $this; }

    // Birthday
    public function getBirthday() { return $this->birthday; }
    public function setBirthday($birthday) { $this->birthday = $birthday; return $this; }

    // Gender
    public function getGender() { return $this->gender; }
    public function setGender($gender) { $this->gender = $gender; return $this; }

    // Email
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; return $this; }

    // Phone Number
    public function getPhoneNumber() { return $this->phoneNumber; }
    public function setPhoneNumber($phoneNumber) { $this->phoneNumber = $phoneNumber; return $this; }

    // Countries ID
    public function getCountriesId() { return $this->countriesId; }
    public function setCountriesId($countriesId) { $this->countriesId = $countriesId; return $this; }

    // Cities ID
    public function getCitiesId() { return $this->citiesId; }
    public function setCitiesId($citiesId) { $this->citiesId = $citiesId; return $this; }

    // District ID
    public function getDistrictId() { return $this->districtId; }
    public function setDistrictId($districtId) { $this->districtId = $districtId; return $this; }

    // Address
    public function getAddress() { return $this->address; }
    public function setAddress($address) { $this->address = $address; return $this; }

    // Postal Code
    public function getPostalCode() { return $this->postalCode; }
    public function setPostalCode($postalCode) { $this->postalCode = $postalCode; return $this; }

    // Categories ID
    public function getCategoriesId() { return $this->categoriesId; }
    public function setCategoriesId($categoriesId) { $this->categoriesId = $categoriesId; return $this; }

    // Relations - Skills
    public function getSkills() { return $this->skills; }
    public function setSkills($skills) { $this->skills = $skills; return $this; }
    public function addSkill($skill) { $this->skills[] = $skill; return $this; }

    // Relations - Work History
    public function getWorkHistory() { return $this->workHistory; }
    public function setWorkHistory($workHistory) { $this->workHistory = $workHistory; return $this; }
    public function addWorkHistory($work) { $this->workHistory[] = $work; return $this; }

    // Relations - Education
    public function getEducation() { return $this->education; }
    public function setEducation($education) { $this->education = $education; return $this; }
    public function addEducation($edu) { $this->education[] = $edu; return $this; }

    // Relations - Certificates
    public function getCertificates() { return $this->certificates; }
    public function setCertificates($certificates) { $this->certificates = $certificates; return $this; }
    public function addCertificate($cert) { $this->certificates[] = $cert; return $this; }

    // Relations - User
    public function getUser() { return $this->user; }
    public function setUser($user) { $this->user = $user; return $this; }

    // Relations - Category
    public function getCategory() { return $this->category; }
    public function setCategory($category) { $this->category = $category; return $this; }

    // Relations - Country
    public function getCountry() { return $this->country; }
    public function setCountry($country) { $this->country = $country; return $this; }

    // Relations - City
    public function getCity() { return $this->city; }
    public function setCity($city) { $this->city = $city; return $this; }

    // Relations - District
    public function getDistrict() { return $this->district; }
    public function setDistrict($district) { $this->district = $district; return $this; }

    /**
     * Convert model to array for database
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'full_name' => $this->fullName,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber,
            'countries_id' => $this->countriesId,
            'cities_id' => $this->citiesId,
            'district_id' => $this->districtId,
            'address' => $this->address,
            'postal_code' => $this->postalCode,
            'categories_id' => $this->categoriesId,
        ];
    }

    /**
     * Load from array (from database query result)
     */
    public static function fromArray($data)
    {
        $cv = new self();
        $cv->setId($data['id'] ?? null);
        $cv->setUserId($data['user_id'] ?? null);
        $cv->setFullName($data['full_name'] ?? null);
        $cv->setBirthday($data['birthday'] ?? null);
        $cv->setGender($data['gender'] ?? null);
        $cv->setEmail($data['email'] ?? null);
        $cv->setPhoneNumber($data['phone_number'] ?? null);
        $cv->setCountriesId($data['countries_id'] ?? null);
        $cv->setCitiesId($data['cities_id'] ?? null);
        $cv->setDistrictId($data['district_id'] ?? null);
        $cv->setAddress($data['address'] ?? null);
        $cv->setPostalCode($data['postal_code'] ?? null);
        $cv->setCategoriesId($data['categories_id'] ?? null);
        return $cv;
    }

    /**
     * Validate CV data
     */
    public function validate()
    {
        $errors = [];

        if (empty($this->fullName)) {
            $errors['full_name'] = 'Full name is required';
        }

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required';
        }

        if (empty($this->categoriesId)) {
            $errors['categories_id'] = 'Job category is required';
        }

        if (empty($this->countriesId)) {
            $errors['countries_id'] = 'Country is required';
        }

        return $errors;
    }

    /**
     * Check if CV is valid
     */
    public function isValid()
    {
        return empty($this->validate());
    }
}
?>