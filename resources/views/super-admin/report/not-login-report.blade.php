<?php 
$user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
$email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
$mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
?>
@extends('layouts.header')
@section('content')
    <div class="inner-wrapper-g">
        <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
            <div class="main_content">
                @if (session('status'))
                  <div class="alert alert-success" role="alert">
                      {{ session('status') }}
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  </div>
                @endif
                <div class="main_head">
                   <div class="menue_first_head">
                        <div class="list_head">
                            <h1>Report Still Not Login Users </h1>
                        </div> 
                    </div>
                </div>    
                <form action="{{URL::to('login-not-report')}}" autocomplete="off" method="get" >    
                <div class="filter_by_sec">
                    <input type="text" placeholder="Name" name="user_name" value="{{$user_name}}" >
                    <input type="text" placeholder="Email Address" name="email" value="{{$email}}" >
                    <input type="text" placeholder="Contact Number" name="mobile" value="{{$mobile}}" > 
                    <select class="form-pik-er" name="status">
                        <option value="">Select Status</option>
                        <option value="1"  @if($status=="1") selected @endif>Active</option>
                        <option value="2" @if($status=="0") selected @endif>Inactive</option>
                    </select>
                    
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button> 
                         <a href="{{url('/login-not-report')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                     </div>
                </div>
                 </form>
    
                <div class="table_main_dist table-responsive">
                    <table class="distict_table">
                        <thead>
                            <tr class="tab">
                                <th>S.NO </th> 
                                <th>@sortablelink('name','FULL NAME')</th>
                                <th>@sortablelink('email','EMAIL ADDRESS')</th>
                                <th>@sortablelink('userDetail.contact_no','CONTACT NUMBER')</th>
                                <th>@sortablelink('created_at','CREATE DATE')</th>
                              <!--   <th>LOGIN DATE DIFFERENT</th> -->
                                <th>@sortablelink('userDetail.organization_name','ORGANIZATION NAME')</th>
                                <th> RENEWAL DATE</th>
                                <!-- <th class="cen">ACTIONS</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            
                            @if(count($users) > 0)
                                @foreach($users as $key=>$user)
                                <tr>
                                    <td>{{$key + $users->firstItem()}}</td>  
                                    <td>{{ucfirst($user->name)}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>@if($user['userDetail']['contact_no'] != null)
                                        {{$user['userDetail']['contact_no']}}
                                        @endif
                                    </td>
                                   <td>{{ optional($user->created_at)->format('d-m-Y') }}
                                     </td>
                                    <!--  <td>
                                        @if($user->login_at != null)
                                      {{ \Carbon\Carbon::parse($user->login_at)->diffForHumans() }}
                                      @endif
                                    </td> -->
                                    <td>@if($user['userDetail']['organization_name'] != null)
                                    {{ucfirst($user['userDetail']['organization_name'])}}
                                    @endif
                                    </td>
                                    <td>@if($user['userRenewalDatail']['renewal_date'] != null)  
                                        {{ date('d-m-Y', strtotime($user['userRenewalDatail']['renewal_date'])) }}
                                        @endif
                                    </td>
                                   <!--   <td>
                                        <div class="table_last">
                                            <span><a  data-toggle="modal" data-target="#viewModalCenter{{$user->id}}">  <img src="{{asset('images/front_images/eye.png')}}"></a></span>
                                         
                                        </div>
                                    </td> -->
                                </tr>
                                @endforeach
                            @else
                                <tr> 
                                    <td class="text_cen" colspan="9">No Login Users Available</td>
                                </tr>
                            @endif
                            
                        </tbody>
                    </table>        
                </div> 
            </div> 
 

<div class="table-footer"> 
                <div class="table-footer"> 
   <div class="col-xs-12 text-right" align="left">
    {{ $users->appends(['user_name' =>$user_name,'email'=>$email,'mobile'=>$mobile,'status'=>$status])->links('vendor.pagination.default')}}
</div>
</div>
                
            </div>
           
        </div>
    </div>
@endsection