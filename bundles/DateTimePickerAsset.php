<?php

/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace isrba\metronic\bundles;

use Yii;

class DateTimePickerAsset extends BaseAssetBundle {

    public $js = [
        'global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
    ];

    public $css = [
        'global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
    ];

    public $depends = [
        'isrba\metronic\bundles\CoreAsset',
    ];

    /**
     * Inits bundle
     */
    public function init()
    {
        $this->js[] = 'global/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.' . preg_replace('/\-.*$/', '', Yii::$app->language) . '.js';

        parent::init();
    }
}
