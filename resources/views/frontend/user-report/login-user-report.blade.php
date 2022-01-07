
<?php 
$user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
$location = (isset($_GET['location']) && $_GET['location'] != '') ? $_GET['location'] : '';
$email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
$mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : '';
$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : '';
$role = (isset($_GET['role']) && $_GET['role'] != '') ? $_GET['role'] : '';
$login_status = (isset($_GET['login_status']) && $_GET['login_status'] != '') ? $_GET['login_status'] : '';
$manage_status = (isset($_GET['manage_status']) && $_GET['manage_status'] != '') ? $_GET['manage_status'] : '';
$to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : '';
$end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : '';  
$export = (isset($_GET['export']) && $_GET['export'] != '') ? $_GET['export'] : '0';  
?>
@extends('layouts.frontend.header')
@section('content')
<div class="container-fluid px-4 main_part">
     <div class="t-h">
        <a href="{{url('/user-list')}}">
            <h4 class="Acc_Setting bl">User Log Report </h4>
        </a> 
            <div class="distict_btn"> 
            </div> 
      
    </div>
    @if(session('status')) 
    <div class="col-md-12">
     <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('status') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
</div>
    @endif
     <div class="inner-wrapper-g">            
        <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
        <form action="{{url('/user-login-report')}}" autocomplete="off" method="get" >
                <input type="hidden" name="base_url" value="{{url('/')}}">                      
                 <div class="filter_by_sec long-filter list-filter-div">
                   <input type="text" placeholder="Name" name="user_name" value="{{$user_name}}" >
                    <input type="text" placeholder="Email Address" name="email" value="{{$email}}" > 
                    <input type="text" placeholder="Contact Number" name="mobile" value="{{$mobile}}" > 
                    <input type="date" placeholder="To Date" class="datetimepicker" name="to_date" value="{{$to_date}}" >
                    <input type="date" placeholder="End Date" name="end_date" value="{{$end_date}}" >
                    <select class="form-pik-er" name="login_status">
                        <option value="">Select login</option>
                        <option value="1"  @if($login_status=="1") selected @endif>Login</option>
                        <option value="2" @if($login_status=="2") selected @endif>Not Login</option>
                    </select>
                    <select class="form-pik-er" name="status">
                        <option value="">Select Status</option>
                        <option value="1"  @if($status=="1") selected @endif>Active</option>
                        <option value="2" @if($status=="2") selected @endif>Inactive</option>
                    </select>
                     @if(Auth::user()->user_role_id ==4)
                        <select class="form-pik-er manage_status" name="manage_status"> 
                            <option value="1"  @if($manage_status=="1") selected @endif>Team User</option>
                            <option value="2" @if($manage_status=="2") selected @endif>Sub User</option>
                        </select>
                    @endif
                     <select class="form-pik-er role hide"  name="role">
                       <option value="">Role</option>
                       @foreach($roleList as $list)
                       <option value="{{$list->id}}"  @if($role==$list->id) selected @endif>{{$list->role}}</option>
                       @endforeach
                   </select> 
                    <input type="hidden" name="export" value="{{$export}}" id="export_val">
                    <div class="distict_btn  list-filter">
                        <button class="btn blue_btn role_user_btn">Filter</button>
                        <a href="{{url('/user-login-report')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                        <button class="btn blue_btn export_btn" style="background:#ffc107;"> Export</button>  
                     </div>
                </div>
         </form> 
                    
            <div class="container-fluid p-0 mt-2 table-responsive">
                <table class="distict_table">
                    <thead>
                        <tr class="tab"> 
                            <th>S.No </th>
                            <th>@sortablelink('name','FULL NAME')</th>
                            <th>@sortablelink('email','EMAIL ADDRESS')</th>
                            <th>@sortablelink('userDetail.contact_no','CONTACT NUMBER')</th> 
                            <th>@sortablelink('login_at','LOGIN DATE')</th>  
                            <th>@sortablelink('status','STATUS')</th>

                        </tr>
                    </thead>
                    <tbody>

                      @if(count($users) > 0)
                      @foreach($users as $key=>$user)
                      <tr>
                        <td>{{$key + $users->firstItem()}}</td>  
                        <td> {{ucfirst($user->name)}} </td>
                        <td> {{$user->email}} </td>
                        <td> {{$user->userDetail->contact_no}} </td> 
                        <td>{{ optional($user->login_at)->format('m/d/Y h:i a') }}
                        <!--  @if($user->login_at != null)
                         /{{ \Carbon\Carbon::parse($user->login_at)->diffForHumans() }}
                         @endif -->
                       </td>  
                        <td> @if($user->status ==1) Active @else Inactive @endif </td>


                     </tr>   
                     @endforeach

                                    @else
                                    <tr> 
                                      <td class="text_cen" colspan="6">No Login Users Available</td>
                                    </tr>
                                    @endif
                                  </tbody>
                </table>
                
            </div>
        </div>

        <div class="table-footer"> 
    <div class="col-xs-12 text-right" align="left"> 
       {{ $users->appends(['user_name' =>$user_name,'email'=>$email,'mobile'=>$mobile,'status'=>$status,'login_status'=>$login_status,'manage_status'=>$manage_status])->links('frontend.pagination.default')}}
</div>
    </div>
</div>
@endsection
 