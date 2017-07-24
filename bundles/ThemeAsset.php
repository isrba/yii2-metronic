<?php
/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o. 
 * @license http://www.digitaldeals.cz/license/
 */

namespace isrba\metronic\bundles;

use Yii;
use isrba\metronic\Metronic;

class ThemeAsset extends BaseAssetBundle {

    /**
     * @var array depended bundles
     */
    public $depends = [
        'isrba\metronic\bundles\CoreAsset',
        'isrba\metronic\bundles\StyleBasedAsset',
    ];

    /**
     * @var array css assets
     */
    public $css = [
        'layouts/{version}/css/layout.css',
        'layouts/{version}/css/themes/{theme}.css',
    ];

    /**
     * @var array js assets
     */
    public $js = [
        'global/scripts/app.js',
        'layouts/{version}/scripts/layout.js',
    ];

    /**
     * @var array addons assets
     */
    public $addons = [];

    /**
     * Inits bundle
     */
    public function init()
    {
        $this->_handleAddons();

        $this->_handleDynamicCss();

        $this->_handleDynamicJs();

        return parent::init();
    }

    /**
     * Parses dynamic css
     */
    private function _handleDynamicCss()
    {
        $component = Metronic::getComponent();

        if ($component)
        {
            array_walk($this->css, [$component, 'parseAssetsParams']);
        }
    }

    /**
     * Parses dynamic js
     */
    private function _handleDynamicJs()
    {
        $component = Metronic::getComponent();

        if ($component)
        {
            array_walk($this->js, [$component, 'parseAssetsParams']);
        }
    }

    private function _handleAddons() {
        $controller = Yii::$app->controller->id .'/'. Yii::$app->controller->action->id;
        if (array_key_exists($controller, $this->addons)) {
            $additional = $this->addons[$controller];
            if (array_key_exists('js',$additional) && is_array($additional['js'])) {
                $this->js = array_merge($this->js, $additional['js']);
            }
            if (array_key_exists('css',$additional) && is_array($additional['css'])) {
                $this->css = array_merge($this->css, $additional['css']);
            }
        }
    }
}