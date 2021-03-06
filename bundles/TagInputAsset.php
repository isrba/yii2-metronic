<?php
/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace isrba\metronic\bundles;

/**
 * TagInputAsset for tag widget.
 */
class TagInputAsset extends BaseAssetBundle {

    public $js = [
        'global/plugins/jquery-tags-input/jquery.tagsinput.min.js',
    ];

    public $css = [
        'global/plugins/jquery-tags-input/jquery.tagsinput.css',
    ];

    public $depends = [
        'isrba\metronic\bundles\CoreAsset',
    ];

}