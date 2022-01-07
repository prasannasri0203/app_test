<?php

namespace App\Models\Superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
class Tax extends Model
{
    use HasFactory,SoftDeletes;

    use Sortable; 
    protected $table = "taxes";
    protected $fillable = [
            'state_id',
            'gst',
            'pst',
            'hst',
            'qst',
            'status'
	];

     public $sortable = [
       'state_id',
            'gst',
            'pst',
            'hst',
            'qst',
            'status'];

    public function state()
    {
      return $this->belongsTo('App\Models\State','state_id','id');
    }

	public function saveTax($request){
	   
        $tax = new Tax;
        $tax->state_id = $request->state_id;
        $tax->gst = $request->gst;
        $tax->pst = $request->pst;
        $tax->hst = $request->hst;
        $tax->qst = $request->qst;
        $tax->status = $request->status;
        $tax->save();
        $status = "Tax Added Successfully";
  	    return $status;
    }

    public function updateTax($request,$id){

        $tax = Tax::findOrFail($id);
        $tax->state_id = $request->state_id;
        $tax->gst = $request->gst;
        $tax->pst = $request->pst;
        $tax->hst = $request->hst;
        $tax->qst = $request->qst;
        $tax->status = $request->status;
        $tax->save();
        $status = "Tax Updated Successfully";
        return $status;
    }
}
