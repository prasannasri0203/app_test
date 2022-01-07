<?php
$name = (isset($_GET['name']) && $_GET['name'] != '') ? $_GET['name'] : ''; 
$temp_cat_id = (isset($_GET['temp_cat_id']) && $_GET['temp_cat_id'] != '') ? $_GET['temp_cat_id'] : ''; 
$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : ''; 
?>
@extends('layouts.header')
@section('content')
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
                @endif   @if (session('failure'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('failure') }}
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    </div>
                @endif
             <div class="menue_first_head">
                <div class="list_head">
                    <h1>Manage Template</h1>
                </div>
                <div class="distict_btn">
                    <span href="#addtemplate-model" data-toggle="modal" class="btn blue_btn" style="padding: 9px;">Add Template</span>
                    <!-- <a href="{{URL('add-template')}}"><button class="btn blue_btn">Add Template</button></a> -->
                </div>
            </div>
        </div> 
                <form action="{{URL('template')}}" autocomplete="off" method="get" >
                <input type="hidden" name="base_url" value="{{url('/')}}">                      
                <div class="filter_by_sec">
                    <input type="text" placeholder="Name" name="name"  value="{{$name}}"> 
                    <select class="form-pik-er" name="temp_cat_id">
                        <option value=""> Select Category</option>
                        @foreach($temp_category as $key=> $temp_cat)
                            <option value="{{ $temp_cat->id}}"  @if($temp_cat_id==$temp_cat->id) selected @endif>{{ $temp_cat->name }}</option>
                        @endforeach 
                    </select>
                     <select class="form-pik-er" name="status">
                        <option value="">Select Status</option>
                        <option value="1"  @if($status=="1") selected @endif>Active</option>
                        <option value="0"  @if($status=="0") selected @endif>Inactive</option>
                    </select>
                    
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{URL('template')}}">
                            <button type="button" class="btn blue_btn" style="background-color:green">Reset </button>
                        </a>
                     </div>
                </div>                
            </div>
        </form> 


 <!-- add template-->

 

<!-- add template update--> 
<div class="table_main_dist table-responsive">
    <table class="distict_table">
        <thead>
            <tr class="tab">
                <th>S.NO </th>   
                   <th>@sortablelink('templateCategory.name','TEMPLATE CATEGORY NAME')</th> 
                   <th>@sortablelink('template_name','TEMPLATE NAME')</th> 
                    <th>@sortablelink('created_at','CREATED DATE')</th>  
                   <th>@sortablelink('status','STATUS')</th> 
                <th class="cen">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
             @if(count($templates) > 0)
            @foreach($templates as $key=>$template)
            <tr>
                <td>{{ $key + $templates->firstItem()}}</td>  
                <td>{{ $template->templateCategory['name'] }}</td> 
                <td>{{$template->template_name}}</td>
                <td>{{date('m/d/Y', strtotime($template->created_at))}}</td> 
                <td>@if($template->status=='1') Active @else Inactive @endif</td> 
                <td>
                    <div class="table_last">
                        <span><a href="{{url('/SA-flowchart/?default='.$template->id)}}" class="btn blue_btn "><img src="images/Edit.png" title="Edit"></a></span>
                        <span><a Onclick="deletetemplate(this);" data-delete-id="{{$template->id}}"><img src="images/Delete.png"></a>
                        </span>
                    </div>
                </td>
            </tr> 
            @endforeach
            @else
            <tr> 
                <td class="text_cen" colspan="6">No Template Available</td>
            </tr>
            @endif

        </tbody>
    </table>        
</div> 
</div> 
</div>

<div id="addtemplate-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">  
       <form method="POST" action="{{route('add-tcategory-tcat')}}">
        @csrf
        <div class="modal-header flex-column">       
          <h4 class="modal-title w-100">Project Mapping</h4>  
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">     
          <div class="lbl_text"> 
            <div class="form-group">  
              <input type="hidden" value="{{url('/')}}" id="baseurl">  
              <div class="input_model_select">
                <select name="temp_category_id" required="" class="form-control">
                  <option value="">Select Project</option> 
                  @foreach($temp_category as $project) 
                  <option value="{{$project->id}}">{{$project->name}}</option>
                  @endforeach 
                </select>

              <input type="text" name="add_temp_name" placeholder="Flow Chart Name" id="add_temp_name" style="margin-top:10px;" class="form-control" autocomplete="off"> 
              </div> 
            </div>
            
            <div class="modal-footer"> 
              <input type="submit" class="btn btn-warning" id="comment_btn" value="Ok" />   
            </div> 
          </div>  
        </div>
      </form>
    </div>
  </div>   
</div>  
 
<div class="table-footer"> 
 <div class="col-xs-12 text-right" align="left">
     {{ $templates->appends([\Request::except('page'),'template_name'=>$name,'template_category_id'=>$temp_cat_id,'status'=>$status])->render('vendor.pagination.default')}}
</div>
</div>
</div>
<script>
    function deletetemplate(thisval){
	var deleteid    =   $(thisval).attr('data-delete-id');
	if(confirm('Are You Sure You want delete the Template?')){
		var baseurl				=	$('input[name="base_url"]').val();
		var redirecturl			=	baseurl+'/delete-template/'+deleteid;
		window.location.href	=	redirecturl;
	}
}
</script>
@endsection