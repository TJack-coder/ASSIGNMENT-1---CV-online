<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'cv_category_id', 'full_name', 'date_of_birth', 'gender',
        'email', 'phone_number', 'country_id', 'city_id', 'district_id',
        'street_address', 'postal_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(CvCategory::class, 'cv_category_id');
    }

    public function educations()
    {
        return $this->hasMany(CvEducation::class);
    }

    public function workHistories()
    {
        return $this->hasMany(CvWorkHistory::class);
    }

    public function certificates()
    {
        return $this->hasMany(CvCertificate::class);
    }

    public function skills()
    {
        return $this->hasMany(CvSkill::class);
    }
}