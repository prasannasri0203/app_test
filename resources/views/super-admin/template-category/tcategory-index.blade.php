<?php 
 $name = (isset($_GET['name']) && $_GET['name'] != '') ? $_GET['name'] : ''; 
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
                @endif
                @if (session('failure'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('failure') }}
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    </div>
                @endif
             <div class="menue_first_head">
                <div class="list_head">
                    <h1>Manage Template Category </h1>
                </div>
                <div class="distict_btn">
                    <a href="{{URL('add-tcategory')}}"><button class="btn blue_btn">Add Category</button></a>
                </div>
            </div>
        </div> 
                <form action="{{URL('tcategory')}}" autocomplete="off" method="get" >
                    <input type="hidden" name="base_url" value="{{url('/')}}"> 
                     
                 <div class="filter_by_sec">
                    <input type="text" placeholder="Category Name" name="name"  value="{{$name}}">  
                    <select class="form-pik-er" name="status">
                        <option value="">Select Status</option>
                        <option value="1"  @if($status=="1") selected @endif>Active</option>
                        <option value="0"  @if($status=="0") selected @endif>Inactive</option>
                    </select>
                    
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{URL('tcategory')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                     </div>
                </div>
                
            </div>

        </form> 


<div class="table_main_dist table-responsive">
    <table class="distict_table">
        <thead>
            <tr class="tab">
                <th>S.NO </th>   
                   <th>@sortablelink('name','TEMPLATE CATEGORY NAME')</th> 
                   <th>@sortablelink('created_at','CREATED DATE')</th> 
                   <th>@sortablelink('status','STATUS')</th> 
                <th class="cen">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
             @if(count($categorys) > 0)
            @foreach($categorys as $key=>$category)
            <tr>
                <td>{{ $key + $categorys->firstItem()}}</td>   
                <td >{{$category->name}}</td>
                <td>{{date('m/d/Y', strtotime($category->created_at))}}</td> 
                <td>@if($category->status=='1') Active @else Inactive @endif</td> 
                <td>
                    <div class="table_last">
                        <span><a href="{{url('add-tcategory/'.$category->id)}}"><img src="images/Edit.png" title="Edit"></a></span>
                        <span><a Onclick="deletetcategory(this);" data-delete-id="{{$category->id}}"><img src="images/Delete.png"></a>
                        </span>
                    </div>
                </td>
            </tr> 
            @endforeach
            @else
            <tr> 
                <td  class="text_cen" colspan="5">No Template Category Available</td>
            </tr>
            @endif

        </tbody>
    </table>        
</div> 
</div> 
</div>
 
<div class="table-footer"> 
 <div class="col-xs-12 text-right" align="left">
     {{ $categorys->appends([\Request::except('page'),'name'=>$name,'status'=>$status])->render('vendor.pagination.default')}}
</div>
</div>
</div>
<script>
    function deletetcategory(thisval){
	var deleteid    =   $(thisval).attr('data-delete-id');
	if(confirm('Are You Sure You want delete the TemplateCategory?')){
		var baseurl				=	$('input[name="base_url"]').val();
		var redirecturl			=	baseurl+'/delete-tcategory/'+deleteid;
		window.location.href	=	redirecturl;
	}
}
</script>
@endsection