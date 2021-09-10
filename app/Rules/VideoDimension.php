<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class VideoDimension implements Rule
{

    private $width, $height;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
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

        if ($this->width != $file['video']['resolution_x']
            || $this->height != $file['video']['resolution_y']){
            $passes = false;
        }

        return $passes;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Video dimensions should be '.$this->width.'x'.$this->height.'p';
    }
}
