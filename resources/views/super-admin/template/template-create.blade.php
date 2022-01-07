@extends('layouts.header')
@section('content')
<div class="header_left">
    <h1> Add New Template  </h1>
</div>
<div class="inner-wrapper-g">
    <div class="filter-sec d-flex align-items-center w-bg flex-wrap">  
        <div class="container-fluid p-0 mt-2">
            
            <form action="{{url('stored-template')}}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Template Category Name <span style="color: red;margin-left: 0px; ">*</span></label>
                            <select class="form-pik-er frm_width"  name="template_category_id">
                                <option value="" >Select</option>
                                @foreach($tcategorys as $value)
                                <option value="{{$value->id}}" >{{$value->name}}</option>
                                @endforeach
                            </select>
                            @error('template_category_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Template Name <span style="color: red;margin-left: 0px; ">*</span></label>
                            <input type="text" class="@error('name') is-invalid @enderror" name="name" value="{{old('name') }}" placeholder="Name" autocomplete="off">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Template Image <span style="color: red;margin-left: 0px; ">*</span></label>
                            <input type="file" class="@error('name') is-invalid @enderror" name="image" value="{{old('name') }}" placeholder="Name" autocomplete="off">
                            @error('image')
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
                                <option value="1" >Active</option>
                                <option value="0" >Inactive</option>
                            </select>
                        </div>
                    </div>  
                
            </div>
            <div class="button_section">
                <button type="submit" class="btn blue_btn"> Add  Template  </button>
                <a href="{{url('/template')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form> 
    </div>
</div> 
</div>
@endsection
 
 