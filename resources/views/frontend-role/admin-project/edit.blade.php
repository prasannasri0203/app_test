@extends('layouts.frontend-role.header')

@section('content')
    <div class="container-fluid px-md-4 px-2 main_part">
        <div class="t-h">
            <h4 class="Acc_Setting name-title">Edit Flow Chart Project</h4>           
        </div>
        <form method="post"  action="{{route('update-project',$flowchart_project->id)}}">
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
                                           <input type="hidden" value="{{$flowchart_project->id}}" placeholder="Project Name" name="id">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Project Name<span style="color: red">*</span></label>
                                    <?php $project_name =   (old('project_name'))?old('project_name'):$flowchart_project->project_name;?>
                                    <input type="text" value="{{$project_name}}" placeholder="Project Name" name="project_name">
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
                                     <?php $description =   (old('description'))?old('description'):$flowchart_project->description;?>

                                    <textarea name="description" placeholder="Description" row="2" class="form-control"> {{$description}} </textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" value="0" name="team_user_id">
                            <input type="hidden" value="0" name="admin_id">
                            

                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="pr-la-in">
                                    <label>Flowchart Editor</label>
                                        <?php $editor_id =   (old('editor_id'))?old('editor_id'):$flowchart_project->editor_id;?>
                                    <select name="editor_id" id="editors">
                                    <option value=""> Select  Editor</option>
                                        @foreach($editorList as $list)
                                        <option value="{{ $list->id }}"  @if($editor_id == $list->id) selected @endif>{{ ucfirst($list->name) }} </option>
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
                                       <?php $approver_id =   (old('approver_id'))?old('approver_id'):$flowchart_project->approver_id;?>
                                    <select name="approver_id" id="approvers">
                                    <option value=""> Select  Approver</option>
                                        @foreach($approverList as $list)
                                        <option value="{{ $list->id }}"  @if($approver_id == $list->id) selected @endif>{{ ucfirst($list->name) }} </option>
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
                                    <?php $viewer_id =   (old('viewer_id'))?old('viewer_id'):explode(',', $flowchart_project->viewer_id);?>
                                    <select name="viewer_id[]" id="viewers" multiple="">
                                    <option value=""> Select  Viewer</option>
                                        @foreach($viewerList as $list)
                                        <option value="{{ $list->id }}" @if(in_array($list->id,$viewer_id)) selected @endif>{{ ucfirst($list->name) }} </option>
                                        @endforeach
                                    </select>
                                    @error('viewer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                              <div class="pr-la-in">
                                    <label>Status</label>
                                       <?php $status =   (old('status'))?old('status'):$flowchart_project->status;?>
                                    <select name="status">
                                    <option value="1" @if($status ==1) selected @endif> Active</option>
                                      <option value="0" @if($status ==0) selected @endif> Inactive</option>
                                    </select>
                                    @error('approver_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            
                          
                        </div>
                    </div>
                </div>
            </div>
            <div class="button_section">
                <button class="btn blue_btn">Update </button>
                <a href="{{route('projects')}}"><button type="button" class="btn white_btn">Cancel</button></a>
            </div>
        </form>
    </div>
@endsection