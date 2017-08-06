<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace isrba\metronic\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

use isrba\metronic\bundles\SelectAsset;

/**
 * Select renders Bootstrap Select component.
 *
 * For example:
 * ```php
 * echo Select::widget([
 *     'name' => 'select',
 *     'data' => ['1' => 'Item 1', '2' => 'Item 2'],
 * ]);
 * ```
 *
 * @see http://silviomoreto.github.io/bootstrap-select/
 */
class Select extends InputWidget
{
    /**
     * @var array the option data items. The array keys are option values, and the array values
     * are the corresponding option labels. The array can also be nested (i.e. some array values are arrays too).
     * For each sub-array, an option group will be generated whose label is the key associated with the sub-array.
     * If you have a list of data models, you may convert them into the format described above using
     * [[\yii\helpers\ArrayHelper::map()]].
     */
    public $data = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, 'form-control');
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->data, $this->options);
        } else {
            echo Html::dropDownList($this->name, $this->value, $this->data, $this->options);
        }
        SelectAsset::register($this->view);
        $this->registerPlugin('selectpicker');
    }
}