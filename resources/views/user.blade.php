@extends('layouts.header')
@section('content')
    <div class="header_left">
        <h1>Add Users</h1>
    </div>
    <div class="inner-wrapper-g">
        <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
            <div class="container-fluid p-0 mt-2">
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Full Name</label>
                            <input type="text" placeholder="James White">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Email Address</label>
                            <input type="text" placeholder="johnie84@gmail.com">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Organization Name</label>
                            <input type="text" placeholder="KO Entriprise Ltd">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Address</label>
                            <input type="text" placeholder="132, My Street">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>City</label>
                            <select>
                                <option value="">New york</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Province</label>
                            <select>
                                <option value="">Brooklyn</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="pr-la-in">
                            <label>Postal Code</label>
                            <input type="text" placeholder="100001">
                        </div>
                    </div>
                </div> 
                <div class="button_section">
                    <button class="btn blue_btn">Add Users</button>
                    <a href="#"><button class="btn white_btn">Cancel</button></a>
                </div> 
            </div>   
        </div>
    </div>
@endsection