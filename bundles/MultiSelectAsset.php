<?php
/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace  isrba\metronic\bundles;

/**
 * MultiSelectAsset for multi select widget.
 */
class MultiSelectAsset extends BaseAssetBundle
{

    public $js = [
        'global/plugins/jquery-multi-select/js/jquery.multi-select.js',
    ];

    public $css = [
        'global/plugins/jquery-multi-select/css/multi-select.css',
    ];

    public $depends = [
        'isrba\metronic\bundles\CoreAsset',
    ];
}
