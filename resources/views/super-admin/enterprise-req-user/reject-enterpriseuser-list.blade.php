 <?php 
$user_name = (isset($_GET['user_name']) && $_GET['user_name'] != '') ? $_GET['user_name'] : '';
$email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
$mobile = (isset($_GET['mobile']) && $_GET['mobile'] != '') ? $_GET['mobile'] : ''; 
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
                            <h1>Manage Rejected Enterprise User Requests</h1>
                        </div>
                        <div class="distict_btn">
                            <a href="{{url('/enterprise-request')}}"><button type="button" class="btn blue_btn" style="background-color: darkcyan">Back</button></a>
                        </div>
                    </div>
                </div>
                <form method="GET" action="{{ route('reject-enterprise-request') }}" >    
                <div class="filter_by_sec">
                    <input type="text" placeholder="Name" name="user_name"  value="{{$user_name}}">
                    <input type="text" placeholder="Email Address" name="email"  value="{{$email}}">
                    <input type="text" placeholder="Contact Number" name="mobile"  value="{{$mobile}}">  
                    
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{url('/reject-enterprise-request')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
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
                                <th>@sortablelink('contact_no','CONTACT NUMBER')</th>
                                <th>@sortablelink('organization_name','ORGANIZATION NAME')</th> 
                                <th>DATE</th> 
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
                                    <td>{{$user->contact_no}}</td>  
                                    <td>{{$user->organization_name}} </td>  
                                    @if($user->updated_at !='')
                                        <td><?php $date = explode(' ',$user->updated_at); echo date('m/d/Y', strtotime($date[0])); ?></td>
                                    @else
                                        <td><?php $date = explode(' ',$user->created_at);  date('m/d/Y', strtotime($date[0])); ?></td>
                                    @endif
                                     <td>
                                        <div class="table_last"> 
                                           
                                             <span><a  data-toggle="modal" data-target="#viewModalCenter{{$user->id}}">  <img src="{{asset('images/front_images/eye.png')}}"></a></span>

                                            
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="6">No Rejected Enterprise User Requests Available</td>
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
        <h5 class="modal-title" id="exampleModalCenterTitle">Rejected Enterprise User Requests Detail </h5>
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
                <td>{{$user->contact_no}}</td>
              </tr> 
                <tr class="viewmodel_tr">
                <th>Organization Name</th>
                <td>{{ucfirst($user->organization_name)}}</td>
               
              </tr> 
                 
                <tr class="viewmodel_tr">
                <th>No Of Team</th>
                <td>{{$user->team_count}}</td>
              </tr> 
                    <tr class="viewmodel_tr">
                    <th>Adderss</th>
                     <td>@if($user->address != ''){{ucfirst($user->address)}},{{ucfirst($user->city)}},{{ucfirst($user->province)}}-{{ucfirst($user->postal_code)}}@else
                      -
                      @endif</td>
                  </tr> 
                  
                <tr class="viewmodel_tr">
                <th>Reason</th>
                <td>{{ucfirst($user->reason)}}</td>
               
              </tr> 

                  </tr>  
            </table>
            <table class="distict_table">
                <thead>
                    <tr class="tab">
                        <th>S.No </th>
                        <th>#Invoice</th> 
                        <th>Renewal Date</th>
                        <th>Date</th>
                        <th>Plan</th> 
                        <th >Amount</th> 
                    </tr>
                </thead>
                <tbody>     
                 <?php $i=0; 
                 ?>

                 @foreach($plans as $key=>$plan) 
                 @if($user->user_id == $plan->user_id) 
                 <?php

                  $i=$i+1;
                 
                  ?>                
                    <tr>
                        <td>{{$i}}</td> 
                        <td>#KH{{$plan->id}}{{$key+1}}{{ $user->id}}</td>
                        <td>{{date('m/d/Y', strtotime($plan->renewal_date));}}</td>
                        <td>{{date('m/d/Y', strtotime($plan->renewal_updated_at));}}</td> 
                        <td>{{$plan->plan_name}}</td> 
                        <td>CAD {{$plan->renewal_amt}}</td> 
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
@endforeach

<div class="table-footer"> 
 <div class="col-xs-12 text-right" align="left">
   {{ $users->appends(['user_name' =>$user_name,'email'=>$email,'mobile'=>$mobile])->links('vendor.pagination.default')}}
</div>
</div>
    </div>

@endsection