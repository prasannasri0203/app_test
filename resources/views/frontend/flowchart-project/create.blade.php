@extends('layouts.frontend.header')

@section('content')
    <div class="container-fluid px-md-4 px-2 main_part">
        <div class="t-h">
            <a href="{{route('flowchart-project.index')}}">
                <h4 class="Acc_Setting name-title">Create Flow Chart Project</h4>
            </a>
           
        </div>
        <form method="post" action="{{route('flowchart-project.store')}}">
         @csrf
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                </div>
            @endif
            <div class="inner-wrapper-g">
                <div class="filter-sec d-flex align-items-center w-bg flex-wrap">
                    <div class="container-fluid p-0 mt-4">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Project Name<span style="color: red">*</span></label>
                                    <input type="text" value="{{old('project_name')}}" placeholder="Project Name" name="project_name">
                                    @error('project_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Description</label>
                                 

                                    <textarea name="description" placeholder="Description" row="2" class="form-control"> {{old('description')}} </textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                           
                            @if(Auth::user()->user_role_id == 4)
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="pr-la-in">
                                    <input type="hidden" id="baseurl" value="{{url('/')}}">
                                        <label>Team User</label>
                                        <select name="team_user_id" id="team_user_id">
                                        <option value=""> Select Team User</option>
                                            @foreach($teamList as $list)
                                            <option value="{{ $list->id }}">{{ ucfirst($list->name) }} </option>
                                            @endforeach
                                        </select>
                                        @error('team_user_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif  
                            @if(Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 3)
                            @else  
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Flowchart Admin</label>
                                    <select name="admin_id" id="admins">
                                    <option value=""> Select  Admin</option>
                                        @foreach($adminList as $list)
                                        <option value="{{ $list->id }}">{{ ucfirst($list->name) }} </option>
                                        @endforeach
                                    </select>
                                    @error('admin_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Flowchart Editor</label>
                                    <select name="editor_id" id="editors">
                                    <option value=""> Select  Editor</option>
                                        @foreach($editorList as $list)
                                        <option value="{{ $list->id }}">{{ ucfirst($list->name) }} </option>
                                        @endforeach
                                    </select>
                                    @error('editor_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                              <div class="pr-la-in">
                                    <label>Flowchart Approver</label>
                                    <select name="approver_id" id="approvers">
                                    <option value=""> Select  Approver</option>
                                        @foreach($approverList as $list)
                                        <option value="{{ $list->id }}">{{ ucfirst($list->name) }} </option>
                                        @endforeach
                                    </select>
                                    @error('approver_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                              <div class="pr-la-in">
                                    <label>Flowchart Viewer</label>
                                    <select name="viewer_id[]" id="viewers" multiple="">
                                    <option value=""> Select  Viewer</option>
                                        @foreach($viewerList as $list)
                                        <option value="{{ $list->id }}">{{ ucfirst($list->name) }} </option>
                                        @endforeach
                                    </select>
                                    @error('viewer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            @endif
                          
                        </div>
                    </div>
                </div>
            </div>
            <div class="button_section">
                <button class="btn blue_btn">Save</button>
                <a href="{{route('flowchart-project.index')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form>
    </div>
@endsection