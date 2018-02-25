<?php

/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace isrba\metronic\widgets;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;
use isrba\metronic\bundles\TimePickerAsset;

/**
 * TimePicker renders Bootstrap timepicker widget.
 *
 * For example:
 *
 * ```php
 * echo TimePicker::widget([
 *     'model' => $model,
 *     'clientOptions' => [
 *         'dateFormat' => 'yy-mm-dd',
 *     ],
 * ]);
 * ```
 *
 * The following example will use the name property instead:
 *
 * ```php
 * echo TimePicker::widget([
 *     'name'  => 'country',
 *     'clientOptions' => [
 *         'dateFormat' => 'yy-mm-dd',
 *     ],
 * ]);
 * ```
 *
 * @see http://jdewit.github.io/bootstrap-timepicker/
 * @author Ivan Srba <srba@nextra.sk>
 * @since 2.0
 */
class TimePicker extends InputWidget {

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, 'timepicker form-control');
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textInput($this->name, $this->value, $this->options);
        }
        TimePickerAsset::register($this->view);
        $this->registerPlugin('timepicker');
    }
}
