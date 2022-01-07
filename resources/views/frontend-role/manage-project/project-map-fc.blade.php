@extends('layouts.frontend-role.header')
<?php use App\Models\UserTemplate;?>
@section('content')
   <!-- Whole card section start -->
    <div class="container-fluid px-4 main_part">
        <!-- first card section -->
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="recent_flow mt-4 ml-1 mb-0">{{ucwords($project->project_name)}}</h2>
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
        <?php
          date_default_timezone_set("America/Toronto");
          // print_r(new DateTime());
        ?>  
        @if(count($templates) > 0)
            @foreach($templates as $userthemp)
                <?php if(!empty($userthemp->file_name)){
                $imgsrc=$userthemp->file_name.'.png';
                $imglink=asset('images/defaultFCimages').'/'.$imgsrc;
                }else{
                 $imglink=asset('images/front_images/user_module/fc-3.png'); 
                }
                $fcstatus = 0;
                if($userthemp->status == 1 && $userthemp->is_approved == 1){
                    $fcstatus = 1;
                }
                $show_status = 0;
                if($user_role_id == 4){
                  if($userthemp->status == 1 && $userthemp->is_approved == 1){
                      $show_status = 1;
                  } 
                }else if($user_role_id == 3){
                  if($userthemp->status == 1 && ($userthemp->editor_status == 1 || $userthemp->editor_status == 2 || $userthemp->editor_status == 3 || $userthemp->editor_status == 4)){
                      $show_status = 1;
                  }
                }else{
                  $show_status = 1;
                }
                if($show_status == 1){
                ?>
                <div class="card-section d-flex flex-wrap mt-3">
                    
                    <div class="card card-box mb-3">
                        <img src="{{$imglink}}"class="card-img-top" alt="">
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center algorithem_part before_hover">
                                <div class="algorithem_img">
                                     <img src="{{asset('images/front_images/user_module/algorithm_img.png')}}"> 
                                </div>
                                <div class="algorithem_text">
                                    <h4 class="mb-0">{{ucwords($userthemp->template_name)}}</h4>
                                    <?php  $open_date ='';
                                    $date_time2='';
                                        if(optional($userthemp->userTemplateTrack)->updated_at)
                                        {
                                          if($user_id == optional($userthemp->userTemplateTrack)->user_id){
                                            $date_time2 =    optional($userthemp->userTemplateTrack)->updated_at;
                                          }
                                        } 
                                        else
                                        {
                                          if($user_id == optional($userthemp->userTemplateTrack)->user_id){
                                            $date_time2 =    optional($userthemp->userTemplateTrack)->created_at; 
                                          }
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
                                        <p class="mb-0">opened {{ $open_date }}</p>
                                      @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center algorithem_part after_hover">
                                <div class="d-flex align-items-center after_algorithem">
                                    @if($user_role_id != 4)
                                      <a href="{{url('/RU-flowchart?user='.$userthemp->id)}}"><button class="btn" type="button">Open</button></a>
                                    @else
                                      @if($fcstatus == 1) 
                                        <a title="QR Code" onclick="qrcodediv({{ $userthemp->id }})"><img src="{{asset('images/Qr Code.png')}}" style="height:20px;" ></a>
                                          <div id="qrcodeview-model{{$userthemp->id}}" class="modal qrdev">
                                            <div class="modal-dialog modal-confirm">
                                              <div class="modal-content">
                                                 <form method="POST" action="{{URL::to('/role-user/approverstatus-change')}}">
                                                                @csrf
                                                <div class="modal-header flex-column">       
                                                  <h4 class="modal-title w-100">Scan the QR Code</h4>  
                                                          <button type="button" class="close qrclose" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                            
                                                  @php
                                                  $qrurl=URL::to('RU-flowchart?user='.$userthemp->id.'&process=qrcode');
                                                  @endphp
                                                    {!! QrCode::size(250)->generate($qrurl) !!}
                                                    <div style="padding:15px; color: #636363; ">
                                                      URL :<a style="color:#636363;" href="{{$qrurl}}"> {{$qrurl}} </a>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                  <button type="button" class="btn btn-warning qrclose" data-dismiss="modal">Cancel</button>
                                                  <!-- <button type="submit" class="btn btn-warning">Approve</button> -->
                                                </div>
                                              </form>
                                              </div>
                                            </div>
                                          </div> 
                                      @endif
                                    @endif
                                    <div class="share_part" onclick="shareChart2({{ $userthemp->id }})" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        @if($fcstatus == 1)  
                                           <img src="{{asset('images/front_images/user_module/share.png')}}">
                                           <p class="mb-0">Share</p>
                                        @endif
                                    </div>
                                    <!-- <div class="share_part more" >
                                        <img src="{{asset('images/front_images/user_module/dots_more.png')}}">
                                        <p class="mb-0">More</p>
                                 
                                    </div> -->
                                </div>

                            </div>

                            <!-- <div id="load_item">
                                <ul class="list-group">
                                    <li>Open</li>
                                    <li>Rename</li>
                                    <li>Duplicate</li>
                                    <li>Delete</li>
                                    <li>Export</li>
                                    <li>Share</li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                    
                    @if(count($userthemp->flowchartMapping) > 0)
                        <div class="card_arrow mb-3"> 
                            <img src="{{asset('images/front_images/user_module/plain-arrow.png')}}" style="width: 100px;height:100px;margin-top: 38px;">
                        </div>
                        @foreach($userthemp->flowchartMapping as $mapping)
                            <?php 
                                $mapTemplate = UserTemplate::with('userTemplateTrack')->where('id',$mapping->mapped_flowchart_id)->withTrashed()->first();
                                if(!empty($mapTemplate->file_name)){
                                    $imgsrc=$mapTemplate->file_name.'.png';
                                    $imglink2=asset('images/defaultFCimages').'/'.$imgsrc;
                                }else{
                                    $imglink2=asset('images/front_images/user_module/fc-3.png'); 
                                }
                                $fcstatus2 = 0;
                                if($mapTemplate->status == 1 && $mapTemplate->is_approved == 1){
                                    $fcstatus2 = 1;
                                }
                            ?>
                            <div class="card card-box card_res mb-3">
                                <img src="{{$imglink2}}" class="card-img-top" alt="">
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center algorithem_part before_hover">
                                        <div class="algorithem_img">
                                           <img src="{{asset('images/front_images/user_module/algorithm_img.png')}}">
                                        </div>
                                        <div class="algorithem_text">
                                            <h4 class="mb-0">{{$mapTemplate->template_name}}</h4>
                                            <?php  $open_date2 ='';
                                            $date_time3 ='';
                                            if(optional($mapTemplate->userTemplateTrack)->updated_at)
                                            {
                                              if($user_id == optional($mapTemplate->userTemplateTrack)->user_id){
                                                $date_time3 =    optional($mapTemplate->userTemplateTrack)->updated_at;
                                              }
                                            } 
                                            else
                                            {
                                              if($user_id == optional($mapTemplate->userTemplateTrack)->user_id){
                                                $date_time3 =    optional($mapTemplate->userTemplateTrack)->created_at; 
                                              }
                                            }
                                            if($date_time3 !=''){
                                              $timediff = $date_time3->diff(new DateTime());
                                              //echo $timediff->format('%y year %m month %d days %h hour')."<br/>";
                                              $diff_yr= $timediff->format('%y');
                                              $diff_mon= $timediff->format('%m');
                                              $diff_days= $timediff->format('%d');
                                              $diff_hr= $timediff->format('%h');
                                              $diff_min= $timediff->format('%i');

                                              if($diff_yr != 0){
                                                $open_date2 = $diff_yr.' year ago';
                                                if($diff_yr > 1){
                                                  $open_date2 = $diff_yr.' years ago';
                                                }                      
                                              }else if($diff_mon != 0){
                                                $open_date2 = $diff_mon.' month ago';
                                                if($diff_mon > 1){
                                                  $open_date2 = $diff_mon.' months ago';
                                                } 
                                              }else if($diff_days != 0){
                                                $open_date2 = $diff_days.' day ago';
                                                if($diff_days > 1){
                                                  $open_date2 = $diff_days.' days ago';
                                                } 
                                              }else if($diff_hr != 0){
                                                $open_date2 = $diff_hr.' hour ago';
                                                if($diff_hr > 1){
                                                  $open_date2 = $diff_hr.' hours ago';
                                                } 
                                              }else if($diff_min != 0){
                                                $open_date2 = $diff_min.' minute ago';
                                                if($diff_min > 1){
                                                  $open_date2 = $diff_min.' minutes ago';
                                                } 
                                              }
                                            }
                                          ?>
                                          @if($open_date2 != '')
                                            <p class="mb-0">opened {{ $open_date2 }}</p>
                                          @endif
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center algorithem_part after_hover">
                                        <div class="d-flex align-items-center after_algorithem">
                                            @if($user_role_id != 4)
                                              <a href="{{url('/RU-flowchart?user='.$mapping->mapped_flowchart_id)}}"><button class="btn" type="button">Open</button></a>
                                            @else
                                              @if($fcstatus2 == 1) 
                                                <a title="QR Code" onclick="mqrcodediv({{ $mapping->id }})"><img src="{{asset('images/Qr Code.png')}}" style="height:20px;" ></a>
                                                  <div id="mqrcodeview-model{{$mapping->id}}" class="modal qrdev">
                                                    <div class="modal-dialog modal-confirm">
                                                      <div class="modal-content">
                                                         <form method="POST" action="{{URL::to('/role-user/approverstatus-change')}}">
                                                                        @csrf
                                                        <div class="modal-header flex-column">       
                                                          <h4 class="modal-title w-100">Scan the QR Code</h4>  
                                                                  <button type="button" class="close qrclose" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                    
                                                          @php
                                                          $qrurl=URL::to('RU-flowchart?user='.$mapping->mapped_flowchart_id.'&process=qrcode');
                                                          @endphp
                                                            {!! QrCode::size(250)->generate($qrurl) !!}
                                                            <div style="padding:15px; color: #636363; ">
                                                              URL :<a style="color:#636363;" href="{{$qrurl}}"> {{$qrurl}} </a>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                          <button type="button" class="btn btn-warning qrclose" data-dismiss="modal">Cancel</button>
                                                          <!-- <button type="submit" class="btn btn-warning">Approve</button> -->
                                                        </div>
                                                      </form>
                                                      </div>
                                                    </div>
                                                  </div> 
                                              @endif
                                            @endif
                                            @if($fcstatus2 == 1)
                                                <div class="share_part" onclick="shareChart2({{ $mapping->mapped_flowchart_id }})" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <img src="{{asset('images/front_images/user_module/share.png')}}">
                                                    <p class="mb-0">Share</p>
                                                </div>
                                            @endif    
                                            <!-- <div class="share_part more" >
                                                <img src="./images/user_module/dots_more.png">
                                                <p class="mb-0">More</p>
                                               
                                            </div> -->
                                        </div>

                                    </div>

                                    <!-- <div id="load_item">
                                        <ul class="list-group">
                                            <li>Open</li>
                                            <li>Rename</li>
                                            <li>Duplicate</li>
                                            <li>Delete</li>
                                            <li>Export</li>
                                            <li>Share</li>
                                        </ul>
                                    </div> -->
                                </div>
                                

                            </div>
                        @endforeach
                    @endif
                </div>
              <?php }?>
            @endforeach
        @else
            <div> No Flow Chart Available </div>
        @endif
         
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
                          <select class="form-control admin-share sel-input" id="role-shareuseremail" name="useremail[]" multiple="multiple">
                          </select>
                          
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
    <!-- Whole card section end -->
@endsection
@section('script')
<script>
  $('.admin-share').select2({
    tags: true,
    tokenSeparators: [',', ' '],
    initSelection: function(element, callback) {                   
    }
  });
  
  function qrcodediv(id){
    $('#qrcodeview-model'+id).show();
  }
  $('.qrclose').click(function(){
    $('.qrdev').hide();
  });
  function mqrcodediv(id){
    $('#mqrcodeview-model'+id).show();
  }
</script>
@endsection