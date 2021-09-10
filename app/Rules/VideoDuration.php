<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class VideoDuration implements Rule
{
    protected $min, $max;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min = null, $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $getID3 = new \getID3();

        $file = $getID3->analyze($value->getRealPath());

        $passes = true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The duration should be at least '.$this->min.' seconds and at most '.$this->max.' seconds';
    }

    private function getSeconds($duration){
        $duration = explode(':', $duration);

        $hrs = $mins = $secs = 0;

        if(count($duration) == 3){
            $hrs = $duration[0];
            $mins = $duration[1];
            $secs = $duration[2];
        }else if(count($duration) == 2){
            $mins = $duration[0];
            $secs = $duration[1];
        }

        
    }
}
