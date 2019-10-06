<?php

/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace isrba\metronic\bundles;

use Yii;

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
	
	/**
     * Inits bundle
     */
    public function init()
    {
        $this->js[] = 'global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.' . preg_replace('/\-.*$/', '', Yii::$app->language) . '.min.js';

        parent::init();
    }
}
