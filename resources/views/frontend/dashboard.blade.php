@extends('layouts.frontend.header')
@section('content')
<div class="container-fluid px-4 main_part">
   <div class="d-flex justify-content-between align-items-center">
      <h2 class="recent_flow mt-4 ml-1 mb-0">Recently Created Flow Charts</h2>
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
   <!-- recently created fc -->
   <div class="card-section d-flex  mt-3">
    <?php
      date_default_timezone_set("America/Toronto");
      // print_r(new DateTime());
    ?>  
    @if(count($usertemplate) > 0)
      @foreach($usertemplate as $userthemp)
        <?php 
          if(!empty($userthemp->file_name)){
          $imgsrc=$userthemp->file_name.'.png';
          $imglink=asset('images/defaultFCimages').'/'.$imgsrc;
          }else{
           $imglink=asset('images/front_images/user_module/fc-3.png'); 
          }?>
      <div class="card mb-3">
        <a href="{{url('/flowchart?user='.$userthemp->id)}}">
         <img src="{{$imglink}}" class="card-img-top" alt="">
         <div class="card-body p-0">
            <div class="d-flex align-items-center algorithem_part before_hover">
               <div class="algorithem_img">
                  <img src="{{asset('images/front_images/user_module/algorithm_img.png')}}">
               </div>
               <div class="algorithem_text">
                  <h4 class="mb-0"> {{ucwords($userthemp->template_name)}}</h4>
                  <?php  $open_date ='';
                    $date_time2 ='';
                    if($userthemp->userTemplateTrack->updated_at != '')
                    {
                      $date_time2 = $userthemp->userTemplateTrack->updated_at;
                    } 
                    else
                    {
                      $date_time2 = $userthemp->userTemplateTrack->created_at; 
                    }
                    if($date_time2 !=''){
                      $timediff = $date_time2->diff(new DateTime());
                      //echo $timediff->format('%y year %m month %d days %h hour')."<br/>";
                      $diff_yr= $timediff->format('%y');
                      $diff_mon= $timediff->format('%m');
                      $diff_days= $timediff->format('%d');
                      $diff_hr= $timediff->format('%h');
                      $diff_min= $timediff->format('%i');
                      if($diff_yr != 0){
                        $open_date = $diff_yr.' year ago';
                        if($diff_yr > 1){
                          $open_date = $diff_yr.' years ago';
                        }                      
                      }else if($diff_mon != 0){
                        $open_date = $diff_mon.' month ago';
                        if($diff_mon > 1){
                          $open_date = $diff_mon.' months ago';
                        } 
                      }else if($diff_days != 0){
                        $open_date = $diff_days.' day ago';
                        if($diff_days > 1){
                          $open_date = $diff_days.' days ago';
                        } 
                      }else if($diff_hr != 0){
                        $open_date = $diff_hr.' hour ago';
                        if($diff_hr > 1){
                          $open_date = $diff_hr.' hours ago';
                        } 
                      }else if($diff_min != 0){
                        $open_date = $diff_min.' minute ago';
                        if($diff_min > 1){
                          $open_date = $diff_min.' minutes ago';
                        } 
                      }
                    }
                  ?>
                  @if($open_date != '')
                    <p class="mb-0">opened   {{ $open_date }}</p>
                  @endif
               </div>
            </div>
            <div class="d-flex align-items-center algorithem_part after_hover">
               <div class="d-flex align-items-center after_algorithem">
                  <a href="{{url('/flowchart?user='.$userthemp->id)}}"><button class="btn" type="button">Open</button></a>
                  <?php $fcstatus = 0;
                  if(Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 3){
                    if($userthemp->status == 1){
                      $fcstatus = 1;
                    }
                  }else if(Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 4){
                    if($userthemp->status == 1 && $userthemp->is_approved == 1){
                      $fcstatus = 1;
                    }
                  }  

                  ?>
                  @if($fcstatus == 1)
                    <div class="share_part" onclick="shareChart({{ $userthemp->id }})" data-bs-toggle="modal" data-bs-target="#exampleModal">
                       <img src="{{asset('images/front_images/user_module/share.png')}}">
                       <p class="mb-0">Share</p>
                    </div>
                  @endif
                  <div class="share_part more">
                     <img src="{{asset('images/front_images/user_module/dots_more.png')}}">
                     <p class="mb-0">More</p>
                  </div>
               </div>
            </div>
            <div id="load_item">
               <ul class="list-group fc_det">
                  <li><a href="{{url('/flowchart?user='.$userthemp->id)}}">Open</a></li>
                  <a onclick="rename({{ $userthemp->id }},'{{ $userthemp->template_name }}')"  data-toggle="modal" data-target="#rename">
                     <li> Rename </li>
                  </a>
                  <a  onclick="duplicate({{ $userthemp->id }})"  data-toggle="modal" data-target="#duplicate">
                     <li>Duplicate</li>
                  </a>
                  <a onclick="return confirm('Are you sure to delete?')" style="color:inherit;" href="{{route('template-delete',[$userthemp->id])}}">
                     <li>Delete</li>
                  </a>
                  <li><a href="{{url('/flowchart?user='.$userthemp->id.'&process=export')}}" style="color:black;">Export</a></li>

                  @if($fcstatus == 1)
                  <li><a href="#" style="color:inherit;" onclick="shareChart({{ $userthemp->id }})" data-bs-toggle="modal" data-bs-target="#exampleModal">Share</a></li>
                  @endif
               </ul>
            </div>
         </div>
       </a>
      </div>
      @endforeach
      @if(count($usertemplate) > 0)
        <div class="card card_res mb-3"> 
          <div class="card-body p-0">
            <a href="{{url('user-myflowchart')}}">
              <div class="view_all_main">
                <h1>View All</h1>
              </div>
            </a>
          </div>
        </div>
      @endif      
    @else
      <div> No Flow Chart Available </div>
    @endif
      
   </div>
   <!-- end recently created fc -->
   <!-- received fc -->
      <div class="d-flex justify-content-between align-items-center">
         <h2 class="recent_flow mt-4 ml-1 mb-0">Recently Received Flow Charts</h2>
      </div>
      <div class="card-section d-flex  mt-3">
         @if(count($receivedfc) > 0)
            @foreach($receivedfc as $receiveduserthemp)
                <?php if(!empty($receiveduserthemp->userTemplate->file_name)){
                $imgsrc=$receiveduserthemp->userTemplate->file_name.'.png';
                $imglink=asset('images/defaultFCimages').'/'.$imgsrc;
                }else{
                 $imglink=asset('images/front_images/user_module/fc-3.png'); 
                }?>
               <div class="card mb-3">
                 <a href="{{url('/flowchart?user='.$receiveduserthemp->user_template_id.'&process=received')}}">
                  <img src="{{$imglink}}" class="card-img-top" alt="">
                  <div class="card-body p-0">
                     <div class="d-flex align-items-center algorithem_part before_hover">
                        <div class="algorithem_img">
                           <img src="{{asset('images/front_images/user_module/algorithm_img.png')}}">
                        </div>
                        <div class="algorithem_text">
                           <h4 class="mb-0"> {{ucwords($receiveduserthemp->userTemplate->template_name)}}</h4>
                           <?php  $open_date ='';
                           $date_time2 ='';
                           if($receiveduserthemp->updated_at != '')
                           {
                            $date_time2 =    $receiveduserthemp->updated_at;
                           } 
                           else
                           {
                             $date_time2 =    $receiveduserthemp->created_at; 
                           }
                            if($date_time2 !=''){
                              $timediff = $date_time2->diff(new DateTime());
                              //echo $timediff->format('%y year %m month %d days %h hour')."<br/>";
                              $diff_yr= $timediff->format('%y');
                              $diff_mon= $timediff->format('%m');
                              $diff_days= $timediff->format('%d');
                              $diff_hr= $timediff->format('%h');
                              $diff_min= $timediff->format('%i');
                              if($diff_yr != 0){
                                $open_date = $diff_yr.' year ago';
                                if($diff_yr > 1){
                                  $open_date = $diff_yr.' years ago';
                                }                      
                              }else if($diff_mon != 0){
                                $open_date = $diff_mon.' month ago';
                                if($diff_mon > 1){
                                  $open_date = $diff_mon.' months ago';
                                } 
                              }else if($diff_days != 0){
                                $open_date = $diff_days.' day ago';
                                if($diff_days > 1){
                                  $open_date = $diff_days.' days ago';
                                } 
                              }else if($diff_hr != 0){
                                $open_date = $diff_hr.' hour ago';
                                if($diff_hr > 1){
                                  $open_date = $diff_hr.' hours ago';
                                } 
                              }else if($diff_min != 0){
                                $open_date = $diff_min.' minute ago';
                                if($diff_min > 1){
                                  $open_date = $diff_min.' minutes ago';
                                } 
                              }
                            }
                           ?>
                           @if($open_date != '')
                            <p class="mb-0">Received   {{ $open_date }}</p>
                           @endif
                        </div>
                     </div>
                     <div class="d-flex align-items-center algorithem_part after_hover">
                        <div class="d-flex align-items-center after_algorithem">
                           <a href="{{url('/flowchart?user='.$receiveduserthemp->user_template_id.'&process=received')}}"><button class="btn" type="button">Open</button></a>
                           @if($receiveduserthemp->userTemplate->status == 1)
                             <div class="share_part" onclick="shareChart({{ $receiveduserthemp->user_template_id }})" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <img src="{{asset('images/front_images/user_module/share.png')}}">
                                <p class="mb-0">Share</p>
                             </div>
                           @endif
                           
                        </div>
                     </div>
                  
                  </div>
                </a>
               </div>
            @endforeach
            @if(count($receivedfc) > 0)
              <div class="card card_res mb-3"> 
                <div class="card-body p-0">
                  <a href="{{url('received-myflowchart')}}">
                    <div class="view_all_main">
                      <h1>View All</h1>
                    </div>
                  </a>
                </div>
              </div>

            @endif
            @else
            <div> No Flow Chart Available </div>
            @endif
      </div>
   <!--end received fc -->
   <!-- project fc -->
      <div class="d-flex justify-content-between align-items-center">
         <h2 class="recent_flow mt-4 ml-1 mb-0">Recent Projects</h2>
      </div>
      <div class="card-section d-flex mt-3">
        @if(count($projectlist) > 0)
            @foreach($projectlist as $project)
                <div class="card card_res mb-3"> 
                   <div class="card-body p-0">
                    <a href="{{url('view-project/'.$project->id)}}">
                    <div class="view_all_main proj">
                       <h1 class="mb-0"> {{ucwords($project->project_name)}}</h1>
                    </div>
                   </a>

                   </div>
                </div>
            @endforeach 
            @if(count($projectlist) > 0)
              <div class="card card_res mb-3"> 
                <div class="card-body p-0">
                  <a href="{{url('flowchart-project')}}">
                    <div class="view_all_main">
                      <h1>View All</h1>
                    </div>
                   </a>
                </div>
              </div>
            @endif
        @else
          <div> No Project Available </div>
        @endif
      </div>
   <!--end project fc -->
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
            <form method="POST" action="{{ route('template-rename') }}">
               @csrf
         </div>
         <div class="modal-body">    
         <div class="model_grp">
         <div class="lbl_text">
         <label>Flow Chart Name</label>
         </div>
         <div class="input_model">
         <input type="text" name="template_name" id="template_name" placeholder="Flow Chart Name" value="" required="required"> 
         <input type="hidden" id="template_id" name="template_id" placeholder="Flow Chart Rename" value="" required="required"> 
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
<!-- share model -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title ml-1" id="exampleModalLabel">Share</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
                  <div class="link">
                     <!-- <h5>Get Sharable Link<span class="ml-2"> <img src="{{asset('images/front_images/user_module/link.png')}}"></span></h5> -->
                  </div>
               </div>
               <div class="row m-0">
                  <div class="Invite_Collbrators">
                     <label for="exampleFormControlInput1" class="form-label d-none">Invite Collabrators</label>
                      @if(Auth::user()->user_role_id == 1 || Auth::user()->user_role_id== 4)
                        <select class="form-control team-share sel-input" id="role-shareuseremail" name="useremail[]" multiple="multiple">
                        </select>
                      @else
                       <div id="example11"></div>
                       <input type="hidden" class="form-control" name="useremail" id="shareuseremail">
                      @endif
                  </div>
                  <!-- <div class="dropdown">
                     <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                     <img src="{{asset('images/front_images/user_module/pencil.png')}}">
                     </button>
                     <ul class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="dropdownMenuButton1">
                         <li><a class="dropdown-item px-3" href="#"><i class="fa fa-user-edit mr-2"></i>Edit and Share</a></li>
                         <li><a class="dropdown-item px-3" href="#"><i class="fa fa-pencil-alt mr-2"></i>Edit</a></li>
                         <li><a class="dropdown-item px-3" href="#"><i class="fa fa-comment-dots mr-2"></i>Comment</a></li>
                         <li><a class="dropdown-item px-3" href="#"><i class="fa fa-eye mr-2"></i>View</a></li>
                     
                     </ul>
                     </div> -->
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
  $('.team-share').select2({
    tags: true,
    tokenSeparators: [',', ' '],
    initSelection: function(element, callback) {                   
    }
  });
   function details(val) {  
     
       $.ajax({
           method: "GET",
           url: "{{url('user-myflowchart/')}}/" + val,
           success: function (data) {
               $('#data_updater').html(data);
           }
       });
   }
   
      function rename(val,name) {  
          $("#template_id").val(val);
          $("#template_name").val(name);
    
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
                   url: '{{ route('template-duplicate') }}',
                   data: { template_name: template_name,original_id:original_id }, 
               success: function(data){ 
                     if(data=='success')
                     { 
                       $('#template_name-success').text('Flow Chart Duplicate Successfully');
                           setTimeout(function(){
                       window.location.reload(1);
                       }, 1000);
                     } else{
                       $('#check-template_name-status').text('Flow Chart Name Already Existing...');
                     } 
                 }
             });
   
   });
   
    
</script>
@endsection