<?php

namespace App\Models\Superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
class Template extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "templates";

      use Sortable;
    protected $fillable = [
		    'template_category_id',
        'template_name',
        'status',
        'image' 
	];
      public $sortable = ['id','template_category_id','status','template_name','image','created_at'];

  public function templateCategory()
    {
       return $this->hasOne('App\Models\Superadmin\TemplateCategory','id','template_category_id');
    }
  public function saveTemplate($request){
    $chk = Template::pluck('template_name')->toArray();
    if(in_array($request->name,$chk)){
        $status = "Template Category Name Already Exists";
    }else{
        $template = new Template;
        $template->template_name = $request->name;
        $template->template_category_id = $request->template_category_id;
        $template->status = $request->status;
        $template->save();
        $status = "Template Category Added Successfully";
    }
    
    return $status;
}
}
