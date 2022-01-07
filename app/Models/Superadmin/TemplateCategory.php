<?php

namespace App\Models\Superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class TemplateCategory extends Model
{
    use HasFactory,SoftDeletes;
      use Sortable;

    protected $table = "template_categorys";
    protected $fillable = [
		'name',
        'status' 
	];
    public $sortable = ['id','name','status','created_at','updated_at'];

    public function saveTcategory($request){
        $chk = TemplateCategory::pluck('name')->toArray();
        if(in_array($request->name,$chk)){
            $status = "Template Category Name Already Exists";
        }else{
            $template_category = new TemplateCategory;
            $template_category->name = $request->name;
            $template_category->status = $request->status;
            $template_category->save();
            $status = "Template Category Added Successfully";
        }        
        return $status;
    }

    public function updateTcategory($request){
        $chk = TemplateCategory::where('id','!=',$request->template_categorys_id)->pluck('name')->toArray();
        if(in_array($request->name,$chk)){
            $status = "Template Category Name Already Exists";
        }else{
            TemplateCategory::where('id',$request->template_categorys_id)
            ->update(['name' => $request->name,'status'=>$request->status]);
               $status = "Template Category Updated Successfully";
        }        
        return $status;
    }
}
