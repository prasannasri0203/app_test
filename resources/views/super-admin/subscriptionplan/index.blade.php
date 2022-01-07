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
                @if (session('failure'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('failure') }}
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    </div>
                @endif
                <div class="main_head">
                   <div class="menue_first_head">
                        
                        <div class="list_head">
                            <h1>Manage Subscriptions</h1>
                        </div>
                        <div class="distict_btn">
                            <a href="{{url('/addsubscription')}}"><button class="btn blue_btn">Add Subscription</button></a>
                        </div>
                    </div>
                </div>
             
                   
                <div class="filter_by_sec">
                    <input type="hidden" name="base_url" value="{{url('/')}}">
                    <input type="text" placeholder="Plan Name" name="filter_plan_name" value="{{$Module['filtervalues']['plananame']}}">
                    <select class="form-pik-er" name="roletype">
                        <option value="" @if($Module['filtervalues']['roletype']=='') selected @endif>Select Role</option>
                        <option value="1" @if($Module['filtervalues']['roletype']=='1') selected @endif>Team</option>
                        <option value="2" @if($Module['filtervalues']['roletype']=='2') selected @endif>Trial</option>
                        <option value="3" @if($Module['filtervalues']['roletype']=='3') selected @endif>Individual</option>
                        <option value="4" @if($Module['filtervalues']['roletype']=='4') selected @endif>Enterpriser</option>
                    </select>
                    <select class="form-pik-er" name="filter_plan_type">
                        <option value="" @if($Module['filtervalues']['plantype']=='') selected @endif>Select Plan Type</option>
                        <option value="free" @if($Module['filtervalues']['plantype']=='free') selected @endif>Free</option>
                        <option value="paid" @if($Module['filtervalues']['plantype']=='paid') selected @endif>Paid</option>
                    </select>
                    <select class="form-pik-er" name="filter_status">
                        <option value="" @if($Module['filtervalues']['status']=='') selected @endif>Select Status</option>
                        <option value='1'  @if($Module['filtervalues']['status']=='1') selected @endif>Active</option>
                        <option value='0'  @if($Module['filtervalues']['status']=='0') selected @endif>Inactive</option>
                    </select>
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn subscription_filter" style="width:100px;">Filter</button>
                        <a href="{{url('/super-subscription-plan')}}"><button class="btn blue_btn" style="width:100px;background-color:green">Reset</button></a>
                    </div>
                </div>
                
                <div class="table_main_dist table-responsive">
                    <table class="distict_table">
                        <thead>
                            <tr class="tab">
                                <th>S.NO</th>
                                <th>@sortablelink('plan_name','PLAN NAME')</th>
                                <th>@sortablelink('plan_type','PLAN TYPE')</th>
                                <th class="cen">@sortablelink('amount','AMOUNT (CAD)')</th>
                                <th>@sortablelink('payment_type','PAYMENT TYPE')</th>
                                <th>DATE</th>
                                <th>@sortablelink('status','STATUS')</th>  
                                <th class="cen">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($Module['listvalues'])>0)
                                @foreach($Module['listvalues'] as $k=>$planvals)
                                    <tr>
                                         <td class="text_cen">{{$k + $Module['listvalues']->firstItem()}}</td>
                                        <td>{{ucwords($planvals->plan_name)}}</td>
                                        <td>{{ucfirst($planvals->plan_type)}}</td>
                                         <td class="text_cen">{{$planvals->amount}}</td>
                                        <td>{{ucfirst($planvals->payment_type)}}</td>
                                        @if($planvals->updated_at !='')

                                            <td><?php $date = explode(' ',$planvals->updated_at); echo date('m/d/Y',strtotime($date[0])); ?></td>
                                        @else
                                            <td><?php $date = explode(' ',$planvals->created_at); echo date('m/d/Y',strtotime($date[0]));?></td>
                                        @endif
                                        <td>@if($planvals->status==1) Active @else Inactive @endif</td>
                                        <td>
                                            <div class="table_last">
                                                <span><a href="{{url('/addsubscription/'.$planvals->id)}}"><img src="images/Edit.png" title="Edit"></a></span>
                                                <span><a Onclick="deletesubscription(this);" data-delete-id="{{$planvals->id}}"><img src="images/Delete.png"></a></span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr> 
                                        <td class="text_cen" colspan="8">No Subscription Plan Available</td>
                                    </tr>
                            @endif
                        </tbody>
                    </table>         
                </div> 
            </div> 
            <div class="table-footer">
                <div class="col-xs-12 text-right" align="left">
                    {{$Module['listvalues']->links('vendor.pagination.default')}}
                </div>
            </div>
        </div>
    </div>
@endsection