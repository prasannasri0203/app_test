<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserTemplate;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlowchartProject extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sortable; 
         

    public $sortable = [
        'project_name', 'description', 'admin_id','editor_id','approver_id','viewer_id','created_by','status','created_at','team_user_id'];


    public function admin()
    {
       return $this->belongsTo(User::class,'admin_id');
    }

    public function editor()
    {
       return $this->belongsTo(User::class,'editor_id');
    }
    public function approver()
    {
       return $this->belongsTo(User::class,'approver_id');
    }
    public function viewer()
    {
       return $this->belongsTo(User::class,'viewer_id');
    }

    public function createdBy()
    {
       return $this->belongsTo(User::class,'created_by');
    }

    public function userTemplate()
    {
       return $this->hasMany(UserTemplate::class,'project_id');
    }
    public function team()
    {
       return $this->belongsTo(User::class,'team_user_id');
    }

    
}
