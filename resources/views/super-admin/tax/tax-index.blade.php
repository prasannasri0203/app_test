<?php 
$state_id = (isset($_GET['state_id']) && $_GET['state_id'] != '') ? $_GET['state_id'] : ''; 
$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';  
?>
@extends('layouts.header')
@section('content')
<style type="text/css">
.distict_table thead tr th {
    width:83px;
}
</style>
<div class="inner-wrapper-g">
    <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
        <div class="main_content">
            <div class="main_head">
                  @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    </div>
                @endif
             <div class="menue_first_head">
                <div class="list_head">
                    <h1>Manage Tax</h1>
                </div>
                <div class="distict_btn">
                    <a href="{{URL('add-tax')}}"><button class="btn blue_btn">Add Tax</button></a>
                </div>
            </div>
        </div> 
                <form action="{{URL::to('tax')}}" autocomplete="off" method="get" >
                    <input type="hidden" name="base_url" value="{{url('/')}}"> 
                 <div class="filter_by_sec">
                    <select class="form-pik-er"  name="state_id">
                            <option value="">Select</option>
                            @foreach($states as $value)
                               <option value="{{$value->id}}"   @if($state_id==$value->id) selected @endif>{{$value->states_name}}</option>
                            @endforeach
                    </select>                    
                    <select class="form-pik-er" name="status">
                        <option value="">Select Status</option>
                        <option value="1" @if($status=="1") selected @endif>Active</option>
                        <option value="0" @if($status=="0") selected @endif>Inactive</option>
                    </select>
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{URL::to('tax')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                    </div>
                </div>
            </div>
        </form> 

<div class="table_main_dist table-responsive">
    <table class="distict_table">
        <thead>
            <tr class="tab">
                <th>S.NO </th>  
                 <th>@sortablelink('state.states_name','STATE NAME')</th> 
                   <th>@sortablelink('gst','GST(%)')</th> 
                   <th>@sortablelink('pst','PST(%)')</th> 
                   <th>@sortablelink('hst','HST(%)')</th> 
                   <th>@sortablelink('qst','QST(%)')</th> 
                   <th>@sortablelink('status','STATUS(%)')</th>  
                <th class="cen">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
             @if(count($taxes) > 0)
            @foreach($taxes as $key=>$tax)
            <tr>
                <td>{{ $key+1}}</td>  
                <td>{{$tax->state->states_name}}</td>
                <td>{{$tax->gst}}</td>
                <td>{{$tax->pst}}</td>
                <td>{{$tax->hst}}</td>
                <td>{{$tax->qst}}</td>
                
                <td>@if($tax->status=='1') ACTIVE @else INACTIVE @endif</td>
                
                <td>
                    <div class="table_last">
                        <span><a href="{{url('edit-tax/'.$tax->id)}}"><img src="images/Edit.png" title="Edit"></a></span>
                        <span><a Onclick="deletetax(this);" data-delete-id="{{$tax->id}}"><img src="images/Delete.png"></a></span>
                    </div>
                </td>
            </tr> 
            @endforeach
            @else
            <tr> 
                <td class="text_cen" colspan="8">No Tax Available</td>
            </tr>
            @endif
        </tbody>
    </table>        
</div> 
</div> 
</div>
<div class="table-footer"> 
 <div class="col-xs-12 text-right" align="left">
    {{ $taxes->links()}}
</div>
</div>
</div>
<script>
    function deletetax(thisval){
	var deleteid    =   $(thisval).attr('data-delete-id');
	if(confirm('Are You Sure You want delete the tax?')){
		var baseurl				=	$('input[name="base_url"]').val();
		var redirecturl			=	baseurl+'/delete-tax/'+deleteid;
		window.location.href	=	redirecturl;
	}
}
</script>
@endsection