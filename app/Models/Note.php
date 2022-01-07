<?php

namespace App\Models;

use App\Models\FlowchartProject;
use App\Models\UserTemplateTrack;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{ 
    use HasApiTokens, HasFactory, Notifiable; 
    use Sortable; 
    public function userTemplate()
    {
       return $this->hasOne(UserTemplate::class,'user_template_id');
    }
    public function user()
    {
       return $this->hasOne('App\Models\User','id','user_id');
    }
   
}
