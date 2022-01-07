@extends('layouts.frontend-role.header')

@section('content')
<!-- <style>
    .mystylee {
        display: none !important;
    }
</style> -->
    <div class="container-fluid px-4 main_part">
        <div class="header_top">
            <div class="t-h"> 
               <h2 class="recent_flow mt-4 ml-1 mb-0 ">Theme Settings</h2>  
                <div class="distict_btn float-right"> 
                    <button style="background: #179FD7;" class="btn btn-primary mb-lg-0 mb-3" onclick="setdefaultColor('0')">Default Theme</button>
                </div>
            <p class="ml-1 mt-2">Pick your Theme</p>
            </div>
        </div>
        <div class='col-md-10 success form-group hide' id="thememsg" style="display: none;">
            <div class='alert-success alert'  role="alert">Theme updated successfully!<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-sm-2 color_card_section">
            @if(count($themes) > 0)
            @foreach($themes as $theme)
            <div class="col mb-3 boxtheme">
                <div class="card @if($theme_id==$theme->id) theme_active @endif" onclick="myFunc('{{$theme->id}}')" id="divt{{$theme->id}}">
                    <div class="card-body p-0 ">
                        <div class="choose_color mt-1" style="background:{{$theme->background_color}}" id="{{$theme->background_color}}_{{$theme->color_name}}">
                        </div>
                    </div>
                    <div class="color_name ">{{ucfirst($theme->color_name)}}</div>
                </div>
                <input type="hidden" id="font_{{$theme->id}}" value="{{$theme->font_color}}">
                <input type="hidden" id="background_color_{{$theme->id}}" value="{{$theme->background_color}}">
                <div class="modal fade theme_popup" id="exampleModalCenter_{{$theme->id}}" tabindex="-1 " role="dialog " aria-labelledby="exampleModalCenterTitle " aria-hidden="true ">
                    <div class="modal-dialog modal-dialog-centered " role="document ">
                        <div class="modal-content" id="modalClose">                            
                            <div class="modal-header pt-3 pr-4">
                                <button type="button" class="close close-btn" onclick="myClose('{{$theme->id}}')">
                            <span aria-hidden="true ">&times;</span>
                          </button>
                            </div>
                            <div class="modal-body pb-0 pt-2">
                                <div class="theme">                                    
                                    Do You want Apply <span>{{$theme->color_name}}</span> Theme
                                </div>
                            </div>
                            <div class="modal-footer pt-0">
                                <button type="button" class="btn" data-dismiss="modal" onclick="setColor('{{$theme->id}}')">Yes</button>
                                <button type="button " class="btn" onclick="myClose('{{$theme->id}}')">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
            
        </div>


    </div>

@endsection
<script>
    function myFunc(id) {
        var ele = document.getElementById("exampleModalCenter_"+id);
        ele.classList.toggle("myFunc").removeclss.hide();
    }
    function myClose(id) {
        // var code = localStorage.getItem("background_color");
        // if(code == id){
        //     localStorage.removeItem("background_color", id);
        // }
        var element = document.getElementById("exampleModalCenter_"+id);
        // element.classList.toggle("mystylee");
        $(element).removeClass( "myFunc" );
    }
    function setColor(id){
        if($('.card').hasClass('theme_active')) {
            $('.card').removeClass('theme_active');
        }
        $('#divt'+id).addClass("theme_active");
        $('.container-fluid').removeClass('default_theme');
        var font = $('#font_'+id).val();
        var background_color = $('#background_color_'+id).val();
        localStorage.setItem("background_color", background_color);
        localStorage.setItem("font_color", font);
        var element = document.getElementById("exampleModalCenter_"+id);
        $(element).removeClass( "myFunc" );
        setBackgroundColor();
        let URL =  '{{ route("role-user.theme-update") }}';
        $.post(URL,
        {
            "_token": "{{ csrf_token() }}",
            "theme_id":id
        },
        function(response) {
            if(response['success'] == '1'){
                $('#thememsg').show();
            }else{
                alert('Something went wrong!');
                return false;
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log('failure');
        });        
    }
    //default color
    function setdefaultColor(id){ 

        $('.container-fluid').addClass('default_theme'); 
          localStorage.removeItem("background_color");
          localStorage.removeItem("font_color");          
        setDefaultBackgroundColor();
        let URL =  '{{url("/role-user/set/defaulttheme")}}';
        $.post(URL,
        {
            "_token": "{{ csrf_token() }}" 
        },
        function(response) {
            if(response['success'] == '1'){
                $('#thememsg').show();
            }else{
                alert('Something went wrong!');
                return false;
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log('failure');
        });        
    }
</script>