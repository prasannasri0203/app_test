@extends('layouts.header')
@section('content')
@if($cat_id != '')
<div class="header_left">
    <h1> Edit Template Category</h1>
</div>
@else
<div class="header_left">
    <h1> Add New Template Category</h1>
</div>
@endif

<div class="inner-wrapper-g">
    <div class="filter-sec d-flex align-items-center w-bg flex-wrap"> 
        <div class="container-fluid p-0 mt-2">
            @if (session('failure'))
                <div class="alert alert-danger" role="alert">
                    {{ session('failure') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            <form action="{{url('stored-tcategory')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Template Category Name</label>
                        @if($temp_data !=='')
                            <input type="text" class="@error('name') is-invalid @enderror" name="name" value="{{$temp_data->name}}" placeholder="Name" autocomplete="off">
                        @else
                            <input type="text" class="@error('name') is-invalid @enderror" name="name" value="{{old('name') }}" placeholder="Name" autocomplete="off">
                        @endif
                            @error('name')
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
                                @if($temp_data !=='')
                                   @if($temp_data->status == 1)
                                   <option selected="" value="1" >Active</option>
                                   <option value="0" >Inactive</option>
                                   @else
                                   <option selected value="0" >Inactive</option>
                                   <option  value="1" >Active</option>
                                   @endif
                                @else
                                    <option value="1" >Active</option>
                                    <option value="0" >Inactive</option>
                                @endif
                            </select>
                        </div>
                    </div>  
                
            </div>
            <div class="button_section">
                @if($temp_data !=='')
                   <input type="hidden" value="{{$temp_data->id}}" name="template_categorys_id">
                   <button type="submit" class="btn blue_btn">Update</button>
                @else
                   <button type="submit" class="btn blue_btn">Add Category</button>
                @endif
                <a href="{{URL('tcategory')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form> 
    </div>
</div> 
</div>
@endsection