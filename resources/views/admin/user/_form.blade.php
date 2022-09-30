{{Form::token()}}

<div class="form-group">
    {{Form::label('firstname',"First Name",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('firstname', null,['class'=>'form-control','placeholder'=>"First Name","maxlength"=>225])}}
        @if($errors)
        <?php
//            echo "<pre>";
//            print_r($errors);
//            exit;
        ?>
        <label class="text-danger" for="firstname">{{ ($errors->first('firstname')) ? $errors->first('firstname') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('lastname',"Last Name",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('lastname', null,['class'=>'form-control','placeholder'=>"Last Name","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="lastname">{{ ($errors->first('lastname')) ? $errors->first('lastname') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('email',"Email",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('email', null,['id'=>'email','class'=>'form-control','placeholder'=>"Email","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="email">{{ ($errors->first('email')) ? $errors->first('email') : '' }}</label>
        @endif
        <span id="email_checking" class="hide">Please wait...</span>
    </div>
</div>

<div class="form-group">
    {{Form::label('password',"Password",['class'=>empty($user)?'control-label col-lg-3 required':'control-label col-lg-3'])}}
    <div class="col-lg-6">
        {{Form::password('password',['class'=>'form-control','placeholder'=>"Password","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="password">{{ ($errors->first('password')) ? $errors->first('password') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('phone_code',"Phone code",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::select('phone_code',$phone_codes,null,['class'=>'form-control select2-select','id'=>'phone_code',"maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="phone_code">{{ ($errors->first('phone_code')) ? $errors->first('phone_code') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('phone_number',"Phone Number",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('phone_number',null,['class'=>'form-control','placeholder'=>"Phone Number","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="phone_number">{{ ($errors->first('phone_number')) ? $errors->first('phone_number') : '' }}</label>
        @endif
        <span id="phone_number_checking" class="hide">Please wait...</span>
    </div>
</div>

<div class="form-group">
    {{Form::label('ethiopia_phone_code',"Ethiopia Phone Code",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::select('ethiopia_phone_code',$phone_codes,null,['class'=>'form-control select2-select','id'=>'ethiopia_phone_code',"maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="ethiopia_phone_code">{{ ($errors->first('ethiopia_phone_code')) ? $errors->first('ethiopia_phone_code') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('ethiopia_phone_number',"Ethiopia Phone Number",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('ethiopia_phone_number',null,['class'=>'form-control','placeholder'=>"Ethiopia Phone Number","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="ethiopia_phone_number">{{ ($errors->first('ethiopia_phone_number')) ? $errors->first('ethiopia_phone_number') : '' }}</label>
        @endif
        <span id="ethiopia_phone_number_checking" class="hide">Please wait...</span>
    </div>
</div>


<div class="form-group">
    {{Form::label('profile_picture',"Profile picture",['class'=>empty($user)?'control-label col-lg-3 required':'control-label col-lg-3'])}}
    <div class="col-lg-6">
        {{Form::file('profile_picture',['placeholder'=>"","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="profile_picture">{{ ($errors->first('profile_picture')) ? $errors->first('profile_picture') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('federal_tax_id',"Federal Tax ID",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('federal_tax_id',null,['class'=>'form-control','placeholder'=>"Federal Tax ID","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="federal_tax_id">{{ ($errors->first('federal_tax_id')) ? $errors->first('federal_tax_id') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('country_id',"Country",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::select('country_id',$countries,null,['id'=>'country_id','class'=>'form-control select2-select',"maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="country_id">{{ ($errors->first('country_id')) ? $errors->first('country_id') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('state_id',"State",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::select('state_id',[""=>"State"],null,['id'=>'state_id','class'=>'form-control select2-select',"maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="state_id">{{ ($errors->first('state_id')) ? $errors->first('state_id') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('city_id',"City",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::select('city_id',[""=>"City"],null,['id'=>'city_id','class'=>'form-control select2-select',"maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="city_id">{{ ($errors->first('city_id')) ? $errors->first('city_id') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('address_line_1',"Address",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('address_line_1',null,['class'=>'form-control','placeholder'=>"Address","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="address_line_1">{{ ($errors->first('address_line_1')) ? $errors->first('address_line_1') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    {{Form::label('zipcode',"Zipcode",['class'=>'control-label col-lg-3 required'])}}
    <div class="col-lg-6">
        {{Form::text('zipcode',null,['class'=>'form-control','placeholder'=>"Zipcode","maxlength"=>225])}}
        @if($errors)
        <label class="text-danger" for="zipcode">{{ ($errors->first('zipcode')) ? $errors->first('zipcode') : '' }}</label>
        @endif
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-10">
        {{Form::submit('Submit',['class'=>'btn btn-primary submit-button','id'=>'submitButton',"value"=>"Submit","data-loading-text"=>"Loading..."])}}
        {{link_to(url('admin/users'), $title ="Cancel", $attributes = array("class"=>"btn btn-danger center-button","data-loading-text"=>"Loading..."), $secure = null)}}
    </div>
</div>

@push('scripts')

<script>
    var country_id = "{{isset($user->country_id)?$user->country_id:0}}";
    var state_id = "{{isset($user->state_id)?$user->state_id:0}}";
    var city_id = "{{isset($user->city_id)?$user->city_id:0}}";
    {{--  $('.chosen-select').chosen({no_results_text: 'Oops, nothing found!'});  --}}

    $(".select2-select").select2({width: '100%'});

    if (country_id > 0 && state_id > 0)
    {
        getStates(country_id);
        getCities(state_id);
    }

    if($('#country_id').val() > 0) {
        country_id = $('#country_id').val();
        if("{{ old('state_id') }}") {
            state_id = "{{ old('state_id') }}";
            getStates(country_id);
        }
        if("{{ old('city_id') }}") {
            city_id = "{{ old('city_id') }}";
            getCities(state_id);
        }
    }

    $('#country_id').change(function () {
        getStates($(this).val());
    });

    function getStates(country_id) {
        var options = "";
        if (country_id) {
            $.ajax({
                url: "{{url('state/getStatesByCountry')}}/" + country_id,
                type: "GET",
                beforeSend: function () {

                },
                success: function (stateResult) {
                    if (stateResult.status == true) {
                        $(stateResult.states).each(function (index, state) {
                            if (state_id > 0 && state_id == state.id)
                                options += "<option value='" + state.id + "' selected>" + state.name + "</option>";
                            else
                                options += "<option value='" + state.id + "'>" + state.name + "</option>";
                        });
                        $('#state_id').html(options);
                    } else {
                        $('#state_id').html(options);
                    }
                    {{--  $("#state_id").trigger("chosen:updated");  --}}
                },
                error: function (xhr, status, error) {
                    $('#state_id').html(options);
                    {{--  $("#state_id").trigger("chosen:updated");  --}}
                }
            });
        } else {
            $('#state_id').html(options);
            {{--  $("#state_id").trigger("chosen:updated");  --}}
        }
        
    }

    $('#state_id').change(function () {
        getCities($(this).val());
    });
    function getCities(state_id) {
        var options = "";
        if (state_id) {
            $.ajax({
                url: "{{url('city/getCitiesByState')}}/" + state_id,
                type: "GET",
                beforeSend: function () {

                },
                success: function (cityResult) {
                    if (cityResult.status == true) {
                        $(cityResult.cities).each(function (index, city) {
                            if (city_id > 0 && city_id == city.id)
                                options += "<option value='" + city.id + "' selected>" + city.name + "</option>";
                            else
                                options += "<option value='" + city.id + "'>" + city.name + "</option>";
                        });
                        $('#city_id').html(options);
                    } else {
                        $('#city_id').html(options);
                    }
                    {{--  $("#city_id").trigger("chosen:updated");  --}}
                },
                error: function (xhr, status, error) {
                    $('#city_id').html(options);
                    {{--  $("#city_id").trigger("chosen:updated");  --}}
                }
            });
        } else {
            $('#city_id').html(options);
            {{--  $("#city_id").trigger("chosen:updated");  --}}
        }
        
    }

</script>
@endpush