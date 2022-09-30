{{Form::token()}}
<div class="form-group">
    {{Form::label('key',"Key",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        
        {{Form::text('key', null,['class'=>'form-control','readonly'=>'true','placeholder'=>"Setting key","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="key">{{ ($errors->first('key')) ? $errors->first('key') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('Value',"Value",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('value', null,['class'=>'form-control','placeholder'=>"Setting value","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="value">{{ ($errors->first('value')) ? $errors->first('value') : '' }}</label>
        @endif
    </div>
</div>
<div class="form-group">
    {{Form::label('description',"Description",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::textarea('description', null,['class'=>'form-control','placeholder'=>"Description"])}}
        @if($errors)
        <label class="text-danger" for="value">{{ ($errors->first('description')) ? $errors->first('description') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-10">
        {{Form::submit('Submit',['class'=>'btn btn-primary submit-button',"value"=>"Submit","data-loading-text"=>"Loading..."])}}
        {{link_to(url('admin/settings'), $title ="Cancel", $attributes = array("class"=>"btn btn-danger center-button","data-loading-text"=>"Loading..."), $secure = null)}}
    </div>
</div>

