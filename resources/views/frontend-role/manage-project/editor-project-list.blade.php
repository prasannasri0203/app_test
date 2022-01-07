<?php 
$template_name = (isset($_GET['template_name']) && $_GET['template_name'] != '') ? $_GET['template_name'] : ''; 
$status = (isset($_GET['status']) && $_GET['status'] != '') ? $_GET['status'] : ''; 
$to_date = (isset($_GET['to_date']) && $_GET['to_date'] != '') ? $_GET['to_date'] : '';
$end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : '';
$project_name = (isset($_GET['project_name']) && $_GET['project_name'] != '') ? $_GET['project_name'] : ''; 
?>
@extends('layouts.frontend-role.header')
@section('content')
<style>
</style>
<div class="container-fluid px-4 main_part">
    <div class="t-h">
        <a href="{{url('/user-myflowchart')}}">
            <h4 class="Acc_Setting bl">Flow Chart </h4>
        </a>
        @if(Auth::guard('roleuser')->user()->parent_id != 0 && (Auth::guard('roleuser')->user()->user_role_id ==1 || Auth::guard('roleuser')->user()->user_role_id ==2)) 
             <div class="distict_btn right-align-submenu">
 
     <div class="XA_text mr-4 mb-lg-0 mb-3" id="submenu-popup-clk">
      <a class="FC_title">Add  Flow Chart </a>
      <div id="popup-submenu"> 
       <a href="#addtemplate-model" data-toggle="modal" >
        <button class="btn sm-btn" type="button"> Create Flow Chart</button>
      </a>
      <a href="{{url('/role-user/default-temp-list')}}">
        <button class="btn sm-btn" type="button">Default Template</button>
      </a>
    </div>
  </div>
</div>  
        @endif
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
   <div class="filter-sec d-flex align-items-center w-bg flex-wrap ">
       
    <form action="{{URL::to('/role-user/editor-project-list')}}" autocomplete="off" method="get" class="filter-cls" >
                    <input type="hidden" name="base_url" value="{{url('/')}}"> 
                     
                 <div class="filter_by_sec  long-filter list-filter-div">
                    <input type="text" placeholder="Project Name " name="project_name"  value="{{$project_name}}"> 
                    <input type="text" placeholder="FC Name " name="template_name"  value="{{$template_name}}"> 
                     <input type="date" placeholder="To Date" class="datetimepicker" name="to_date" value="{{$to_date}}" >
                     <input type="date" placeholder="End Date" name="end_date" value="{{$end_date}}" >
                    @if(Auth::guard('roleuser')->user()->user_role_id != 4)
                      <select class="form-pik-er" name="status">
                        <option value="">Select Status</option>
                        @foreach($filterstatus as $fs)
                          <option value="{{$fs['val']}}"  @if($status==$fs['val']) selected @endif>{{$fs['name']}}</option>
                        @endforeach
                      </select>
                    @endif
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{URL::to('/role-user/editor-project-list')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                     </div>
                </div>
                </form>

                
    <div class="container-fluid p-0 mt-2 table-responsive">
        <input type="hidden" value="{{url('/')}}" id="baseurl">
        <div class="fc-list-table">
          <table class="distict_table">
            <thead>
              <tr class="tab">
                <th>S.NO </th>
                <th>@sortablelink('flowchartProject.project_name','PROJECT NAME')</th>
                <th>@sortablelink('template_name','FLOW CHART NAME')</th> 
                <th> DATE</th>  
                <th>@sortablelink('status','STATUS')</th>
                <th class="cen">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @if(count($user_project_temp) > 0)
              @foreach($user_project_temp as $key => $list)
              <tr>
                <td>{{  $key + $user_project_temp->firstItem() }}</td>

                <td> {{ ucwords($list->flowchartProject->project_name) }}</td>
                <td> {{ ucwords($list->template_name) }}</td>
                <td>
                  @if($list->updated_at == null)
                        {{date('m/d/Y', strtotime($list->created_at))}}
                        @else
                         {{date('m/d/Y', strtotime($list->updated_at))}}
                        @endif
                </td>
                <td>  {!! $list->userTemplateStatus() !!}    </td>
                
                <td> 
                  @if(Auth::guard('roleuser')->user()->user_role_id == 1 || Auth::guard('roleuser')->user()->user_role_id == 2 || Auth::guard('roleuser')->user()->user_role_id == 3)
                    <span>
                      <a  href="{{url('/RU-flowchart?user='.$list->id)}}" title="View" class="share-gray"  style="color:inherit;" ><img src="{{asset('images/front_images/eye.png')}}"></a>
                    </span>
                  @endif
                  @if(Auth::guard('roleuser')->user()->user_role_id == 1)
                    <span>
                      <a href="{{url('/RU-flowchart?user='.$list->id)}}" title="Edit"><img src="{{asset('images/Edit.png')}}"></a>
                    </span>
                  @endif
                  @if(Auth::guard('roleuser')->user()->user_role_id == 2)
                  <span>
                    @if($list->is_approved != 1)  
                      <a href="{{url('/RU-flowchart?user='.$list->id)}}" title="Edit"><img src="{{asset('images/Edit.png')}}"></a>
                    @else
                      <a title="Edit" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Edit.png')}}"></a>                  
                    @endif
                  </span>
                  @endif
                  
                  <?php /*if(Auth::guard('roleuser')->user()->user_role_id ==1 || Auth::guard('roleuser')->user()->user_role_id ==4){?>
                    <span>
                    <a title="Notes"  onclick="notes('{{ $list->id }}','{{Auth()->guard('roleuser')->user()->id}}')"  data-toggle="modal" data-target="#notes">  <img src="{{asset('images/note.png')}}" style="height:20px;cursor: pointer;" > </a>
                    </span>
                  <?php }else{
                    if($list->is_approved != 1){?> 
                      <span>
                      <a title="Notes"  onclick="notes('{{ $list->id }}','{{Auth()->guard('roleuser')->user()->id}}')"  data-toggle="modal" data-target="#notes">  <img src="{{asset('images/note.png')}}" style="height:20px;cursor: pointer;" > </a>
                      </span>
                    <?php }else{?>
                      <span> 
                        <a title="Notes" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Note.png')}}"></a>
                      </span>
                    
                  <?php }}*/?>
                  @if(Auth::guard('roleuser')->user()->user_role_id == 1 || Auth::guard('roleuser')->user()->user_role_id ==2 || Auth::guard('roleuser')->user()->user_role_id ==3)
                    @if($list->is_approved ==1 && $list->status ==1)
                      <span> 
                        <a title="Share" style="color:inherit;"  class="share-gray" onclick="shareChart2({{ $list->id }})" data-toggle="modal" data-target="#exampleModal"><img  src="{{asset('images/front_images/user_module/share.png')}}"></a>
                      </span> 
                    @else
                      <span> 
                        <a title="Share" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/share.png')}}"></a>
                      </span>
                    @endif
                  @endif 
                  
                  @if(Auth::guard('roleuser')->user()->user_role_id ==1 || Auth::guard('roleuser')->user()->user_role_id ==3)
                    @if(($list->editor_status == 1 || $list->editor_review ==1) && $list->editor_status != 3)
                      @if($list->is_approved != 1)
                      <span>
                       <a title="Approve" onclick="tempapproval('{{ $list->id }}')" href="#approval-model" data-toggle="modal">   
                        <img src="{{asset('images/approved.png')}}" style="height:20px;" ></a> 
                      </span> 
                      @else
                      <span> 
                      <a title="Approve" style="color:inherit;"  ><img src="{{asset('images/front_images/gray/Approval.png')}}"></a>
                      </span>
                      @endif
                      @if($list->is_approved != 2)
                      <span>
                       <a title="Reject" onclick="temprejectchage('{{ $list->id }}')" href="#rejectchange-model" data-toggle="modal"> <img   src="{{asset('images/reject.png')}}" style="height:20px;" ></a>
                      </span>
                      @else
                      <span> 
                        <a title="Reject" style="color:inherit;" ><img src="{{asset('images/front_images/gray/Reject.png')}}"></a>
                      </span> 
                      @endif
                    @else
                      <span> 
                        <a title="Approve" style="color:inherit;"  ><img src="{{asset('images/front_images/gray/Approval.png')}}"></a>
                      </span>
                      <span> 
                        <a title="Reject" style="color:inherit;"  ><img src="{{asset('images/front_images/gray/Reject.png')}}"></a>
                      </span>
                    @endif 
                  @endif 
                  @if(Auth::guard('roleuser')->user()->user_role_id ==1)
                    @if($list->editor_status == 1 || $list->editor_review ==1 || $list->is_approved == 1 || $list->is_approved == 2)
                      <span>
                       <a title="Request For Change" onclick="temprequestchage('{{ $list->id }}')" href="#requestchange-model" data-toggle="modal"> <img   src="{{asset('images/request-to-chanage.png')}}" style="height:20px;" ></a>
                     </span>
                    @else 
                      <span> 
                        <a title="Request For Change" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Request-To-change.png')}}"></a>
                      </span>
                    @endif
                  @endif
                  @if(Auth::guard('roleuser')->user()->parent_id != 0 && Auth::guard('roleuser')->user()->user_role_id ==3)
                    @if(($list->editor_status == 1 || $list->editor_review ==1) && $list->is_approved != 1)
                      <span>
                       <a title="Request For Change" onclick="temprequestchage('{{ $list->id }}')" href="#requestchange-model" data-toggle="modal"> <img   src="{{asset('images/request-to-chanage.png')}}" style="height:20px;" ></a>
                     </span>
                    @else 
                      <span> 
                        <a title="Request For Change" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Request-To-change.png')}}"></a>
                      </span>
                    @endif
                  @endif

                  @if(Auth::guard('roleuser')->user()->parent_id != 0 && Auth::guard('roleuser')->user()->user_role_id ==2)
                    @if(($list->editor_status == 0 || $list->editor_status == 2) && $list->editor_review != 1 && $list->status == 1)
                      <span>
                       <a title="Request For Approval" onclick="tempmoveapproval('{{ $list->id }}')" href="#moveapproval-model" data-toggle="modal">  <img   src="{{asset('images/Move-to-approvel.png')}}" style="height:20px;" ></a>
                      </span>
                    @else 
                      <span> 
                        <a title="Request For Approval" style="color:inherit;" ><img src="{{asset('images/front_images/gray/Move-to-approval.png')}}"></a>
                      </span>
                    @endif
                  @endif
                  <?php //if(Auth::guard('roleuser')->user()->user_role_id ==1 || Auth::guard('roleuser')->user()->user_role_id ==4){?>
                    <!-- <span>
                     <a title="Comments" onclick="temprqstchangecmt('{{ $list->id }}')" href="#rqstchanges-cmt-model" data-toggle="modal">  <img src="{{asset('images/Comment.png')}}" style="height:20px;" ></a>
                    </span> -->
                  <?php /*}else{
                   if($list->is_approved != 1){?>  
                    <span>
                     <a title="Comments" onclick="temprqstchangecmt('{{ $list->id }}')" href="#rqstchanges-cmt-model" data-toggle="modal">  <img src="{{asset('images/Comment.png')}}" style="height:20px;" ></a>
                    </span> 
                   <?php }else{?>
                    <span> 
                      <a title="Comments" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Comment.png')}}"></a>
                    </span>
                   <?php }}*/?>
                  

                  
                 
                  @if(Auth::guard('roleuser')->user()->parent_id != 0 && Auth::guard('roleuser')->user()->user_role_id == 1)
                  <span>
                    <a  title="Rename"  onclick="rename({{ $list->id }},'{{$list->template_name}}')"  data-toggle="modal" data-target="#rename"> 
                      <img   src="{{asset('images/rename.png')}}" style="height:20px;" >
                    </a>
                  </span>
                  <span>
                    <a  title="Duplicate"   onclick="duplicate({{ $list->id }})"  data-toggle="modal" data-target="#duplicate"  >
                      <img   src="{{asset('images/copy.png')}}" style="height:20px;" > 
                    </a>
                  </span> 
                  <span>          
                  <a title="Delete" onclick="tempdelete('{{ $list->id }}')" href="#del-model" data-toggle="modal"> <img src="{{asset('images/Delete.png')}}"></a>
                  </span>
                  @endif
                  @if(Auth::guard('roleuser')->user()->parent_id != 0 && Auth::guard('roleuser')->user()->user_role_id ==4) 
                    <span>
                      @if($list->is_approved == 1 && $list->status == 1)
                        <a title="QR Code" href="#qrcodeview-model{{$list->id}}" data-toggle="modal"><img src="{{asset('images/Qr Code.png')}}" style="height:20px;" ></a>
                      @else
                        <a title="QR Code" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/Qr Code.png')}}"></a>
                      @endif
                    </span> 
   
                  @endif

                </td>

             </tr>
             @endforeach
             @else
             <tr> 
              <td colspan="2"></td>
              <td colspan="4">No Flow Charts Available</td>
            </tr>
              @endif
            </tbody>
          </table>          
        </div>
    </div>
  </div>
</div> 

  
   <div class="table-footer">
      <div class="col-xs-12 text-right" align="left">
         {{ $user_project_temp->appends(['template_name' =>$template_name,'to_date'=>$to_date,'end_date'=>$end_date,'status'=>$status])->links('frontend.pagination.default')}}
      </div>
   </div>
</div>
         

<!-- rename model -->
<div class="modal fade" id="rename" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Flow Chart Rename</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            <form method="POST" action="{{URL::to('/role-user/temp-rename')}}">
               @csrf
         </div>
         <div class="modal-body">    
         <div class="model_grp">
         <div class="lbl_text">
         <label>Flow Chart Name</label>
         </div>
         <div class="input_model">
         <input type="text" name="templatename" id="templatename" placeholder="Flow Chart Name" value="" required="required"> 
         <input type="hidden" id="templateid" name="templateid" placeholder="Flow Chart Rename" value="" required="required"> 
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
<!-- duplicate model -->
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
                  <input type="text" name="template_name" id="temp_name" placeholder="Flow Chart  Name" value="" required="required">  
                  <span class="invalid-feedback" role="alert">
                  <strong id="check-template_name-status"> </strong>
                  </span>
                  <input type="hidden" id="original_id" name="original_id" placeholder="Flow Chart  Name" value="" required="required"> 
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

   <!-- note -->

 <div class="modal fade" id="notes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalCenterTitle">Notes </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> 
         <form method="POST" action="{{ route('add-notes') }}">
         @csrf
      </div>      
      <div class="modal-body">     
        <div class="lbl_text">
          <div class="display-comment" id="notes_list">
                             
           </div>
           <hr>
            <h5>Add Notes</h5>
          </div>
          
          <div class="form-group">
            <textarea type="text" row="2" name="note" id="note" class="form-control" /></textarea>
            <input type="hidden" name="template_id" id="template_id" value="" />
            <input type="hidden" name="userid" id="template_id" value="{{Auth()->guard('roleuser')->user()->id}}" />
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
   
</div>

 @if(Auth::guard('roleuser')->user()->parent_id != 0 && (Auth::guard('roleuser')->user()->user_role_id ==1 || Auth::guard('roleuser')->user()->user_role_id ==2))
   <!-- add template-->

   <div id="addtemplate-model" class="modal fade">  
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">  
       <form method="POST" action="{{URL::to('/role-user/add-flowchart-role')}}"> 
                      @csrf
           <div class="modal-header flex-column">       
        <h4 class="modal-title w-100">Add Flow chart </h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">     
        <div class="lbl_text"> 
          <div class="form-group">   
           <input type="hidden" value="{{url('/')}}" id="baseurl">  
             <input type="hidden" name="userid" id="userid" value="{{Auth()->guard('roleuser')->user()->id}}" />           
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
@endif


<!-- delete -->
<div id="del-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
       <form method="POST" action="{{URL::to('/role-user/temp-delete')}}">
                      @csrf
      <div class="modal-header flex-column">       
        <h4 class="modal-title w-100">Are you sure?</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" value=""  name="tempid" id="tempid">
        <p>Do you really want to delete these Flowchart?.</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </form>
    </div>
  </div>
</div> 

<!-- approval -->
<div id="approval-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
       <form method="POST" action="{{URL::to('/role-user/approverstatus-change')}}">
                      @csrf
      <div class="modal-header flex-column">       
        <h4 class="modal-title w-100">Are you sure?</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" value=""  name="approvaltempid" id="approvaltempid">
        <input type="hidden" name="userid" id="userid" value="{{Auth()->guard('roleuser')->user()->id}}" />
        <p>Do you really want  approve this template ?.</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning">Approve</button>
      </div>
    </form>
    </div>
  </div>
</div>   

<!-- move approval -->
<div id="moveapproval-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
       <form method="POST" action="{{URL::to('/role-user/editorstatus-change')}}">
                      @csrf
      <div class="modal-header flex-column">       
        <h4 class="modal-title w-100">Are you sure?</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" value=""  name="moveapprovaltempid" id="moveapprovaltempid">
        <p>Do you really want to move the template to approval ?.</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning">Yes</button>
      </div>
    </form>
    </div>
  </div>
</div>   

<!-- request change -->
<div id="requestchange-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content"> 
         <form method="POST" action="{{URL::to('/role-user/user-rejectstatus-change')}}">
          @csrf
           <div class="modal-header flex-column">       
              <h5 class="modal-title w-100">Request For Change</h5>  
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
             <input type="hidden" name="userid" id="userid" value="{{Auth()->guard('roleuser')->user()->id}}" />
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


<!-- request change comments -->
<div id="rqstchanges-cmt-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content"> 
         <form method="POST" action="{{URL::to('/role-user/comment-rqt-add')}}">
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
             <input type="hidden" name="userid" id="userid" value="{{Auth()->guard('roleuser')->user()->id}}" />
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

<!-- qrcode view     -->
@if(count($user_project_temp) > 0)
 @foreach($user_project_temp as $key => $list)
<div id="qrcodeview-model{{$list->id}}" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
       <form method="POST" action="{{URL::to('/role-user/approverstatus-change')}}">
                      @csrf
      <div class="modal-header flex-column">       
        <h4 class="modal-title w-100">Scan the QR Code</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
  
        @php
        $qrurl=URL::to('RU-flowchart?user='.$list->id.'&process=qrcode');
        @endphp
          {!! QrCode::size(250)->generate($qrurl) !!}
          <div style="padding:15px; color: #636363; ">
            URL :<a style="color:#636363;" href="{{$qrurl}}"> {{$qrurl}} </a>
          </div>
        <input type="hidden" name="qrcodetempid" value="" id="qrcodetempid">
        <input type="hidden" value=""  name="approvaltempid" id="approvaltempid">
        <input type="hidden" name="userid" id="userid" value="{{Auth()->guard('roleuser')->user()->id}}" />
      
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
        <!-- <button type="submit" class="btn btn-warning">Approve</button> -->
      </div>
    </form>
    </div>
  </div>
</div>    
@endforeach
@endif
<!-- reject change -->
<div id="rejectchange-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
       <form method="POST" action="{{URL::to('/role-user/rejectstatus-change')}}">
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
          
          <div class="modal-body>
             <input type="hidden" value="{{url('/')}}" id="baseurl">
            <textarea type="text" row="2" name="comments" id="comments" class="form-control" /></textarea>
            <input type="hidden" name="temprejectid" id="temprejectid" value="" /> 
             <input type="hidden" name="userid" id="userid" value="{{Auth()->guard('roleuser')->user()->id}}" />
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
                <form method="POST" action="{{ route('roleuser.myflowchart.share') }}">
                    @csrf
                    <input type="hidden" name="chart_id" id="chart_id">
                    <input type="hidden" value="{{url('/')}}" id="baseurl">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="invite">
                            <h3>Invite Collabrators</h3>
                        </div>
                        <div class="link">
                            <!-- <h5>Get Sharable Link<span class="ml-2"> <img src="{{asset('images/front_images/user_module/link.png')}}"></span></h5> -->
                        </div>


                    </div>
                    <div class="row m-0">
                        <div class="Invite_Collbrators role-share-input">
                            <label for="exampleFormControlInput1" class="form-label d-none">Invite Collabrators</label>
                            <!-- <div id="example12"></div>
                            <input type="hidden" class="form-control" name="useremail" id="role-shareuseremail"> -->
                            <select class="form-control admin-share sel-input" id="role-shareuseremail" name="useremail[]" multiple="multiple">
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
<div class="modal fade" id="shared_detail2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-1" id="exampleModalLabel">Share</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>
            <div class="modal-body">
              <input type="hidden" value="{{url('/')}}" id="baseurl">
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

@endsection

@section('script')

<script> 
  $('.admin-share').select2({
    tags: true,
    tokenSeparators: [',', ' '],
    initSelection: function(element, callback) {                   
    }
  });
 var baseurl  =   $('#base_url').val();  
/*function qrcodeview(tempid) {       
          $("#qrcodetempid").val(tempid);
        }*/

    function rename(val,name) {  
          $("#templateid").val(val);
          $("#templatename").val(name);    
       }
    function duplicate(val) {     
          $("#original_id").val(val);    
       }  
    function tempdelete(val) {      
          $("#tempid").val(val);    
       }  
    function tempapproval(val) {      
          $("#approvaltempid").val(val);    
       } 
    function tempmoveapproval(val) {      
          $("#moveapprovaltempid").val(val);    
       }   
        
    function temprequestchage(val) {      
      $("#tempchangeid").val(val);    

      $.ajax({
          url: baseurl+'/role-user/comment-lists', 
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


    function temprqstchangecmt(val) {       
          $("#temprqstchnge").val(val); 
          var baseurl =  $('#baseurl').val(); 
    $.ajax({
        url: baseurl+'/role-user/comment-rqt-lists', 
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

    function temprejectchage(val) {      
          $("#temprejectid").val(val); 
          var baseurl =  $('#baseurl').val();

    $.ajax({
        url: baseurl+'/role-user/comment-lists', 
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
                
   $( "#temp_name_check" ).click(function() {
   
             var template_name = $('#temp_name').val();
             var original_id = $('#original_id').val(); 
             $.ajax({  
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                   method: "POST",
                   url: '{{URL::to("/role-user/temp-duplicate")}}',
                   data: { template_name: template_name,original_id:original_id }, 
               success: function(data){ 
                     if(data=='success')
                      { 
                       window.location.href = baseurl+'/role-user/editor-project-list';
                       $('#template_name-success').text('Flow Chart Duplicated Successfully');
                      } else{
                       $('#check-template_name-status').text('Flow Chart Name Already Existing...');
                     } 
                 }
             });
   
   }); 

  function notes(val,user_id) {  
    $("#template_id").val(val);   
    var baseurl =  $('#baseurl').val();
    $.ajax({
        url: baseurl+'/role-user/note-lists', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            template_id:val,
            user_id:user_id
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

  function comments(val) {  
    $("#temprejectid").val(val);   
    var baseurl =  $('#baseurl').val();
    $.ajax({
        url: baseurl+'/role-user/comment-lists', 
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        data: { 
            template_id:val 
        }, 
        success: function(response){  
          var noteslist='';
           $.each(response.notes, function (key, value) { 
              commentlist+='<div style="margin-left: 40px;">'+value.note+'</div>';
           });
                noteslist+='';
                $('#comment_list').html('');
                $('#comment_list').append(commentlist); 
            
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