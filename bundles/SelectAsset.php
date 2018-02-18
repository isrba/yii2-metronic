<?php
/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace  isrba\metronic\bundles;

use Yii;

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


    /**
     * Inits bundle
     */
    public function init()
    {
        if (Yii::$app->language == 'en-GB') {
            $language = 'en-US';
        } else {
            $language = Yii::$app->language;
        }

        $this->js[] = 'global/plugins/bootstrap-select/js/i18n/defaults-' . preg_replace('/\-/', '_', $language) . '.js';

        parent::init();
    }
}
