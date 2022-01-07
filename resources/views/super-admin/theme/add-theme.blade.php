@extends('layouts.header')
@section('content')
<div class="header_left">
    <h1>@if(array_key_exists('addeditthemevalue',$Module)) Edit Theme  @else Add Theme  @endif</h1>
</div>
<div class="inner-wrapper-g">
    <div class="filter-sec d-flex align-items-center w-bg flex-wrap">  
        <div class="container-fluid p-0 mt-2">
            <?php 
            if(old('theme_name')) $theme_name  = old('theme_name');
            else $theme_name =  '';                     
    

            if(old('background_color')) $background_color  = old('background_color');
            else $background_color =  '';

            if(old('font_color')) $font_color  = old('font_color');
            else $font_color =  '';
            
            if(old('status')) $status    =   old('status');
            else $status =   ''; 
            
            $theme_id  =   '';

            if(array_key_exists('addeditthemevalue',$Module)){

                if(old('theme_name')) $theme_name = old('theme_name');
                else $theme_name =  $Module['addeditthemevalue'][0]->color_name;
 

                if(old('background_color')) $background_color = old('background_color');
                else $background_color =  $Module['addeditthemevalue'][0]->background_color;

                if(old('status')) $status    =   old('status');
                else $status =  $Module['addeditthemevalue'][0]->status;

                if(old('font_color')) $font_color    =   old('font_color');
                else $font_color =  $Module['addeditthemevalue'][0]->font_color;
                
                $theme_id = $Module['addeditthemevalue'][0]->id;
            }

            ?>
            <form action="{{url('stored-theme')}}" method="POST">
                @csrf
                <input type="hidden" name="theme_id" value="{{$theme_id}}"/>
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Theme Name <span style="color: red;margin-left: 0px; ">*</span></label>
                            <input type="text" class="@error('theme_name') is-invalid @enderror" name="theme_name" value="{{$theme_name}}" placeholder="Theme Name" autocomplete="off">
                            @error('theme_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                   
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <div class="pr-la-in">
                        <label>Background Color <span style="color: red;margin-left: 0px; ">*</span></label>
                        <input data-jscolor="{}" class=" @error('background_color') is-invalid @enderror" name="background_color" value="{{$background_color}}" placeholder="Background Color" autocomplete="off">
                        @error('background_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 price_col hide">
                    <div class="pr-la-in">
                        <label>Font Color <span style="color: red;margin-left: 0px; ">*</span></label>
                        <input data-jscolor="{}" class=" @error('font_color') is-invalid @enderror" name="font_color" value="{{$font_color}}" placeholder="Font Color" autocomplete="off">
                        @error('font_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div> 
                                  

                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 ">
                    <div class="pr-la-in ">
                        <label>Status</label>
                        <select class="form-pik-er frm_width"  name="status">
                            <option value="1" @if($status==1) selected @endif>Active</option>
                            <option value="0" @if($status=='0') selected @endif>Inactive</option>
                        </select>
                    </div>
                </div>  
                
            </div>
            <div class="button_section">
                <button type="submit" class="btn blue_btn">@if(array_key_exists('addeditthemevalue',$Module)) Update   @else Add Theme  @endif</button>
                <a href="{{url('/themes')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form> 
    </div>
</div> 
</div>
@endsection
<script  src="{{asset('js/jscolor.js')}}" type="text/javascript"></script>
<script  src="{{asset('js/jscolor.min.js')}}" type="text/javascript"></script>
 