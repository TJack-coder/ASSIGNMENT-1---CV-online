<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CvWorkHistory extends Model
{
    protected $fillable = ['cv_id', 'company_name', 'job_title', 'employment_type_id','industry_id', 'start_date', 'end_date', 'description'];
}