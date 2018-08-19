<?php
/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace  isrba\metronic\bundles;

use Yii;

/**
 * TypeaheadAsset for Typeahead widget.
 */
class TypeaheadAsset extends BaseAssetBundle
{

    public $js = [
        'global/plugins/typeahead/handlebars.min.js',
        'global/plugins/typeahead/typeahead.bundle.min.js',
    ];

    public $css = [
        'global/plugins/typeahead/typeahead.css',
    ];


    public $depends = [
        'isrba\metronic\bundles\CoreAsset',
    ];
}
