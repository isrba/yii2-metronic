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
use isrba\metronic\bundles\DateTimePickerAsset;

/**
 * DateTimePicker renders a bootstrap datetime picker component.
 *
 * For example:
 *
 * ```php
 * echo DateTimePicker::widget([
 *     'language' => 'ru',
 *     'model' => $model,
 *     'attribute' => 'country',
 *     'clientOptions' => [
 *         'dateFormat' => 'yy-mm-dd',
 *     ],
 * ]);
 * ```
 *
 * The following example will use the name property instead:
 *
 * ```php
 * echo DateTimePicker::widget([
 *     'language' => 'ru',
 *     'name'  => 'country',
 *     'clientOptions' => [
 *         'dateFormat' => 'yy-mm-dd',
 *     ],
 * ]);
 * ```
 *
 * @see https://www.malot.fr/bootstrap-datetimepicker/index.php
 * @since 2.0
 */
class DateTimePicker extends InputWidget {

    /**
     * @var string the locale ID (eg 'fr', 'de') for the language to be used by the date picker.
     * If this property set to false, I18N will not be involved. That is, the date picker will show in English.
     */
    public $language = false;

    /**
     * @var boolean If true, shows the widget as an inline calendar and the input as a hidden field.
     */
    public $inline = false;

    /**
     * @var array the HTML attributes for the container tag. This is only used when [[inline]] is true.
     */
    public $containerOptions = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        if ($this->inline && !isset($this->containerOptions['id']))
        {
            $this->containerOptions['id'] = $this->options['id'] . '-container';
        }
        else
        {
            Html::addCssClass($this->options, 'form-control form-control-inline');
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $contents = [];
        if ($this->inline)
        {
            if ($this->hasModel())
            {
                $contents[] = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
            }
            else
            {
                $contents[] = Html::hiddenInput($this->name, $this->value, $this->options);
            }
            $contents[] = Html::tag('div', '', $this->containerOptions);
        }
        else
        {
            if ($this->hasModel())
            {
                $contents[] = Html::activeTextInput($this->model, $this->attribute, $this->options);
            }
            else
            {
                $contents[] = Html::textInput($this->name, $this->value, $this->options);
            }
        }
        echo implode("\n", $contents);
        if ($this->language)
        {
            $this->clientOptions['language'] = $this->language;
        }
        DateTimePickerAsset::register($this->view);
        $this->registerPlugin('datetimepicker');
    }

    /**
     * Registers a specific Bootstrap plugin and the related events
     * @param string $name the name of the Bootstrap plugin
     */
    protected function registerPlugin($name)
    {
        $view = $this->getView();
        $id = $this->inline ? $this->containerOptions['id'] : $this->options['id'];
        if ($this->clientOptions !== false)
        {
            $options = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
            $js = "jQuery('#$id').$name($options);";
            $view->registerJs($js);
        }
        if (!empty($this->clientEvents))
        {
            $js = [];
            foreach ($this->clientEvents as $event => $handler)
            {
                $js[] = "jQuery('#$id').on('$event', $handler);";
            }
            $view->registerJs(implode("\n", $js));
        }
    }

}
