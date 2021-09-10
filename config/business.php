<?php

return [
    /**
     * Minimum duration in seconds for a slot
     */
    'min_duration' => 15,

    /**
     * Minimum number of days to book a slot in advance, to allow for moderation of ad
     */
    'min_advance_booking_days' => 3,

    /**
     * Number of seconds to use to calculate slot price
     */
    'pricing_slab' => 15,

    /**
     * Price per pricing slab
     */
    'slab_price' => 1,

    /**
     * Min airing hour
     */
    'min_hour' => 6,

    /**
     * Max airing hour
     */
    'max_hour' => 18,
];
