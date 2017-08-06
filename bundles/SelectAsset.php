<?php
/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace  isrba\metronic\bundles;

/**
 * SelectAsset for Bootstrap select widget.
 */
class SelectAsset extends BaseAssetBundle
{

    public $js = [
        'global/plugins/bootstrap-select/js/bootstrap-select.min.js',
    ];

    public $css = [
        'global/plugins/bootstrap-select/css/bootstrap-select.min.css',
    ];


    public $depends = [
        'isrba\metronic\bundles\CoreAsset',
    ];
}
