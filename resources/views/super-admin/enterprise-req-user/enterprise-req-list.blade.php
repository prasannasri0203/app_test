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
                            <h1>Manage Enterprise User Requests</h1>
                        </div>
                        <div class="distict_btn">
                            <a href="{{url('/reject-enterprise-request')}}"><button type="button" class="btn blue_btn" style="background-color: crimson">Rejected User</button></a>
                        </div>
                    </div>
                </div>
                <form method="GET" action="{{ route('enterprise-request') }}" >    
                <div class="filter_by_sec">
                    <input type="text" placeholder="Name" name="user_name"  value="{{$user_name}}">
                    <input type="text" placeholder="Email Address" name="email"  value="{{$email}}">
                    <input type="text" placeholder="Contact Number" name="mobile"  value="{{$mobile}}"> 
                    <!-- <select class="form-pik-er" name="status">
                        <option value="0">Select Status</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                    </select> -->
                    
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{url('/enterprise-request')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                        <!-- <a href="{{url('/reject-enterprise-request')}}"><button type="button" class="btn blue_btn" style="background-color: crimson">Reject Enterprise</button></a> -->
                     </div>
                      </form>
                </div>
    
                <div class="table_main_dist table-responsive">
                    <table class="distict_table">
                        <thead>
                            <tr class="tab">
                                <th>S.NO </th>  
                                <th>@sortablelink('name','FULL NAME')</th>
                                <th>@sortablelink('email','EMAIL ADDRESS')</th>
                                <th>@sortablelink('userDetail.contact_no','CONTACT NUMBER')</th>
                                <th>@sortablelink('userDetail.organization_name','ORGANIZATION NAME')</th> 
                                <th>STATUS</th> 
                                <th class="cen">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($users) > 0)
                                @foreach($users as $key=>$user)
                                <tr>
                                    <td>{{$key + $users->firstItem()}}</td>  
                                    <td>{{ucfirst($user->name)}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user['userDetail']['contact_no']}}</td>
                                    <td>{{ucfirst($user['userDetail']['organization_name'])}}</td> 
                                     <td>@if($user->is_approved=='1')
                                            <span class="btn btn-primary"  style="font-size: 15px;"> Approved </span>   
                                         @elseif($user->is_approved=='2')
                                            <span  class="btn btn-danger" style="font-size: 15px;">Rejected</span>
                                         @elseif($user->is_approved=='0') 
                                            <span  class="btn btn-warning"  style="font-size: 15px;">Pending</span>
                                         @endif   
                                     </td>
                                     <td>
                                        <div class="table_last">
                                          @if($user->is_approved=='1')
                                               <span> 
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#deactiveUserModel{{$user->id}}">
                                                <i class="fa fa-close"></i>
                                            </button>
                                             </span> 
                                              <span> 
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectUserModel{{$user->id}}">
                                                <i class="fa fa-close"></i>
                                            </button>
                                             </span>
 
                                           
                                         @elseif($user->is_approved=='0') 
                                            <span>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#activeUserModel{{$user->id}}">
                                                <i class="fa fa-check"></i>
                                            </button>
                                             </span>
                                              
                                              <span> 
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectUserModel{{$user->id}}">
                                                <i class="fa fa-close"></i>
                                            </button>
                                             </span>
                                      
                                            @endif 
                                             <span><a  data-toggle="modal" data-target="#viewModalCenter{{$user->id}}">  <img src="{{asset('images/front_images/eye.png')}}"></a></span>

                                            
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="6">No Enterprise User Requests Available</td>
                                </tr>
                            @endif
                            
                        </tbody>
                    </table>        
                </div> 
            </div>             
           
        </div> 
        </div>
  @foreach($users as $key=>$user)

  <!-- view details -->

  <div class="modal fade cd-example-modal-xl viewModalCenter{{$user->id}}" id="viewModalCenter{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Enterprise User Requests Detail </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="modal-body">
            <table class="viewmodel_tbl" width="100%"  border="1">
              <tr class="viewmodel_tr">
                <th>Name</th>
                <td>{{ucfirst($user->name)}}</td>
              </tr>
                <tr class="viewmodel_tr">
                <th>Email</th>
                <td>{{$user->email}}</td>
              </tr> 
                <tr class="viewmodel_tr">
                <th>Contact Number</th>
                <td>{{$user['userDetail']['contact_no']}}</td>
              </tr> 
                <tr class="viewmodel_tr">
                <th>Organization Name</th>
                <td>{{ucfirst($user['userDetail']['organization_name'])}}</td>
               
              </tr> 
                <!-- <tr class="viewmodel_tr">
                <th>Status</th>
                <td>@if($user->status==1) Active @else Inactive @endif</td>
                </tr> --> 

                <tr class="viewmodel_tr">
                <th>No Of Team</th>
                <td>{{$user->team_count}}</td>
              </tr> 
                    <tr class="viewmodel_tr">
                    <th>Adderss</th>
                     <td>@if($user['userDetail']['address'] != '')
                     {{ucfirst($user['userDetail']['address'])}},{{ucfirst($user['userDetail']['city'])}},{{ucfirst($user['userDetail']['province'])}}-{{ucfirst($user['userDetail']['postal_code'])}}@else
                      -
                      @endif</td>
                  </tr> 
                   <!--  <tr class="viewmodel_tr">
                    <th>Renewal Date</th>
                    <?php
  
                $renewaldate=DB::table('renewal_details')->where('user_id',$user->id)->where('is_activate',1)->where('status',1)->select('*')->first();  
                  ?>  
                     <td>{{date('m/d/Y', strtotime($renewaldate->renewal_date));}} </td>  
                  </tr> -->   
                  </tr>  

                <tr class="viewmodel_tr">
                <th>Account Created By</th>
                <td>@if($user->is_own==1) Own(Customer) @else Super Admin @endif</td>
              </tr> 
            </table>
            <table class="distict_table">
                <thead>
                    <tr class="tab">
                        <th>S.No </th>
                        <th>#Invoice</th> 
                        <th>Date</th>
                        <th>Plan</th>
                        <th>Coupon Code</th>                        
                        <th>Payment Status </th>
                        <th>Payment Type</th>
                        <th class="cen">Amount</th> 
                    </tr>
                </thead>
                <tbody>     
                 <?php $i=0; ?>
                 @foreach($plans as $key=>$plan) 
                 @if($user->id == $plan->user_id) 
                 <?php

                  $i=$i+1;
                  if($plan->renewal_coupon_id !=''){
                $couponname=DB::table('coupons')->where('id',$plan->renewal_coupon_id)->select('*')->get();
                  }
                  ?>                
                    <tr 
               @if($plan->renewal_is_activate =='1') style="background-color: rgb(144, 238, 144) !important;" @endif>
                        <td>{{$i}}</td> 
                        <td>#KH{{$plan->id}}{{$key+1}}u{{ $user->id}}</td>
                         <td>{{date('m/d/Y', strtotime($plan->renewal_updated_at));}}</td> 
                        <td>{{$plan->plan_name}}</td> 
                        <td>@if($plan->renewal_coupon_id !='')
                               {{$couponname[0]->coupon_code}}
                           @elseif($plan->renewal_coupon_id =='0')
                              - 
                           @endif
                         </td> 
                        <td>  
                           @if($plan->renewal_status=='1')
                              Success
                           @elseif($plan->renewal_status=='2')
                             Faild  
                           @endif 
                        </td>  
                        <td>  
                            @if($plan->renewal_paytype=='1')
                                Cash
                            @elseif($plan->renewal_paytype=='2')
                                Cheque
                            @elseif($plan->renewal_paytype=='3')
                                Cheque
                            @elseif($plan->renewal_paytype=='0')
                                Offline Payment 
                            @endif 
                       </td> 
                        <td>CAD{{$plan->renewal_amt}}</td> 
                    </tr>                                             
                @endif
                @endforeach


            </tbody>
        </table> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
      </div>
    </div>
  </div>
</div>

  <!-- active user -->

 <div class="modal fade" id="activeUserModel{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:575px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Enterprise User Approve</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
         <form method="GET" action="{{URL::to('/approve-user/'.$user->id)}}" enctype="multipart/form-data">
      </div>      
      <div class="modal-body">
        <input type="hidden" name="user_id" value="{{$user->id}}">

       <p> Do You want active this {{ucfirst($user->name)}} user ?</p>
        
        <div class="model_grp_select">
        <div class="lbl_text_select">
            <label>Select Plan</label>
        </div>
        <div class="input_mdl_select">
            <select name="plan_id" required="" class="form-control">
              <option value="">Select</option>
              @foreach($planlist as $plan)
               <option value="{{$plan->id}}">{{ucwords($plan->plan_name)}}</option>
              @endforeach
            </select>
        </div>
        </div>
        <br/>
        <div class="model_grp">
        <div class="lbl_text">
            <label>No of Team</label>
        </div>
        <div class="input_model" style="width: 100%; max-width: 340px;">
            <input type="text" id="location" onkeypress="return isNumberKey(event)" autocomplete="off" name="team_count" placeholder="No of Teams" value="{{$user ? $user->team_count : old('team_count') }}" required="required"> 
        </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Approve</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- deactive user -->

 <div class="modal fade" id="deactiveUserModel{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Enterprise User Pending</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
         <form method="GET" action="{{URL::to('/deactive-user/'.$user->id)}}" enctype="multipart/form-data">
      </div>      
      <div class="modal-body">
        <input type="hidden" name="user_id" value="{{$user->id}}">
        Do You want Pending this {{ucfirst($user->name)}} user ?
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Pending</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Rejected user -->

 <div class="modal fade" id="rejectUserModel{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Enterprise User Reject</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
         <form method="GET" action="{{URL::to('/reject-user/'.$user->id)}}" enctype="multipart/form-data">
      </div>      
      <div class="modal-body">
        <input type="hidden" name="user_id" value="{{$user->id}}">

        <div class="model_grp">
        <div class="lbl_text">
            <label>Reason</label>
        </div>
        <div class="input_model">
            <input type="text" name="reason" placeholder="Reason" value="{{$user ? $user->reason : old('reason') }}" required="required"> 
        </div>
        </div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<div class="table-footer"> 
 <div class="col-xs-12 text-right" align="left">
   {{ $users->appends(['user_name' =>$user_name,'email'=>$email,'mobile'=>$mobile,'status'=>$status])->links('vendor.pagination.default')}}
</div>
</div>
    </div>

@endsection