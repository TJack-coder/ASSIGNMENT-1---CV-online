<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CvEducation extends Model
{
    protected $fillable = ['cv_id', 'institution_id', 'degree_level_id', 'major_id', 'start_year', 'end_year', 'description'];
}