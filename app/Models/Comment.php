<?php

namespace App\Models;

use App\Models\FlowchartProject;
use App\Models\UserTemplateTrack;
use App\Models\UserTemplate;
use Laravel\Sanctum\HasApiTokens;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasApiTokens, HasFactory, Notifiable; 
    use Sortable; 
    
    public function userTemplate()
    {
       return $this->belongsTo(UserTemplate::class,'user_template_id');
    }
    public function user()
    {
       return $this->hasOne('App\Models\User','id','user_id');
    }
    public function degradedUser()
    {
       return $this->hasOne('App\Models\DegradedSubUser','user_id','user_id');
    }
}
