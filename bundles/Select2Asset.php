<?php
/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace  isrba\metronic\bundles;

use Yii;

/**
 * Select2Asset for select2 widget.
 */
class Select2Asset extends BaseAssetBundle
{

    public $js = [
        'global/plugins/select2/js/select2.js',
    ];

    public $css = [
        'global/plugins/select2/css/select2.css',
        'global/plugins/select2/css/select2-bootstrap.min.css',
    ];


    public $depends = [
        'isrba\metronic\bundles\CoreAsset',
    ];


    /**
     * Inits bundle
     */
    public function init()
    {
        $this->js[] = 'global/plugins/select2/js/i18n/' . preg_replace('/\-.*$/', '', Yii::$app->language) . '.js';

        parent::init();
    }
}
