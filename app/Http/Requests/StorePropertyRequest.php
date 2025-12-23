<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'land_type' => 'required|in:Agriculture,Residential Plot,Commercial Plot',
            'state' => 'required|exists:states,id',
            'district' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'area' => 'required|string|max:255',
            'full_address' => 'nullable|string',
            'google_map_lat' => 'required|numeric|between:-90,90',
            'google_map_lng' => 'required|numeric|between:-180,180',
            'plot_area' => 'required|numeric|min:0',
            'plot_area_unit' => 'required|in:sq ft,sq yd,acre',
            'frontage' => 'nullable|numeric|min:0',
            'depth' => 'nullable|numeric|min:0',
            'road_width' => 'required|numeric|min:0',
            'corner_plot' => 'nullable|boolean',
            'gated_community' => 'nullable|boolean',
            'ownership_type' => 'required|in:Freehold,Leasehold',
            'price' => 'required|numeric|min:0',
            'price_negotiable' => 'nullable|boolean',
            'contact_name' => 'required|string|max:255',
            'contact_mobile' => 'required|string|max:15',
            'description' => 'required|string',
            'property_images' => 'required|array|min:2|max:10',
            'property_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'property_video' => 'nullable|file|mimes:mp4,mov,avi|max:51200',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $district = \App\Models\District::find($this->district);
            if ($district && $district->state_id != $this->state) {
                $validator->errors()->add('district', 'The selected district does not belong to the selected state.');
            }

            $city = \App\Models\City::find($this->city_id);
            if ($city && $city->district_id != $this->district) {
                $validator->errors()->add('city_id', 'The selected city does not belong to the selected district.');
            }
        });
    }
}