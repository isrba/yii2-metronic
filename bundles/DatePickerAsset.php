<?php

/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace isrba\metronic\bundles;

class DatePickerAsset extends BaseAssetBundle {

    public $js = [
        'global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    ];

    public $css = [
        'global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
    ];

    public $depends = [
        'isrba\metronic\bundles\CoreAsset',
    ];
}
