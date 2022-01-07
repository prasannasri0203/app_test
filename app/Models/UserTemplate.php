<?php

namespace App\Models;

use App\Models\FlowchartProject;
use App\Models\Comment;
use App\Models\User;
use App\Models\UserTemplateTrack;
use App\Models\FlowchartMapping;
use Laravel\Sanctum\HasApiTokens;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTemplate extends Model
{

    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    use Sortable; 
         
     public $sortable = [
       'file_name', 'user_id', 'template_name','editor_status','is_approved','editor_review','created_at','is_approved'];


    public function userTemplateTrack()
    {
       return $this->hasOne(UserTemplateTrack::class,'user_template_id');
    }
    
    public function flowchartProject()
    {
       return $this->belongsTo(FlowchartProject::class,'project_id');
    } 
    public function user()
    {
       return $this->belongsTo(User::class,'user_id');
    } 
    public function comment()
    {
       return $this->hasMany(Comment::class,'user_template_id');
    }
    public function flowchartMapping(){
      return $this->hasMany(FlowchartMapping::class,'user_template_id')->orderBy('order_number');
    }
    public function userTemplateStatus()
    { 
      $status="";
      if($this->status ==0 &&  $this->editor_status ==0 && $this->is_approved ==0)
        $status = "<span class='btn btn-info fc_status' style='font-size: 15px;color: #fff; background-color: #d94e6efa;border-color: #d94e6efa;'> Draft</span>" ;
      if($this->status ==1 &&  $this->editor_status ==0 && $this->is_approved ==0)
        $status = "<span class='btn btn-primary fc_status' style='font-size: 15px;'> Active</span>" ;
      elseif($this->status == 1 && $this->is_approved ==1)
        $status = "<span class='btn btn-success fc_status' style='font-size: 15px;'> Approved</span>" ;
      elseif($this->status == 1 && $this->is_approved ==2)
        $status = "<span class='btn btn-danger fc_status' style='font-size: 15px;'> Rejected</span>" ;      
      elseif($this->status ==1 && ($this->editor_status == 1 || $this->editor_review ==1) && $this->is_approved==0)
        $status = "<span class='btn btn-warning fc_status' style='font-size: 15px;'> Waiting for Approval</span>" ;
      elseif($this->status ==1 && $this->editor_status == 2 && $this->is_approved==0)
        $status = "<span class='btn btn-info fc_status' style='font-size: 15px;'> Request for Change</span>" ;      
      return $status;

    }
    
}
