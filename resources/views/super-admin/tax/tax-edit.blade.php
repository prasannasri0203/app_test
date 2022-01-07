@extends('layouts.header')
@section('content')
<div class="header_left">
    <h1> Edit Tax  </h1>
</div>
<div class="inner-wrapper-g">
    <div class="filter-sec d-flex align-items-center w-bg flex-wrap">  
        <div class="container-fluid p-0 mt-2">
            @if (session('exist'))
                <div class="alert alert-warning" role="alert">
                    {{ session('exist') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            <form action="{{url('update-tax/'.$tax_edit['edit_value']->id)}}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>State Name<span style="color: red;margin: 0px">*</span></label>
                            <select class="form-pik-er frm_width"  name="state_id">
                                <option value="">Select</option>

                                @foreach($tax_edit['states'] as $value)
                                  @if($value->states_name == $tax_edit['edit_value']->states_name)
                                    <option value="{{$value->id}}" selected="">{{$value->states_name}}</option>
                                  @else
                                    <option value="{{$value->id}}">{{$value->states_name}}</option>
                                  @endif
                                @endforeach
                            </select>
                            @error('state_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>The state name field is required</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>GST<span style="color: red;margin: 0px">*</span></label>
                            <input type="text" class="@error('gst') is-invalid @enderror" name="gst" value="{{$tax_edit['edit_value']->gst}}" placeholder="GST" autocomplete="off">
                            @error('gst')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>PST<span style="color: red;margin: 0px">*</span></label>
                            <input type="text" class="@error('pst') is-invalid @enderror" name="pst" value="{{$tax_edit['edit_value']->pst}}" placeholder="PST" autocomplete="off">
                            @error('pst')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>HST<span style="color: red;margin: 0px">*</span></label>
                            <input type="text" class="@error('hst') is-invalid @enderror" name="hst" value="{{$tax_edit['edit_value']->hst}}" placeholder="HST" autocomplete="off">
                            @error('hst')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>QST<span style="color: red;margin: 0px">*</span></label>
                            <input type="text" class="@error('qst') is-invalid @enderror" name="qst" value="{{$tax_edit['edit_value']->qst}}" placeholder="QST" autocomplete="off">
                            @error('qst')
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
                                <option value="1" <?php if ($tax_edit['edit_value']->status == 1): ?> selected <?php endif ?> >Active</option>
                                <option value="0" <?php if ($tax_edit['edit_value']->status == 0): ?> selected <?php endif ?> >Inactive</option>
                            </select>
                        </div>
                    </div>  
            </div>
            <div class="button_section">
                <button type="submit" class="btn blue_btn"> Update Tax  </button>
                <a href="{{url('/tax')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form> 
    </div>
</div> 
</div>
@endsection
 
 