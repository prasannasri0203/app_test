<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Superadmin\TemplateCategory;
use App\Models\Superadmin\Template;
use App\Models\UserTemplate;


class TemplateController extends Controller
{
    public function __construct()
    {
         $this->Template = new Template();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $template['module'] = 'Template';
        $name = (isset($_GET['name']) && $_GET['name'] != '') ? $_GET['name'] : ''; 
        $temp_cat_id = (isset($_GET['temp_cat_id']) && $_GET['temp_cat_id'] != '') ? $_GET['temp_cat_id'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
        $temp_category=[];
        $templates = Template::with('templateCategory')->sortable();  

        if ($name != '') {
                $templates->where('template_name', 'like', '%' . $name . '%');
            }
 
         if ($temp_cat_id != '') {
                $templates->where('template_category_id', 'like', '%' . $temp_cat_id . '%');
            } 
        if ($status != '') {
                $templates->where('status', 'like', '%' . $status . '%');
            } 
             $templates =  $templates->orderBy('id', 'DESC')->paginate(20);
             $temp_category=TemplateCategory::all(); 
        return view('super-admin.template.template-index',compact('templates','temp_category'),['Module'=>$template]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $template_add['module'] = 'Add';
        $tcategorys = TemplateCategory::where('status',1)->get();
        return view('super-admin.template.template-create',['Module'=>$template_add],compact('tcategorys'));
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
            'template_category_id'=> 'required',
            'name' => 'required',
            'status' => 'required', 
        ]); 
        $status = $this->Template->saveTemplate($request); 
        $url = url('/template');
        return redirect($url)->with('status',$status);
    }

    public function storeTcat(Request $request){
        $template_category = new Template;
        $template_category->template_category_id = $request->temp_category_id;
        $template_category->template_name = $request->add_temp_name;
        $template_category->status = 1;
        $template_category->save();
        return redirect('/SA-flowchart/?default='.$template_category->id);
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
        $template_applylist=UserTemplate::where('template_id',$id)->get();        
        if(count($template_applylist)==0)
        {  
            $template = Template::find($id);
            $template->delete();
            $status = 'Template Deleted Successfully';
            return redirect('/template')->with('status',$status);
        }
        else{ 
            $failure='In this Template, user has been applied, so you can not able to delete!';
            return redirect('/template')->with('failure',$failure);
        } 
    }
}
