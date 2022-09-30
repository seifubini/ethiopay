{{Form::token()}}

<div class="form-group">
    {{Form::label('service_type_id',"Service Type",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{ Form::select('service_type_id',$service,null,["class"=>"form-control"])}}
        @if($errors)
        <label class="text-danger" for="service_type_id">{{ ($errors->first('service_type_id')) ? $errors->first('service_type_id') : '' }}</label>
        @endif
    </div>
</div>
<div class="form-group">
    {{Form::label('provider_name',"Service Provider",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('provider_name', null,['class'=>'form-control','placeholder'=>"Provider Name","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="provider_name">{{ ($errors->first('provider_name')) ? $errors->first('provider_name') : '' }}</label>
        @endif
    </div>
</div>
<div class="form-group">
    {{Form::label('service_id',"Service id",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('service_id', null,['class'=>'form-control','placeholder'=>"Service Name","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="service_id">{{ ($errors->first('service_id')) ? $errors->first('service_id') : '' }}</label>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-3 col-lg-10">
        {{Form::submit('Submit',['class'=>'btn btn-primary submit-button',"value"=>"submit","data-loading-text"=>"Loading..."])}}
        {{link_to(url('admin/service-providers'), $title ="Cancel", $attributes = array("class"=>"btn btn-danger center-button","data-loading-text"=>"Loading..."), $secure = null)}}
    </div>
</div>

