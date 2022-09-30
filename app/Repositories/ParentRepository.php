<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class ParentRepository {

    public function ServiceTypeRepository() {
        $model = new \App\Models\ServiceType;
        return new ServiceTypeRepository($model);
    }

    public function ServiceProviderRepository() {
        $model = new \App\Models\ServiceProvider;
        return new ServiceProviderRepository($model);
    }

    public function UserRepository() {
        $model = new \App\Models\User();
        return new UserRepository($model);
    }

    public function SettingRepository() {
        $model = new \App\Models\Setting();
        return new SettingRepository($model);
    }

    public function CountryRepository() {
        $model = new \App\Models\Country();
        return new CountryRepository($model);
    }

    public function addressRepository() {
        $model = new \App\Models\Address;
        return new AddressRepository($model);
    }

}
