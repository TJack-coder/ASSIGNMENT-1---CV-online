<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CvCertificate extends Model
{
    protected $fillable = ['cv_id', 'certificate_name_id', 'issuing_organization_id', 'issue_year', 'description'];
}