<?php 
$template_name = (isset($_GET['template_name']) && $_GET['template_name'] != '') ? $_GET['template_name'] : ''; 
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
    <a href="{{url('/user-myflowchart')}}">
      <h4 class="Acc_Setting bl">Flow Charts</h4>
    </a>
    <a href="#" class="float-right" > 
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
                     <input type="date" placeholder="To Date" name="to_date" value="{{$to_date}}" >
                     <input type="date" placeholder="End Date" name="end_date" value="{{$end_date}}" >
                    <select class="form-pik-er" name="status">
                        <option value="">Select Status</option>
                        <option value="1"  @if($status=="1") selected @endif>Active</option>
                        <option value="0"  @if($status=="0") selected @endif>Draft</option>
                    </select>
                    
                    <div class="distict_btn list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{url('/user-myflowchart')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
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
              <th>@sortablelink('template_name','NAME')</th>
              <th> DATE</th>
              <th>@sortablelink('status','STATUS')</th>
              <th class="cen">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            @if(count($usertemplate) > 0)
            @foreach($usertemplate as $key => $list)
            <tr>
              <td>{{ $key + $usertemplate->firstItem() }}</td>
              <td> @if($list->flowchartProject)
                {{ucwords($list->flowchartProject->project_name)}}
                   @endif</td>
              <td> {{ucwords($list->template_name) }}</td>
              <td>@if($list->updated_at == null)
                        {{date('m/d/Y', strtotime($list->created_at))}}
                        @else
                         {{date('m/d/Y', strtotime($list->updated_at))}}
                        @endif
                     </td>
              <td> @if($list->status ==1) <span class='btn btn-success fc_status' style='font-size: 15px;'> Active</span> @else <span class='btn btn-danger fc_status' style='font-size: 15px;'> Draft</span> @endif </td>
              <td> 

                <div class="table_last">         
                  
                    <!-- <span class="fa-eye-icon">
                      <a  title="View"><i class="fa fa-eye" style="font-size:20px"></i></a>
                    </span> -->
                    <span>
                      <a href="{{url('/flowchart?user='.$list->id)}}"><img src="{{asset('images/Edit.png')}}" title="Edit"></a>
                    </span>
                    <!-- <span>
                     <a  title="Notes"  onclick="notes('{{ $list->id }}')"  data-toggle="modal" data-target="#notes"> <img src="{{asset('images/note.png')}}" style="height:20px;" > </a>
                    </span> -->
                    
                    @if($list->status ==1)
                    <span> 
                      <a href="#" title="Share" style="color:inherit;" onclick="shareChart({{ $list->id }})" data-toggle="modal" data-target="#exampleModal"><img src="{{asset('images/front_images/user_module/share.png')}}"></a>
                    </span>
                    @else
                    <span> 
                      <a title="Share" style="color:inherit;" class="share-gray"><img src="{{asset('images/front_images/gray/share.png')}}"></a>
                    </span>
                    @endif

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
                    
                    <a title="Delete" href="{{route('flowchart-delete',[$list->id])}}"><button class="btn" id="button" type="button" onclick="return confirm('Are you sure to delete?')" > <img  src="{{asset('images/Delete.png')}}" ></button></a> 
               
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

        </form> 

      
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
            <div class="link">
              <!-- <h5>Get Sharable Link<span class="ml-2"> <img src="{{asset('images/front_images/user_module/link.png')}}"></span></h5> -->
            </div>


          </div>
          <div class="row m-0">
            <div class="Invite_Collbrators">
              <label for="exampleFormControlInput1" class="form-label d-none">Invite Collabrators</label>
              <div id="example11"></div>
              <input type="hidden" class="form-control" name="useremail" id="shareuseremail">
            </div>
                        <!-- <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-expanded="false">
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
            <input type="text"  required name="note" id="note" class="form-control" />
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
              @endsection

              @section('script')

              <script>
                var baseurl  =   $('#base_url').val();
                function details(val) {  



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
                  //url: '{{ route('myflowchart.duplicate') }}',
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

            </script>
            @endsection