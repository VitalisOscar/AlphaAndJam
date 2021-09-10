<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Screen;
use App\Rules\VideoDimension;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;

class NewAdvertRequest extends FormRequest
{

    private $advert_service;
    private $adding = false;

    function __construct(){
        $this->advert_service = \resolve(\App\Services\AdService::class);

        $current = Route::current()->getName();

        if(in_array($current, ['web.adverts.create', 'web.adverts.recreate'])){
            $this->adding = true;
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:255'],
            'text' => ['string', 'nullable', 'max:255'],
            'email' => ['email', 'nullable', 'max:255'],
            'phone' => ['regex:/0([0-9]){9,12}/', 'nullable'],
            'media' => ['nullable', 'file', 'mimetypes:image/png,image/jpg,image/jpeg,video/mp4'],
        ];

        $mime = $this->file('media') != null ? $this->file('media')->getMimeType() : null;
        if($mime && explode('/', $mime)[0] == 'image'){
            array_push($rules['media'], 'max:10240', 'dimensions:width=1920,height=1080');
        }else if($mime && explode('/', $mime)[0] == 'video'){
            array_push($rules['media'], 'max:204800', new VideoDimension(1920, 1080));
        }

        // If not adding, advert id must be supplied
        if(!$this->adding){
            $rules = array_merge($rules, [
                'advert_id' => ['required']
            ]);
        }else{
            $rules = array_merge($rules, [
                'slots' => ['required', 'array'],
                'slots.*.screen_id' => ['required', 'exists:screens,id'],
                'slots.*.play_date' => ['required', 'array'],
                'slots.*.play_date.*' => ['date', 'date_format:Y-m-d', 'after_or_equal:'.$this->advert_service->getEarliestSlotBookingDate()],
                'slots.*.package' => ['required', 'exists:packages,id'],
            ]);
        }

        return $rules;
    }

    function messages(){
        $messages = [
            'category_id.exists' => 'Select a valid category',
            'category_id.required' => 'Select a valid category',
            'phone.regex' => 'Provide a valid phone number',
            'media.required_if' => 'You need to select a media file or add some text content',
            'text.required_if' => 'You need to select a media file or add some text content',
            'media.mimetypes' => 'Only image (png,jpg,jpeg) and video (mp4) format files are supported',
            'slots.*.required' => 'You need to select at least one slot',
            'slots.*.screen_id.required' => 'Select a screen from provided screens',
            'slots.*.screen_id.exists' => 'Select a screen from provided screens',
            'slots.*.play_date.*.required' => 'Airing date is required for each slot',
            'slots.*.play_date.*.array' => 'Airing date is required for each slot',
            'slots.*.play_date.*.date' => 'Use a valid airing date',
            'slots.*.play_date.*.date_format' => 'Use a valid airing date',
            'slots.*.play_date.*.after_or_equal' => 'Airing date should be on or after '.$this->advert_service->getEarliestSlotBookingDate(),
            'slots.*.package.required' => 'Select a package for each slot',
            'slots.*.package.exists' => 'Select a package from the given options',
            'advert_id.required' => 'Advert id is required',
            'slots.*.id.required' => 'All initial slots are required',
        ];

        $mime = $this->file('media') != null ? $this->file('media')->getMimeType() : null;
        if($mime && explode('/', $mime)[0] == 'image'){
            $messages['media.min'] = 'Image file size should be a maximum of 10MB';
            $messages['media.max'] = 'Image file size should be a maximum of 10MB';
            $messages['media.dimensions'] = 'Image dimensions should be 1920x1080p';
        }else if($mime && explode('/', $mime)[0] == 'video'){
            $messages['media.min'] = 'Video file size should be a maximum of 200MB';
            $messages['media.max'] = 'Video file size should be a maximum of 200MB';
        }

        return $messages;
    }

    function failedValidation(Validator $validator){
        if($this->expectsJson() || $this->ajax()){
            $json = resolve(\App\Helpers\CustomJsonResponse::class);

            throw new HttpResponseException(
                $json->errors($validator->errors()->all())
            );
        }

        parent::failedValidation($validator);
    }
}
