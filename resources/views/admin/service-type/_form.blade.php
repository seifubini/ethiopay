{{Form::token()}}

<div class="form-group">
    {{Form::label('service_name',"Service Type",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('service_name', null,['class'=>'form-control','placeholder'=>"Service Name","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="service_name">{{ ($errors->first('service_name')) ? $errors->first('service_name') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('payment_fee_in_percentage',"Payment Fee",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        <div class="input-group" id="payment_fee">
            {{Form::text('payment_fee_in_percentage', null,['class'=>'form-control','placeholder'=>"Payment Fee","maxlength"=>225])}}
            <div class="input-group-addon">%</div>
        </div>
        @if($errors)
        <label class="text-danger" for="payment_fee_in_percentage">{{ ($errors->first('payment_fee_in_percentage')) ? $errors->first('payment_fee_in_percentage') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-10">
        {{Form::submit('Submit',['class'=>'btn btn-primary submit-button',"value"=>"Submit","data-loading-text"=>"Loading..."])}}
        {{link_to(url('admin/service-types'), $title ="Cancel", $attributes = array("class"=>"btn btn-danger center-button","data-loading-text"=>"Loading..."), $secure = null)}}
    </div>
</div>

