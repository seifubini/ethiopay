@extends('layouts.app')
@section("title", "Profile")

@section('content')
<main class="ep-tabular-format">
    <section>
        <div class="container">
            <div class="tabular-main">
                <div class="tab-content">
                    <div class="tab-pane active fade in tab-content-detail">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profileblk">
                                    <div class="proimg">
                                        <img src="{{ $user->profile_picture_medium }}" alt="{{ $user->fullname }}">
                                    </div>
                                    <h2>Profile</h2>
                                    <span>Joined in {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at, 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('Y') }}</span>
                                    <a href="{{ url('profile-edit') }}" class="btn btn-default">UPDATE PHOTO</a>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="invoice-form customer-form">
                                    <div class="input-box clearfix">
                                        <h5>My Information</h5>
                                        <div class="invoice-detail pro-invoice">
                                            <p>Name<span>{{ $user->fullname }}</span></p>
                                            <p>Email<span>{{ $user->email }}</span></p>
                                            <p>Phone Number<span>{{ $user->phone_code . ' ' . $user->phone_number }}</span></p>
                                            <p class="addressblk">Address 
                                                    <span>
                                                        {{ $user->addressData->address_line_1 }},
                                                    </span>
                                                    <span>
                                                        {{ $user->addressData->cityData->name . ', ' . $user->addressData->stateData->name . ', ' . $user->addressData->countryData->name . ', ' . $user->addressData->zipcode }}
                                                    </span> 
                                            </p>
                                            <p>Federal Tax ID<span>{{ $user->federal_tax_id }}</span></p>
                                        </div>
                                        <a href="{{ url('profile-edit') }}" class="btn btn-default">Update</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push("scripts")
<script type="text/javascript">

</script>
@endpush