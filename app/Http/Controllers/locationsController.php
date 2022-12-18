<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\SubCity;
class locationsController extends Controller
{
    public function addCountry(Request $request){
        $this->validate($request, [
            'country_name' => "required"
        ]);
        
        $country = new Country;
        $country->country_name = $request->country_name;
        $country->save();

        return $country;
    }

    public function getCountries(){
        return Country::all();
    }

    public function addCity(Request $request){
        $this->validate($request, [
            'city_name' => "required",
            'country_id' => "required"
        ]);
        
        $city = new City;
        $city->city_name = $request->city_name;
        $city->country_id = $request->country_id;
        $city->save();

        return $city;
    }

    public function getCities(){
        return City::leftJoin('countries', 'cities.country_id', 'countries.id')
                   ->select('cities.id', 'countries.country_name', 'cities.city_name', 'cities.country_id')->get();
    }

    public function addSubCity(Request $request){
        $this->validate($request, [
            'sub_city_name' => "required",
            'city_id' => "required"
        ]);
        
        $subCity = new SubCity;
        $subCity->sub_city_name = $request->sub_city_name;
        $subCity->city_id = $request->city_id;
        $subCity->save();

        return $subCity;
    }
   
    public function getSubCities(){
        return SubCity::leftJoin('cities', 'cities.id', 'sub_cities.city_id')
                      ->select('sub_cities.id', 'sub_city_name', 'cities.city_name', 'sub_cities.city_id')->get();
    }

    public function updateSubCity(Request $request, $id){
        $this->validate($request, [
            'sub_city_name' => "required",
            'city_id' => "required"
        ]);
        
        $subCity = SubCity::find($id);
        $subCity->sub_city_name = $request->sub_city_name;
        $subCity->city_id = $request->city_id;
        $subCity->save();

        return $subCity;
    }

    public function updateCountry(Request $request, $id){
        $this->validate($request, [
            'country_name' => "required"
        ]);
        
        $country = Country::find($id);
        $country->country_name = $request->country_name;
        $country->save();

        return $country;
    }

    public function updateCity(Request $request, $id){
        $this->validate($request, [
            'city_name' => "required",
            'country_id' => "required"
        ]);
        
        $city = City::find($id);
        $city->city_name = $request->city_name;
        $city->country_id = $request->country_id;
        $city->save();

        return $city;
    }

    public function deleteSubCity($id){
        if(auth()->user()->user_role == 'ADMIN'){
            $subCity = SubCity::find($id);
            $subCity->delete();

            return $subCity;
        }
        else{
            return response('Unauthorized', 422);
        }
    }

    public function deleteCity($id){
        if(auth()->user()->user_role == 'ADMIN'){
            $city = City::find($id);
            $city->delete();

            return $city;
        }
        else{
            return response('Unauthorized', 422);
        }
    }

    public function deleteCountry($id){
        if(auth()->user()->user_role == 'ADMIN'){
            $country = Country::find($id);
            $country->delete();

            return $country;
        }
        else{
            return response('Unauthorized', 422);
        }
    }
}
