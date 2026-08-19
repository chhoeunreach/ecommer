<?php

namespace App\Utility;

use App\Models\Addon;
use App\Models\Color;

class ProductUtility
{
    public static function get_attribute_options($collection)
    {
        $options = array();
        if (
            isset($collection['colors_active']) &&
            $collection['colors_active'] &&
            $collection['colors'] &&
            count($collection['colors']) > 0
        ) {
            $colors_active = 1;
            array_push($options, $collection['colors']);
        }

        if (isset($collection['choice_no']) && $collection['choice_no']) {
            foreach ($collection['choice_no'] as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = request()->input($name, []);
                // An attribute row with no values picked yet must be skipped rather than
                // pushed as an empty dimension — the cartesian product in
                // CombinationService::generate_combination() treats any empty dimension as
                // "no combinations at all", which would wipe out every existing color/variant
                // combo as soon as an attribute is added but before its values are chosen.
                if (count($data) > 0) {
                    array_push($options, $data);
                }
            }
        }

        return $options;
    }

    public static function get_combination_string($combination, $collection)
    {
        $str = '';
        foreach ($combination as $key => $item) {
            if ($key > 0) {
                $str .= '-' . str_replace(' ', '', $item);
            } else {
                if (isset($collection['colors_active']) && $collection['colors_active'] && $collection['colors'] && count($collection['colors']) > 0) {
                    $color_name = Color::where('code', $item)->first()->name;
                    $str .= $color_name;
                } else {
                    $str .= str_replace(' ', '', $item);
                }
            }
        }
        return $str;
    }
}
