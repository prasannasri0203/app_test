<?php 
$color_name = (isset($_GET['color_name']) && $_GET['color_name'] != '') ? $_GET['color_name'] : '';
$background_color = (isset($_GET['background_color']) && $_GET['background_color'] != '') ? $_GET['background_color'] : ''; 
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
             <div class="menue_first_head">
                <div class="list_head">
                   <h1>Manage Themes </h1>
                </div>
                <div class="distict_btn">
                    @if($showbtn == 1)
                    <a href="{{URL('add-theme')}}"><button class="btn blue_btn">Add Theme</button></a>
                    @endif
                </div>
            </div>
        </div> 
                <form action="{{URL::to('themes')}}" autocomplete="off" method="get" >
                    <input type="hidden" name="base_url" value="{{url('/')}}"> 
                 <div class="filter_by_sec">
                    <input type="text" placeholder="Theme Name" name="color_name" value="{{$color_name}}">
                    <input data-jscolor="{}" placeholder="Background Color" name="background_color" value="{{$background_color}}"> 
                    <select class="form-pik-er" name="status">
                        <option value="">Select Status</option>
                        <option value="1"  @if($status=="1") selected @endif>Active</option>
                        <option value="0"  @if($status=="0") selected @endif>Inactive</option>
                    </select>
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{URL::to('themes')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                    </div>
                </div>
            </div>
        </form> 

<div class="table_main_dist table-responsive">
    <table class="distict_table">
        <thead>
            <tr class="tab">
               <tr class="tab">
                <th>S.NO </th>  
                <th>@sortablelink('color_name','THEME NAME')</th>
                <th>@sortablelink('background_color','BACKGROUND COLOR')</th>                
                <th>@sortablelink('status','STATUS')</th> 
                <th class="cen">ACTIONS</th> 
            </tr>
        </thead>
        <tbody>
           @if(count($themes) > 0)
                @foreach($themes as $key=>$theme)
                <tr>
                    <td >{{ $key + $themes->firstItem()}}</td>  
                    <td>{{ucfirst($theme->color_name)}}</td>  
                    <td >{{$theme->background_color}}</td>                    
                    <td>@if($theme->status=='1') Active @else Inactive @endif</td> 
                    
                    <td>
                        <div class="table_last">
                    <span><a href="{{url('/add-theme/'.$theme->id)}}"><img src="images/Edit.png" title="Edit"></a></span>
                      @if(count($themes) <7)        <span><a onclick="return confirm('Are you sure to delete?')" href="{{route('delete-theme',[$theme->id])}}"><img src="images/Delete.png"></a></span>
                    @endif

                        </div>
                    </td>

                </tr> 
                @endforeach
            @else
            <tr> 
                <td class="text_cen"  colspan="5">No Themes Available</td>
            </tr>
            @endif
        </tbody>
    </table>        
</div> 
</div> 
</div>
<div class="table-footer"> 
 <div class="col-xs-12 text-right" align="left">
     {{ $themes->appends([\Request::except('page'),'color_name'=>$color_name,'background_color'=>$background_color,'status'=>$status])->render('vendor.pagination.default')}}
</div>
</div>
</div> 
@endsection

<script  src="{{asset('js/jscolor.js')}}" type="text/javascript"></script>
<script  src="{{asset('js/jscolor.min.js')}}" type="text/javascript"></script>
