<?php

/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace isrba\metronic\bundles;

/**
 * DateRangePickerAsset for dateRangePicker widget.
 */
class DateRangePickerAsset extends BaseAssetBundle {


    public $js = [
        'global/plugins/moment.min.js',
        'global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
    ];

    public $css = [
        'global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',
    ];

    public $depends = [
        'isrba\metronic\bundles\CoreAsset',
    ];

}
