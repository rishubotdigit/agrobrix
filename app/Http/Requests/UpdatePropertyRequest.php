<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'land_type' => 'required|in:Agriculture,Residential Plot,Commercial Plot',
            'state' => 'required|exists:states,id',
            'district' => 'required|exists:districts,id',
            'area' => 'required|string|max:255',
            'full_address' => 'required|string',
            'plot_area' => 'required|numeric|min:0',
            'plot_area_unit' => 'required|in:sq ft,sq yd,acre',
            'frontage' => 'nullable|numeric|min:0',
            'road_width' => 'required|numeric|min:0',
            'corner_plot' => 'nullable|boolean',
            'gated_community' => 'nullable|boolean',
            'price' => 'required|numeric|min:0',
            'price_negotiable' => 'nullable|boolean',
            'contact_name' => 'required|string|max:255',
            'contact_mobile' => 'required|string|max:15',
            'description' => 'required|string',
            'property_images' => 'nullable|array|min:2|max:10',
            'property_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'property_video' => 'nullable|file|mimes:mp4,mov,avi|max:30720',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'slug' => 'nullable|string|max:255|unique:properties,slug,' . $this->route('property')->id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ];

        $mapEnabled = \App\Models\Setting::get('map_enabled', '1') == '1';
        if ($mapEnabled) {
            $rules['google_map_lat'] = 'required|numeric|between:-90,90';
            $rules['google_map_lng'] = 'required|numeric|between:-180,180';
        } else {
            $rules['google_map_lat'] = 'nullable|numeric|between:-90,90';
            $rules['google_map_lng'] = 'nullable|numeric|between:-180,180';
        }

        return $rules;
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your property.',
            'title.string' => 'The property title must be text.',
            'title.max' => 'The property title cannot exceed 255 characters.',

            'land_type.required' => 'Please select the type of land.',
            'land_type.in' => 'Please select a valid land type (Agriculture, Residential Plot, or Commercial Plot).',

            'state.required' => 'Please select the state where the property is located.',
            'state.exists' => 'The selected state is invalid.',

            'district.required' => 'Please select the district where the property is located.',
            'district.exists' => 'The selected district is invalid.',

            'area.required' => 'Please enter the area or locality name.',
            'area.string' => 'The area name must be text.',
            'area.max' => 'The area name cannot exceed 255 characters.',

            'full_address.required' => 'Please enter the full address of the property.',
            'full_address.string' => 'The full address must be text.',

            'plot_area.required' => 'Please enter the plot area.',
            'plot_area.numeric' => 'The plot area must be a number.',
            'plot_area.min' => 'The plot area cannot be negative.',

            'plot_area_unit.required' => 'Please select the unit for plot area.',
            'plot_area_unit.in' => 'Please select a valid unit (sq ft, sq yd, or acre).',

            'frontage.numeric' => 'The frontage must be a number.',
            'frontage.min' => 'The frontage cannot be negative.',

            'road_width.required' => 'Please enter the road width.',
            'road_width.numeric' => 'The road width must be a number.',
            'road_width.min' => 'The road width cannot be negative.',

            'corner_plot.boolean' => 'Corner plot must be yes or no.',

            'gated_community.boolean' => 'Gated community must be yes or no.',

            'price.required' => 'Please enter the total price.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price cannot be negative.',

            'price_negotiable.boolean' => 'Price negotiable must be yes or no.',

            'contact_name.required' => 'Please enter the contact person\'s name.',
            'contact_name.string' => 'The contact name must be text.',
            'contact_name.max' => 'The contact name cannot exceed 255 characters.',

            'contact_mobile.required' => 'Please enter the contact mobile number.',
            'contact_mobile.string' => 'The mobile number must be text.',
            'contact_mobile.max' => 'The mobile number cannot exceed 15 characters.',

            'description.required' => 'Please provide a description of the property.',
            'description.string' => 'The description must be text.',

            'property_images.array' => 'Property images must be uploaded as files.',
            'property_images.min' => 'Please upload at least 2 images.',
            'property_images.max' => 'You can upload a maximum of 10 images.',
            'property_images.*.image' => 'Each file must be a valid image.',
            'property_images.*.mimes' => 'Images must be in JPEG, PNG, JPG, GIF, or WebP format.',
            'property_images.*.max' => 'Each image cannot exceed 5MB in size.',

            'property_video.file' => 'The property video must be a valid file.',
            'property_video.mimes' => 'The video must be in MP4, MOV, or AVI format.',
            'property_video.max' => 'The video cannot exceed 30MB in size.',

            'amenities.array' => 'Amenities must be selected as a list.',
            'amenities.*.exists' => 'One or more selected amenities are invalid.',

            'google_map_lat.required' => 'Please select the property location on the map.',
            'google_map_lat.numeric' => 'Latitude must be a number.',
            'google_map_lat.between' => 'Latitude must be between -90 and 90.',

            'google_map_lng.required' => 'Please select the property location on the map.',
            'google_map_lng.numeric' => 'Longitude must be a number.',
            'google_map_lng.between' => 'Longitude must be between -180 and 180.',
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
        });
    }
}