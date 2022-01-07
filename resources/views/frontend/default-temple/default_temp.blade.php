<?php  

   $template_cat_id = (isset($_GET['template_cat_id']) && $_GET['template_cat_id'] != '') ? $_GET['template_cat_id'] : ''; 
?>
@extends('layouts.frontend.header')
@section('content')
<div class="container-fluid px-4 main_part">
   <div class="d-flex mt-5 justify-content-between align-items-center">
      <h2 class="recent_flow  ml-1 mb-0">Default Flow Charts Template</h2>

    <form action="{{route('default-template')}}" autocomplete="off" method="get" >
                <input type="hidden" name="base_url" value="{{url('/')}}">                      
                 <div class="filter_by_sec">                
                    <select class="form-pik-er" name="template_cat_id">
                        <option value="">Select Category</option>  
                          @foreach($template_category as $cat) 
                           <option value="{{$cat->id}}"  @if($template_cat_id==$cat->id) selected @endif>{{ucwords($cat->name)}}</option>
                          @endforeach 
                    </select>
                    
                    <div class="distict_btn  list-filter">
                        <button class="btn blue_btn">Filter</button>
                        <a href="{{route('default-template')}}"><button type="button" class="btn blue_btn" style="background-color:green">Reset</button></a>
                     </div>
                </div>
         </form>
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
 
   <div class="card-section d-flex flex-wrap mt-3">        
      @if(count($default_template) > 0)
      @foreach($default_template as $def_temp)
        <?php
         if(!empty($def_temp->file_name)){
           $imgsrc=$def_temp->file_name.'.png';
           $imglink=asset('images/defaultFCimages').'/'.$imgsrc;
           }else{
           $imglink=asset('images/front_images/user_module/fc-3.png'); 
           }?>
      
      <a title="Use Template" onclick="usetemplate('{{ $def_temp->id }}','{{$imglink}}')" href="#usetemplate-model" data-toggle="modal"> 

      <div class="card mb-3">
         <img src="{{$imglink}}" class="card-img-top" alt="" width="179" height="121">
         <div class="card-body p-0">
            <div class="d-flex align-items-center algorithem_part default_temp_card">
               <div class="algorithem_img">
                  <img src="{{asset('images/front_images/user_module/algorithm_img.png')}}">
               </div>
               <div class="algorithem_text">
                  <h4 class="mb-0"> {{ucwords($def_temp->template_name)}}</h4>                  
                  <p class="mb-0">Use Template  </p>
               </div>
            </div>             
         </div>
      </div>
      </a>
      @endforeach
      @else
      <div> No Default Flow Charts Template Available </div>
      @endif       
   </div>
</div>

<!-- use template-->

<div id="usetemplate-model" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">  
      <form method="POST" action="{{route('use_template_update')}}">
                      @csrf
           <div class="modal-header flex-column">       
        <h4 class="modal-title w-100">Use Template</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">     
        <div class="lbl_text"> 
          
          <div class="form-group">
             <img src="{{asset('images/front_images/user_module/fc-3.png')}}" id="use_template_img" class="card-img-top" alt="" width="250" height="250"> 
 
             <input type="hidden" value="{{url('/')}}" id="baseurl">
            <input type="hidden" name="tempid" id="tempid" value="" /> 
             <input type="hidden" name="userid" id="userid" value="{{Auth::user()->id}}" />              
             <input type="hidden" name="parentid" id="parentid" value="{{Auth::user()->parent_id}}" />              
            <div class="input_model_select">
            <select name="project_id" required="" class="form-control">
              <option value="">Select Project</option> 
                @foreach($project_fc_list as $project) 
              <option value="{{$project->id}}">{{$project->project_name}}</option>
              @endforeach 
            </select>
            <input type="text" required="" name="fc_name" placeholder="Flow Chart Name" id="fc_name" style="margin-top:10px;" class="form-control" />

        </div> 
          </div>
          <div class="modal-footer"> 
            <input type="submit" class="btn btn-warning" id="comment_btn" value="Use Template" />   
          </div> 
      </div>  
    </div>
  </form>
  </div>
</div>   

<!-- use template update-->
 

  
@endsection
@section('script')
<script>
        function usetemplate(val,img) {       
          $("#tempid").val(val);
          $("#use_template_img").attr("src",img);
        }
</script>
  
@endsection