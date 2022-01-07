<?php 
   $project_name = (isset($_GET['project_name']) && $_GET['project_name'] != '') ? $_GET['project_name'] : ''; 
   $status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : ''; 
   $to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : '';
   $end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : '';
   ?>
@extends('layouts.frontend.header')
@section('content')
<style>
</style>
<div class="container-fluid px-4 main_part">
   <div class="t-h">
      <a href="{{route('flowchart-project.index')}}">
         <h4 class="Acc_Setting bl">Manage Flow Chart Projects</h4>
      </a>
      <a href="{{route('flowchart-project.create')}}" class="float-right" >
         <div class="distict_btn">
            <h4 class="Acc_Setting ">
               <button class="btn blue_btn ">Add Project</button>
            </h4>
         </div>
      </a>
   </div>
   @if(session('status'))
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
   <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('status') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
   </div>
  </div>
   @endif
   @if(session('failure')) 
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
   <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('failure') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
   </div>
   </div>
   @endif
   <div class="inner-wrapper-g">
      <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
         <form action="{{route('flowchart-project.index')}}" autocomplete="off" method="get" >
                    <input type="hidden" name="base_url" value="{{url('/')}}"> 
                     
                 <div class="filter_by_sec">
                    <input type="text" placeholder="Project Name " name="project_name"  value="{{$project_name}}"> 
                     <input type="date" placeholder="To Date" class="datetimepicker" name="to_date" value="{{$to_date}}" >
                     <input type="date" placeholder="End Date" name="end_date" value="{{$end_date}}" >
                    <select class="form-pik-er" name="status">
                        <option value="">Select Status</option>
                        <option value="1"  @if($status=="1") selected @endif>Active</option>
                        <option value="0"  @if($status=="0") selected @endif>Inactive</option>
                    </select>
                    
                    <div class="distict_btn  list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{route('flowchart-project.index')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                     </div>
                </div>
         </form>
         <div class="container-fluid p-0 mt-2 table-responsive">
            <input type="hidden" value="{{url('/')}}" id="baseurl">
            <table class="distict_table">
               <thead>
                  <tr class="tab">
                     <th>S.NO </th>
                     <th>@sortablelink('project_name','PROJECT NAME')</th>
                     <th>DATE</th>
                     @if(Auth::user()->user_role_id == 4 || Auth::user()->user_role_id ==1)
                      @if(Auth::user()->user_role_id == 4)
                        <th>@sortablelink('team_user_id','TEAM') </th>
                     @endif
                     <th>@sortablelink('admin_id','ADMIN')</th>
                     @endif
                     <th>@sortablelink('status','STATUS')</th>
                     <th class="cen">ACTIONS</th>
                  </tr>
               </thead>
               <tbody>
                  @if(count($flowchartProject) > 0)
                  @foreach($flowchartProject as $key => $list)
                  <tr>
                     <td>{{ $key + $flowchartProject->firstItem() }}</td>
                     <td> {{ ucwords($list->project_name) }} </td>
                     <td>@if($list->updated_at == null) {{ $list->created_at->format('m/d/Y') }} @else {{ $list->updated_at->format('m/d/Y')}} @endif</td>
                        <?php if(Auth::user()->user_role_id == 4 || Auth::user()->user_role_id ==1){ ?>
                           @if(Auth::user()->user_role_id == 4)
                              @if($list->team_user_id != 0)
                                 <td style="text-transform: capitalize;"> {{ ucfirst((optional($list->team)->name))? ucfirst(optional($list->team)->name):'-'; }}</td>
                              @else
                                 <td >-</td>
                              @endif 
                           @endif                   
                          <td style="text-transform: capitalize;"> {{ ucfirst((optional($list->admin)->name))? ucfirst(optional($list->admin)->name):'-'; }}</td>
                       <?php } ?>
                     <td> @if($list->status ==1) Active @else Inactive @endif </td>
                     <td>
                        <div class="table_last">
                           <form action="{{action('App\Http\Controllers\Frontend\FlowchartProjectController@destroy', $list->id)}}" method="POST">
                              @csrf   @method('DELETE')
                               <span>
                                 <a href="{{url('view-project/'.$list->id)}}" title="View"><img src="{{asset('images/front_images/eye.png')}}"></a>
                               </span>
                              <span>
                              <a href="{{ route('flowchart-project.edit',$list->id) }}"><img src="images/Edit.png" title="Edit"></a>
                              </span>
                              <a title="Delete" > 
                              <button class="btn" id="button" type="submit" onclick="return confirm('Are you sure to delete?')"> <img  type="submit"  src="images/Delete.png" ></button>
                              </a>  
                           </form>
                        </div>
                     </td>
                  </tr>
                  @endforeach
                  @else
                     @if(Auth::user()->user_role_id == 2 || Auth::user()->user_role_id ==3)
                        <tr>
                           <td colspan="2"></td>
                           <td colspan="3">No Flow Charts Project Available</td>
                        </tr>
                     @else
                        @if(Auth::user()->user_role_id == 4)
                           <tr>
                              <td colspan="2"></td>
                              <td colspan="5">No Flow Charts Project Available</td>
                           </tr>
                        @else
                           <tr>
                              <td colspan="2"></td>
                              <td colspan="4">No Flow Charts Project Available</td>
                           </tr>
                        @endif
                     @endif
                  @endif
               </tbody>
            </table>
         </div>
      </div>
   </div>
 
  
   <div class="table-footer">
      <div class="col-xs-12 text-right" align="left">
         {{ $flowchartProject->appends(['project_name' =>$project_name,'to_date'=>$to_date,'end_date'=>$end_date,'status'=>$status])->links('frontend.pagination.default')}}
      </div>
   </div>
</div>
@endsection
@section('script')
<script></script>
@endsection