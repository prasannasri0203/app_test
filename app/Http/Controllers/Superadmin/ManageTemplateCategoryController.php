<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Superadmin\TemplateCategory;
use App\Models\Superadmin\Template;

class ManageTemplateCategoryController extends Controller
{
    public function __construct()
    {
         $this->TemplateCategory = new TemplateCategory();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tcategory['module'] = 'TemplateCategory';
        $name = (isset($_GET['name']) && $_GET['name'] != '') ? $_GET['name'] : ''; 
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : ''; 
        $categorys = TemplateCategory::sortable();  

        if ($name != '') {
            $categorys->where('name', 'like', '%' . $name . '%');
        } 
        if ($status != '') {
            $categorys->where('status', 'like', '%' . $status . '%');
        } 
        $categorys =  $categorys->orderBy('id', 'DESC')->paginate(20);  
        return view('super-admin.template-category.tcategory-index',compact('categorys'),['Module'=>$tcategory]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $cat_id = '';
        $temp_data = '';
        if($request->segment(2) !=''){
         $cat_id= $request->segment(2);
         $temp_data= TemplateCategory::where('id',$cat_id)->first();
        }
        $tcategory_add['module'] = 'Add';
        return view('super-admin.template-category.tcategory-create',compact('cat_id','temp_data'),['Module'=>$tcategory_add]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required', 
        ]); 
        if(isset($request->template_categorys_id) && $request->template_categorys_id !=''){
           $status = $this->TemplateCategory->updateTcategory($request);
        }else{
           $status = $this->TemplateCategory->saveTcategory($request); 
        }

        $url = url('/tcategory');
        if($status == 'Template Category Name Already Exists'){
            return redirect()->back()->with('failure',$status);
        }else{
            return redirect($url)->with('status',$status);
        }          
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        $temp_cat_applylist=Template::where('template_category_id',$id)->get();
       
        if(count($temp_cat_applylist)==0)
        {  
           $tcategory = TemplateCategory::find($id);
            $tcategory->delete();
            $status = 'TemplateCategory Deleted Successfully';
            return redirect('/tcategory')->with('status',$status);
        }
        else{ 
          $failure='In this Template Category, user has been applied, so you can not able to delete!';
          return redirect('/tcategory')->with('failure',$failure);
        } 
    }
}
