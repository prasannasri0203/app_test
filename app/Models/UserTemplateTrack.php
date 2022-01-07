<?php

namespace App\Models;

use App\Models\UserTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTemplateTrack extends Model
{
    use HasFactory;
    
    public function userTemplate()
    {
       return $this->belongsTo(UserTemplate::class,'user_template_id');
    }
    
}