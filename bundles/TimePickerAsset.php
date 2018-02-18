<?php

/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace isrba\metronic\bundles;

class TimePickerAsset extends BaseAssetBundle {

    public $js = [
        'global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
    ];

    public $css = [
        'global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
    ];

    public $depends = [
        'isrba\metronic\bundles\CoreAsset',
    ];
}
