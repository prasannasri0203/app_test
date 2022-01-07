<?php

namespace App\Models;

use App\Models\UserTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateShare extends Model
{
    use HasFactory;


    public function userTemplate()
    {
       return $this->belongsTo(UserTemplate::class,'user_template_id');
    }
    public function userDetail()
    {
       return $this->belongsTo(User::class,'user_id');
    }
}