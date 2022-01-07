<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Superadmin\Tax;
use App\Models\State;
use Kyslik\ColumnSortable\Sortable;

class TaxController extends Controller
{

    use Sortable;

    public function __construct()
    {
         $this->tax = new Tax();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $tax_module['module'] = 'Tax';
        $state_id = (isset($_GET['state_id']) && $_GET['state_id'] != '') ? $_GET['state_id'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : ''; 
        $states = State::get();  
         $taxes = Tax::with('state')->when(request()->has('state_id') && request()->state_id,function($query){
                   $query->where('state_id','=',request()->state_id);
                })->when(request()->has('status') && (request()->status == 1 ||  request()->status == 0 ),function($query){
                    $query->where('status','like', '%' . request()->status. '%');
                });
        $taxes = $taxes->sortable()->orderBy('created_at', 'desc')->paginate(20);  

       // $taxes =Tax::select('taxes.*','states.states_name')->join('states', 'taxes.state_id', '=', 'states.id')->orderBy('states_name', $orderBy)->paginate(20);
        return view('super-admin.tax.tax-index',compact('taxes','states'),['Module'=>$tax_module]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tax_add['module'] = 'Tax';
        $tax_add['states'] = State::get();
        return view('super-admin.tax.tax-create',compact('tax_add'),['Module'=>$tax_add]);
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
            'state_id'=> 'required',
            'gst' => 'nullable|numeric',
            'pst'=>'nullable|numeric',
            'hst' => 'nullable|numeric',
            'qst'=>'nullable|numeric',
            'status' => 'required', 
        ]);
        $chkTax = Tax::where('state_id',$request->state_id)->first();
        if($chkTax){
            return redirect()->back()->with('exist','The tax is already added to this state');
        }else{
            $status = $this->tax->saveTax($request); 
            $url = url('/tax');
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

    public function filter()
    {
        $tax_module['module'] = 'Tax';
        $state_id = (isset($_GET['state_id']) && $_GET['state_id'] != '') ? $_GET['state_id'] : '';
        $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : ''; 
        $states = State::get();
        
        if(isset($state_id) && $state_id !='' ){
       
            $taxes =Tax::select('taxes.*','states.states_name')
            ->join('states', 'taxes.state_id', '=', 'states.id')->where('taxes.state_id',$state_id)->orderBy('taxes.id', 'DESC')->paginate(20);

        } else if(isset($status) && $status !=''){
       
            $taxes =Tax::select('taxes.*','states.states_name')
            ->join('states', 'taxes.state_id', '=', 'states.id')->where('taxes.status',$status)->orderBy('taxes.id', 'DESC')->paginate(20);
       
        }else{
       
            $taxes =Tax::select('taxes.*','states.states_name')
            ->join('states', 'taxes.state_id', '=', 'states.id')->where('taxes.state_id',$state_id)->where('taxes.status',$status)->orderBy('taxes.id', 'DESC')->paginate(20);
       
        }
        return view('super-admin.tax.tax-index',compact('taxes','states'),['Module'=>$tax_module]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tax_edit['module'] = 'Tax';
        $tax_edit['states'] = State::get();
        $tax_edit['edit_value'] = Tax::select('taxes.*','states.states_name')->join('states', 'taxes.state_id', '=', 'states.id')->where('taxes.id',$id)->first();
        $tax_edit['arr_value'] = array( 1 => 'GST',2 => 'PST',3 => 'HST',4 => 'QST');
        return view('super-admin.tax.tax-edit',compact('tax_edit'),['Module'=>$tax_edit]);
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
        $request->validate([
            'state_id'=> 'required',
            'gst' => 'nullable|numeric',
            'pst'=>'nullable|numeric',
            'hst' => 'nullable|numeric',
            'qst'=>'nullable|numeric',
            'status' => 'required',  
        ]);
        $chkTax = Tax::where('id','!=',$id)->where('state_id',$request->state_id)->first();
        if($chkTax){
            return redirect()->back()->with('exist','The tax is already added to this state');
        }else{
            $status = $this->tax->updateTax($request,$id); 
            $url = url('/tax');
            return redirect($url)->with('status',$status);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tax = Tax::find($id);
        $tax->delete();
        $status = 'Tax Deleted Successfully';
        $url = url('/tax');
        return redirect($url)->with('status',$status);
    }
}

    

