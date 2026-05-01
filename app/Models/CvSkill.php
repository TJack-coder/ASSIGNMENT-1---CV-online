<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CvSkill extends Model
{
    protected $fillable = ['cv_id', 'skills', 'proficiency_level_id'];
}