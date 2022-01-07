<style>
  .rename-template {
    display: none;
  }
</style>

<?php 
$template_name = (isset($_GET['template_name']) && $_GET['template_name'] != '') ? $_GET['template_name'] : ''; 
$project_name = (isset($_GET['project_name']) && $_GET['project_name'] != '') ? $_GET['project_name'] : ''; 
$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : ''; 
$to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : '';
$end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : '';
$team_user_id = (isset($_GET['team_user_id']) && $_GET['team_user_id'] != '') ? $_GET['team_user_id'] : ''; 
?>
@extends('layouts.frontend.header')
@section('content')
<style>
</style>
<div class="container-fluid px-4 main_part">
 <div class="t-h">
  <a href="{{url('/user-myflowchart')}}">
   <h4 class="Acc_Setting bl">Flow Charts</h4>
 </a> 
   <div class="distict_btn right-align-submenu">
 
     <div class="XA_text mr-4 mb-lg-0 mb-3" id="submenu-popup-clk">
     <a class="FC_title">Add  Flow Chart </a>
      <div id="popup-submenu"> 
       <a href="#addtemplate-model" data-toggle="modal" >
        <button class="btn sm-btn" type="button"> Create Flow Chart</button>
      </a>
      <a href="{{url('default-template')}}">
        <button class="btn sm-btn" type="button">Default Template</button>
      </a>
    </div>
  </div>
</div>

</a>
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
@if(session('failure')) 
<div class="col-md-12">
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
   <form action="{{URL::to('user-myflowchart')}}" autocomplete="off" method="get" >
    <input type="hidden" name="base_url" value="{{url('/')}}"> 
    
    <div class="filter_by_sec  long-filter list-filter-div">
      <input type="text" placeholder="Project Name " name="project_name"  value="{{$project_name}}">
      <input type="text" placeholder="FC Name " name="template_name"  value="{{$template_name}}"> 
      @if(Auth::user()->user_role_id ==4 && Auth::user()->parent_id ==0)
      <select class="form-pik-er"  name="team_user_id">
        <option value="">Select Team</option>
        @foreach($teamUsers as $teamuser)
        <option value="{{$teamuser->id}}" @if($team_user_id == $teamuser->id) selected @endif > {{ucwords($teamuser->name)}} </option>
        @endforeach
      </select>
      @endif
      <input type="date" placeholder="To Date" class="start_datepicker" name="to_date" value="{{$to_date}}" >
      

      <input type="date" placeholder="End Date" class="end_datepicker" name="end_date" value="{{$end_date}}" >
      <select class="form-pik-er" name="status">
        <option value="">Select Status</option>
        <option value="0"  @if($status=="0") selected @endif>Draft</option>
        <option value="1"  @if($status=="1") selected @endif>Active</option>
        <option value="2"  @if($status=="2") selected @endif>Request for Approval</option>
        <option value="3"  @if($status=="3") selected @endif>Request for Change</option>
        <option value="4" @if($status=="4") selected @endif>Approved</option>
        <option value="5" @if($status=="5") selected @endif>Rejected</option>
      </select>
      
      <div class="distict_btn list-filter">
        <button class="btn blue_btn">Filter</button>
        <a href="{{URL::to('user-myflowchart')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
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
       <th>@sortablelink('template_name','FLOW CHART NAME')</th>
       <th> DATE </th>
       <th>@sortablelink('is_approved','STATUS')</th>
       <th class="cen">ACTIONS</th>
     </tr>
   </thead>
   <tbody>
    @if(count($usertemplate) > 0)
    @foreach($usertemplate as $key => $list)
    <tr>
     <td>{{ $key + $usertemplate->firstItem() }}</td>
     <td> {{ucwords($list->flowchartProject->project_name) }}</td>
     <td> {{ucwords($list->template_name) }}</td>
     <td>@if($list->updated_at == null)
      {{date('m/d/Y', strtotime($list->created_at))}}
      @else
      {{date('m/d/Y', strtotime($list->updated_at))}}
      @endif
      </td>
    
      <td>  {!! $list->userTemplateStatus() !!}    </td>
      <td>
        <div class="table_last">
          <span>
            <a href="{{url('/flowchart?user='.$list->id)}}" title="View"><img src="{{asset('images/front_images/eye.png')}}"></a>
          </span>
          <span>
            <a href="{{url('/flowchart?user='.$list->id)}}" title="Edit"><img src="{{asset('images/Edit.png')}}"></a>
          </span>
         <!--  <span>
            <a  title="Notes"  onclick="notes('{{ $list->id }}')"  data-toggle="modal" data-target="#notes">  <img src="{{asset('images/note.png')}}" style="height:20px;cursor: pointer;" >  </a>
          </span> -->
          @if($list->is_approved ==1 && $list->status ==1)
            <span> 
              <a title="Share" style="color:inherit;" onclick="shareChart({{ $list->id }})" data-toggle="modal" data-target="#exampleModal"><img src="{{asset('images/front_images/user_module/share.png')}}"></a>
            </span>
          @else
            <span> 
              <a title="Share" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/share.png')}}"></a>
            </span>
          @endif
          @if($list->flowchartProject['admin_id'] != '')
            @if(($list->editor_status == 1 || $list->editor_review ==1) && $list->editor_status != 3)
              @if($list->is_approved != 1)
                <span>
                 <a title="Approve" onclick="approved('{{ $list->id }}')" href="#approval-model" data-toggle="modal">   
                  <img src="{{asset('images/approved.png')}}" style="height:20px;" ></a> 
                </span>
              @else
                <span> 
                  <a title="Approve" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Approval.png')}}"></a>
                </span>
              @endif
              @if($list->is_approved != 2)
                <span>
                 <a title="Reject" onclick="temprejectchage('{{ $list->id }}')" href="#rejectchange-model" data-toggle="modal"> <img   src="{{asset('images/reject.png')}}" style="height:20px;" ></a>
                </span>
              @else
                <span> 
                <a title="Reject" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Reject.png')}}"></a>
                </span> 
              @endif
            @else
              <span> 
                <a title="Approve" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Approval.png')}}"></a>
              </span>
              <span> 
                <a title="Reject" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Reject.png')}}"></a>
              </span>
            @endif 
          @else
            @if($list->status == 1 && $list->is_approved != 1 && $list->flowchartProject['created_by'] == Auth::user()->id)
              <span>
                 <a title="Approve" onclick="approved('{{ $list->id }}')" href="#approval-model" data-toggle="modal">   
                  <img src="{{asset('images/approved.png')}}" style="height:20px;" ></a> 
              </span>
            @else
              <span> 
                <a title="Approve" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Approval.png')}}"></a>
              </span>
            @endif
            <span> 
              <a title="Reject" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Reject.png')}}"></a>
            </span>
          @endif
          @if($list->flowchartProject['admin_id'] != '')
            @if($list->editor_status == 1 || $list->editor_review ==1 || $list->is_approved == 1 || $list->is_approved == 2)
              <span>
               <a title="Request For Change" onclick="temprequestchage('{{ $list->id }}')" href="#requestchange-model" data-toggle="modal"> <img   src="{{asset('images/request-to-chanage.png')}}" style="height:20px;" ></a>
              </span>
            @else 
              <span> 
                <a title="Request For Change" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Request-To-change.png')}}"></a>
              </span>
            @endif
          @else
            @if(Auth::user()->user_role_id == '1')
              <span> 
                <a title="Request For Change" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Request-To-change.png')}}"></a>
              </span>
            @else
              @if(($list->status == 1 || $list->is_approved == 1) && ($list->flowchartProject['team_user_id'] != 0 || $list->flowchartProject['created_by'] != Auth::user()->id))
                <span>
                 <a title="Request For Change" onclick="temprequestchage('{{ $list->id }}')" href="#requestchange-model" data-toggle="modal"> <img   src="{{asset('images/request-to-chanage.png')}}" style="height:20px;" ></a>
                </span>
              @else
                <span> 
                <a title="Request For Change" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Request-To-change.png')}}"></a>
                </span>
              @endif
            @endif
          @endif
         <!--  <span>
             <a title="Comments" onclick="temprqstchangecmt('{{ $list->id }}')" href="#rqstchanges-cmt-model" data-toggle="modal">  <img src="{{asset('images/Comment.png')}}" style="height:20px;" ></a>
          </span> -->
          
         <span>
          <a  title="Rename"  onclick="rename({{ $list->id }})"  data-toggle="modal" data-target="#rename"> 
            <img   src="{{asset('images/rename.png')}}" style="height:20px;" >
          </a>
        </span>
        <span>
          <a  title="Duplicate"   onclick="duplicate({{ $list->id }})"  data-toggle="modal" data-target="#duplicate"  >
            <img   src="{{asset('images/copy.png')}}" style="height:20px;" >
          </a>
        </span>
        
        <a title="Delete" href="{{route('flowchart-delete',[$list->id])}}"><button class="btn" id="button" type="button" onclick="return confirm('Are you sure to delete?')" > <img src="{{asset('images/Delete.png')}}" ></button></a>
      </div>
      </td>
    </tr>
@endforeach
@else
<tr>
 <td colspan="2"></td>
 <td colspan="2" class="text_cen">No Flow Charts Available</td>
 <td colspan="2"></td>
 

</tr>
@endif
</tbody>
</table>
</div>
</div>
</div>


<!-- add template-->

<div id="addtemplate-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">  
      <form method="POST" action="{{route('add-flowchart')}}">
        @csrf
        <div class="modal-header flex-column">       
          <h4 class="modal-title w-100">Project Mapping</h4>  
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">     
          <div class="lbl_text"> 
            <div class="form-group">  
            
              <input type="hidden" value="{{url('/')}}" id="baseurl">  
              <input type="hidden" name="userid" id="userid" value="{{Auth::user()->id}}" />            
              <div class="input_model_select">
                <select name="project_id" required="" class="form-control">
                  <option value="">Select Project</option> 
                  @foreach($project_fc_list as $project) 
                  <option value="{{$project->id}}">{{$project->project_name}}</option>
                  @endforeach 
                </select> 
                  <input type="text" required="" name="add_temp_name" placeholder="Flow Chart Name" id="add_temp_name" class="form-control" autocomplete="off" style="margin-top: 10px;">
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

<!-- add template update--> 
<!-- rename -->
<div class="modal fade" id="rename" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
   <div class="modal-content">
    <div class="modal-header">
     <h5 class="modal-title" id="exampleModalCenterTitle"> Rename Flow Chart</h5>
     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
       <span aria-hidden="true">&times;</span>
     </button>
     <form method="POST" action="{{ route('myflowchart.rename') }}">
      @csrf
    </div>
    <div class="modal-body">
      <div class="model_grp">
        <div class="lbl_text">
          <label>Flow Chart Name </label>
        </div>
        <div class="input_model">
          <input type="text" id="template_name" name="template_name" placeholder="Flow Chart Rename"  required="required"> 
          <input type="hidden" id="template_id" name="template_id" placeholder="Flow Chart Rename" value="" required="required"> 
        </div>
      </div> 
    </div>
    <div class="modal-footer">
      <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</div>
</div>
</div>
<!-- duplicate -->
<div class="modal fade" id="duplicate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title" id="exampleModalCenterTitle"> Duplicate Flow Chart  </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button> 
  </div>
  <span class="alert-success" role="alert">
   <strong id="template_name-success"> </strong>
 </span> 
 <div class="modal-body">
  <div class="model_grp">
   <div class="lbl_text">
    <label> Flow Chart Name</label>
  </div>
  <div class="input_model">
    <input type="text" name="template_name" id="temp_name" placeholder="Flow Chart Name" value="" required="required">  
    <span class="invalid-feedback" role="alert">
      <strong id="check-template_name-status"> </strong>
    </span>
    <input type="hidden" id="original_id" name="original_id" placeholder="Flow Chart Name" value="" required="required"> 
  </div>
</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  <button type="button" id="temp_name_check" class="btn btn-primary">Submit</button>
</div>
</div>
</div>
</div>
<div class="table-footer">
  <div class="col-xs-12 text-right" align="left">
   {{ $usertemplate->appends(['template_name' =>$template_name,'to_date'=>$to_date,'end_date'=>$end_date,'status'=>$status])->links('frontend.pagination.default')}}
 </div>
</div>
</div>
<!-- share model -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title ml-1" id="exampleModalLabel">Share</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <form method="POST" action="{{ route('myflowchart.share') }}">
     @csrf
     <input type="hidden" name="chart_id" id="chart_id">
     <input type="hidden" value="{{url('/')}}" id="baseurl">
     <div class="d-flex align-items-center justify-content-between mb-2">
      <div class="invite">
       <h3>Invite Collabrators</h3>
     </div>
   </div>
   <div class="row m-0">
    <div class="Invite_Collbrators">
     <label for="exampleFormControlInput1" class="form-label d-none">Invite Collabrators</label>
     <!-- <div id="example11"></div> -->
     <!-- <input type="hidden" class="form-control" name="useremail" id="shareuseremail"> -->
     <select class="form-control team-share sel-input" id="role-shareuseremail" name="useremail[]" multiple="multiple">
     </select>
     
   </div>
   <div class="form-group w-100 mt-3 popup_text mb-1">
     <textarea name="msg" class="form-control" id="exampleFormControlTextarea1" rows="2" placeholder="Add a custom message.."></textarea>
   </div>
   <p id="shared_detail_cnt" style="display: none;"><a href="#" onclick="shared_user_detail()"  data-toggle="modal" data-target="#shared_detail">Check</a></p>
 </div>
</div>
<div class="modal-footer">
 <button type="submit" class="btn"  style="background: #179FD7;">Done</button>
</div>
</form>
</div>
</div>
</div>
<!--Shared user modal -->
<div class="modal fade" id="shared_detail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-lg">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title ml-1" id="exampleModalLabel">Share</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <form >
     <div class="d-flex align-items-center justify-content-between mb-2">
      <div class="invite">
       <h3>With Collabrators</h3>
     </div>
   </div>
   <div class="row m-0">
    <div class="Invite_Collbrators">
     <label for="exampleFormControlInput1" class="form-label d-none">With Collabrators</label>
   </div>
   <div class="form-group w-100 mt-3 popup_text mb-1">
     <div id="shared_list"></div>
   </div>
 </div>
</div>
<div class="modal-footer">
 <button type="button" class="btn" style="background: #179FD7;" data-dismiss="modal">Close</button>
</div>
</form>
</div>
</div>
</div>

<!--share end -->
<!--Noted user modal -->
<div class="modal fade" id="notes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalCenterTitle">Notes </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> 
        <form method="POST" action="{{ route('myflowchart.add-notes') }}">
         @csrf
       </div>      
       <div class="modal-body">
        <input type="hidden" value="{{url('/')}}" id="baseurl">         
        <div class="lbl_text">
          <div class="display-comment" id="notes_list">
           
          </div>
          <hr>
          <h5>Add Notes</h5>
        </div>
        
        <div class="form-group">
          <textarea type="text" row="2" required name="note" id="note" class="form-control" /></textarea>
          <input type="hidden" name="template_id" id="note_template_id" value="" />
          <input type="hidden" name="userid" id="userid" value="{{Auth::user()->id}}" />
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-warning" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-warning" id="note_btn" value="Add Notes" />
        </div> 
      </div>
    </form>        
  </div> 
</div>
</div>
</div> 
<!-- request change comments -->
<div id="rqstchanges-cmt-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content"> 
     <form method="POST" action="{{route('myflowchart.add-comments')}}">
      @csrf
      <div class="modal-header flex-column">       
        <h4 class="modal-title w-100">User Comments</h4>  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">     
        <div class="lbl_text">
          <div class="display-comment" id="rqtcmt_list"></div>
          <hr><h5>Add Comment </h5>
        </div>
        
        <div class="form-group">
         <input type="hidden" value="{{url('/')}}" id="baseurl">
         <textarea type="text" row="2" name="comments" id="comments" class="form-control" /></textarea>
         <input type="hidden" name="temprqstchnge" id="temprqstchnge" value="" /> 
         <input type="hidden" name="userid" id="userid" value="{{Auth::user()->id}}" />
       </div>
       <div class="modal-footer">
        <button type="reset" class="btn btn-warning" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-warning" id="comment_btn" value="Add Comment" />
      </div> 
    </div> 
    
  </form>
</div>
</div>
</div>
<!-- request change -->
<div id="requestchange-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content"> 
     <form method="POST" action="{{route('myflowchart.request-changes')}}">
      @csrf
      <div class="modal-header flex-column">       
        <!-- <h5 class="modal-title w-100">Comments</h5>   -->
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">     
        <div class="lbl_text">
          <div class="display-comment" id="rqtcomment_list">
           
          </div>
          <hr>
          <h5>Add Comments For Changes </h5>
        </div>
        
        <div class="form-group">
         <input type="hidden" value="{{url('/')}}" id="baseurl">
         <textarea type="text" row="2" name="comments" id="comments" class="form-control" /></textarea>
         <input type="hidden" name="tempchangeid" id="tempchangeid" value="" /> 
         <input type="hidden" name="userid" id="userid" value="{{Auth::user()->id}}" />
       </div>
       <div class="modal-footer">
        <button type="reset" class="btn btn-warning" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-warning" id="comment_btn" value="Add Comment" />
      </div> 
    </div>
    
  </form>
</div>
</div>
</div> 
<!-- reject change -->
<div id="rejectchange-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
     <form method="POST" action="{{route('myflowchart.reject')}}">
      @csrf
      <div class="modal-header flex-column">       
        <h4 class="modal-title w-100">Reject Flow Chart with Reason</h4>  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      
      <div class="modal-body">     
        <div class="lbl_text">
          <div class="display-comment" id="comment_list">
           
          </div>
          <hr>
          <h5>Add Comment For Reject Flow Chart</h5>
        </div>
        
        <div class="modal-body">
         <input type="hidden" value="{{url('/')}}" id="baseurl">
         <textarea type="text" row="2" name="comments" id="comments" class="form-control" /></textarea>
         <input type="hidden" name="temprejectid" id="temprejectid" value="" /> 
         <input type="hidden" name="userid" id="userid" value="{{Auth::user()->id}}" />
       </div>
       <div class="modal-footer">
        <button type="reset" class="btn btn-warning" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-warning" id="comment_btn" value="Add Comment" />
      </div> 
    </div>
    
   <!--    <div class="modal-header flex-column">       
        <h4 class="modal-title w-100">Are you sure?</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" value=""  name="temprejectid" id="temprejectid">
        <p>Do you really want to reject template?.</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning">Reject</button>
      </div> -->
    </form>
  </div>
</div>
</div> 
@endsection
@section('script')
<script>
  $('.team-share').select2({
    tags: true,
    tokenSeparators: [',', ' '],
    initSelection: function(element, callback) {                   
    }
  });

  var baseurl  =   $('#base_url').val();
  function details(val) {  
   
   
   
  }
  
  function approved(val) { 
    if (confirm('Are you sure to Approved ?')) {
      window.location=baseurl+'/user-myflowchart/approved/' +val;
    }
  }
  
  function rename(val) {  
   
    $("#template_id").val(val);
    
    $.ajax({
     method: "GET",
     url: baseurl+'/user-myflowchart/'+val, 
           //url: "{{url('user-myflowchart/')}}/" + val,
           success: function (data) {
             $('#template_name').val(data);
           }
         });
    
  }
  function duplicate(val) { 
    $("#original_id").val(val);    
  }
  
  
  
  
  $( "#temp_name_check" ).click(function() {
   
   var template_name = $('#temp_name').val();
   var original_id = $('#original_id').val(); 
 
   $.ajax({  
     headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     },
     method: "POST",
     url: baseurl+'/user-myflowchart/duplicate', 
                   // url: '{{ route('myflowchart.duplicate') }}',
                   data: { template_name: template_name,original_id:original_id }, 
                   success: function(data){ 
                     if(data=='success')
                     { 
                       window.location.href = baseurl+'/user-myflowchart';
                       $('#template_name-success').text('Flow Chart Duplicated Successfully');                       
                     } else{
                       $('#check-template_name-status').text('Flow Chart Name Already Existing...');
                     } 
                   }
                 });
   
 });


  
  function notes(val) {  
    $("#note_template_id").val(val);  
    var baseurl  =   $('#base_url').val();
    $.ajax({
      url: baseurl+'/user-myflowchart/note-lists', 
      type: 'POST',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
      data: { 
        template_id:val
      }, 
      success: function(response){  
        var noteslist='';
        $.each(response.notes, function (key, value) { 
          noteslist+='<div style="margin-left: 40px;">'+value.note+'</div>';
        });
        noteslist+='';
        $('#notes_list').html('');
        $('#notes_list').append(noteslist); 
        
      }
    });

    
  }        
  function temprqstchangecmt(val) {       
    $("#temprqstchnge").val(val); 
    var baseurl =  $('#baseurl').val(); 
    $.ajax({
      url: baseurl+'/user-comment-lists', 
      type: 'POST',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
      data: { 
        template_id:val 
      }, 
      success: function(response){  
        var comment_list='';
        $.each(response.comments, function (key, value) {
          if(value.user != null){
            uname = value.user.name;
            if(value.user.parent_id != 0){
              var userrole=value.user.user_team_role.role;
            }else{
              var userrole=value.user.user_role.role;
            }
          }else{
            uname = value.degraded_user.name;
            if(value.degraded_user.parent_id != 0){
              var userrole=value.degraded_user.user_team_role.role;
            }else{
              var userrole=value.degraded_user.user_role.role;
            }
          }
          
          var gettime= getStrDate(value.created_at); 
          comment_list+='<div class="cmt-popup" ><span>'+uname+' - '+userrole+'</span><span class="cmt-align">'+gettime+'</span></div><div class="cmts">'+value.comments+'</div>';
        });
        comment_list+='';
        $('#rqtcmt_list').html('');
        $('#rqtcmt_list').append(comment_list); 
        
      } 
    });  
  }
  function temprequestchage(val) {      
    $("#tempchangeid").val(val);    
    var baseurl =  $('#baseurl').val();
    $.ajax({
      url: baseurl+'/user-req-lists', 
      type: 'POST',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
      data: { 
        template_id:val 
      }, 
      success: function(response){   
       var comment_list='<h5>Requested Changes</h5>';
       $.each(response.comments, function (key, value) { 
        if(value.user != null){
          uname = value.user.name;
          if(value.user.parent_id != 0){
            var userrole=value.user.user_team_role.role;
          }else{
            var userrole=value.user.user_role.role;
          }
        }else{
          uname = value.degraded_user.name;
          if(value.degraded_user.parent_id != 0){
            var userrole=value.degraded_user.user_team_role.role;
          }else{
            var userrole=value.degraded_user.user_role.role;
          }
        }
        var gettime= getStrDate(value.created_at); 
        comment_list+='<div class="cmt-popup" ><span>'+uname+' - '+userrole+'</span><span class="cmt-align">'+gettime+'</span></div><div class="cmts">'+value.comments+'</div>';
        
      });
       comment_list+='';
       $('#rqtcomment_list').html('');
       $('#rqtcomment_list').append(comment_list); 
       
     }
   }); 
  }
  function temprejectchage(val) {      
    $("#temprejectid").val(val); 
    var baseurl =  $('#baseurl').val();

    $.ajax({
      url: baseurl+'/user-req-lists', 
      type: 'POST',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
      data: { 
        template_id:val 
      }, 
      success: function(response){

        var comment_list='<h5>Requested Changes</h5>';
        $.each(response.comments, function (key, value) {
          if(value.user != null){
            uname = value.user.name;
            if(value.user.parent_id != 0){
              var userrole=value.user.user_team_role.role;
            }else{
              var userrole=value.user.user_role.role;
            }
          }else{
            uname = value.degraded_user.name;
            if(value.degraded_user.parent_id != 0){
              var userrole=value.degraded_user.user_team_role.role;
            }else{
              var userrole=value.degraded_user.user_role.role;
            }
          }
          var gettime= getStrDate(value.created_at); 
          comment_list+='<div class="cmt-popup" ><span>'+uname+' - '+userrole+'</span><span class="cmt-align">'+gettime+'</span></div><div class="cmts">'+value.comments+'</div>';
        });
        comment_list+='';
        $('#comment_list').html('');
        $('#comment_list').append(comment_list); 
        
      } 
    });  
  }  
  function getStrDate(datev){
    const date = new Date(datev);
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    let year = date.getFullYear();
    let month = (1 + date.getMonth()).toString().padStart(2, '0');
    let day = date.getDate().toString().padStart(2, '0');
    // var formatdate= days[date.getDay()] +', '+monthNames[date.getMonth()] +' ' + day + ', ' + year+', '+date.toLocaleTimeString();
    var formatdate= monthNames[date.getMonth()] +' ' + day + ', ' + year+', '+date.toLocaleTimeString();
    return formatdate;
  }
</script>
@endsection