<html>
<body>
<form method="POST" action="{{url('/angular-tool-save')}}"  enctype="multipart/form-data">
             @csrf
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            <div>
            	<label>Flowchart Name<span style="color: red">*</span></label>
                                    <input type="text"  placeholder="Flowchart Name" name="flowchart_name">
                                    @error('flowchart_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
            </div>
              <div>
            	<label>Flowchart<span style="color: red">*</span></label>
                                    <input type="file" name="flowchart">
                                    @error('flowchart')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
            </div>
             <div>
                <label>Product Id<span style="color: red">*</span></label>
                <select name="project_id">
                                  @foreach($projects as $prj)
<option value="{{$prj->id}}">{{$prj->project_name}}</option>
                                  @endforeach
                </select>
            </div>
            <input type="submit" value="submit">
 </form>

 <div>
    <ul>
@if (count($flowcharts) > 0)
 @foreach($flowcharts as $flw)
<li><a href="/assets/angular/assets/flowcharts/{{$flw->file_name}}" target="_blank" > click me to flowchart </a></li>
 @endforeach
 @endif

 
</ul>
</div>
 </body>
 </html>           