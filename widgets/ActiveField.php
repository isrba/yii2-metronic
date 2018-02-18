<?php

/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */


namespace isrba\metronic\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ActiveField extends \yii\bootstrap\ActiveField {

    /**
     * @var ActiveForm the form that this field is associated with.
     */
    public $form;

    // icon position
    const ICON_POSITION_LEFT = 'left';
    const ICON_POSITION_RIGHT = 'right';

    /**
     * Renders the closing tag of the field container.
     * @return string the rendering result.
     */
    public function end()
    {
        return Html::endTag(isset($this->options['tag']) ? $this->options['tag'] : 'div') . "\n";
    }

    /**
     * Generates a icon for input.
     * @param array $options icon options.
     * The options have following structure:
     * ```php
     * [
     *     'icon' => 'fa fa-bookmark-o',
     *     'position' => ActiveField::ICON_POSITION_LEFT,
     *     'class' => 'input-icon-sm'
     * ]
     * ```
     * @return static the field object itself
     */
    public function icon($options = [])
    {
        $icon = ArrayHelper::remove($options, 'icon', null);
        if ($icon)
        {
            $position = ArrayHelper::remove($options, 'position', self::ICON_POSITION_LEFT);
            if ($position != self::ICON_POSITION_RIGHT)
            {
                Html::addCssClass($options, $position);
            }

            Html::addCssClass($options, 'input-icon');

            $this->parts['{input}'] = Html::tag('i', '', ['class' => $icon]) . "\n" . $this->parts['{input}'];
            $this->parts['{input}'] = Html::tag('div', $this->parts['{input}'], ['class' => $options['class']]);
        }

        return $this;
    }

    /**
     * Generates a groupAddon for input.
     * GroupAddon similar to [[icon()]].
     * @param array $options icon options.
     * The options have following structure:
     * ```php
     * [
     *     'icon' => 'fa fa-bookmark-o',
     *     'position' => ActiveField::ICON_POSITION_LEFT,
     *      'class' => 'input-group-sm'
     * ]
     * ```
     * @return static the field object itself
     */
    public function groupAddon($options = [])
    {
        $icon = ArrayHelper::remove($options, 'icon', null);
        if ($icon)
        {
            Html::addCssClass($options, 'input-group');

            $addon = Html::tag('span', Html::tag('i', '', ['class' => $icon]), ['class' => 'input-group-addon']);
            $position = ArrayHelper::remove($options, 'position', self::ICON_POSITION_LEFT);
            if ($position == self::ICON_POSITION_RIGHT)
            {
                $this->parts['{input}'] .= "\n" . $addon;
            }
            else
            {
                $this->parts['{input}'] = $addon . "\n" . $this->parts['{input}'];
            }
            $this->parts['{input}'] = Html::tag('div', $this->parts['{input}'], ['class' => $options['class']]);
        }

        $text = ArrayHelper::remove($options, 'text', null);
        if ($text)
        {
            Html::addCssClass($options, 'input-group');

            $addon = Html::tag('span', $text, ['class' => 'input-group-addon']);
            $position = ArrayHelper::remove($options, 'position', self::ICON_POSITION_LEFT);
            if ($position == self::ICON_POSITION_RIGHT)
            {
                $this->parts['{input}'] .= "\n" . $addon;
            }
            else
            {
                $this->parts['{input}'] = $addon . "\n" . $this->parts['{input}'];
            }
            $this->parts['{input}'] = Html::tag('div', $this->parts['{input}'], ['class' => $options['class']]);
        }

        $button = ArrayHelper::remove($options, 'button', null);
        if ($button)
        {
            Html::addCssClass($options, 'input-group');

            $addon = Html::tag('span', $button, ['class' => 'input-group-btn']);
            $position = ArrayHelper::remove($options, 'position', self::ICON_POSITION_LEFT);
            if ($position == self::ICON_POSITION_RIGHT)
            {
                $this->parts['{input}'] .= "\n" . $addon;
            }
            else
            {
                $this->parts['{input}'] = $addon . "\n" . $this->parts['{input}'];
            }
            $this->parts['{input}'] = Html::tag('div', $this->parts['{input}'], ['class' => $options['class']]);
        }

        return $this;
    }

    /**
     * Generates a tag that contains error.
     * @param $error string the error to use.
     * @param array $options the tag options in terms of name-value pairs. It will be merged with [[errorOptions]].
     * @return static the field object itself
     */
    public function staticError($error, $options = [])
    {
        $options = array_merge($this->errorOptions, $options);
        $tag = isset($options['tag']) ? $options['tag'] : 'div';
        unset($options['tag']);
        $this->parts['{error}'] = Html::tag($tag, $error, $options);

        return $this;
    }

    /**
     * Generates spinner component.
     * @param array $options spinner options
     * @return $this
     */
    public function spinner($options = [])
    {
        $this->parts['{input}'] = Spinner::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    /**
     * Generates clearing checkbox component.
     * @param array $options clearing checkbox options
     * @return $this
     */
    public function clearingCheckbox($options = [])
    {
        $clearingCheckboxOptions = [
            'value' => 'clear-value',
            'label' => '',
            'labelOptions' => ['class' => 'mt-checkbox mt-checkbox-outline'],
        ];

        $options = array_merge($clearingCheckboxOptions, $options);
        $options['label'] .= '<span></span>';

        if (!$options['visible']) {
            return $this;
        }

        $name = $this->model->formName() . '[' . $this->attribute . ']';

        Html::addCssClass($options, 'clearing-checkbox');

        $checkbox = Html::checkbox($name, false, $options);
        $checkbox = Html::tag('div', $checkbox, ['class' => $options['class']]);

        $this->parts['{input}'] .= "\n" . $checkbox;

        return $this;
    }

    /**
     * Generates checkboxlist component with span tag required by Metronic .
     * @param array $items checkboxlist items
     * @param array $options checkboxlist options
     * @return $this
     */
    public function checkboxlist($items, $options = [])
    {
        $options = array_merge_recursive($options, [
            'class' => 'mt-checkbox-list',
            'item' => function ($index, $label, $name, $checked, $value) {
                return Html::checkbox($name, $checked, [
                    'labelOptions' => [
                        'class' => 'mt-checkbox mt-checkbox-outline'
                    ],
                    'value' => $value,
                    'label' => $label . '<span></span>',
                ]);
            },
        ]);

        if ($this->inline) {
            Html::addCssClass($options, 'mt-checkbox-inline');
        }

        parent::checkboxlist($items, $options);

        $this->parts['{input}'] = preg_replace('/<\/label>/', '<span></span></label>',  $this->parts['{input}']);
        return $this;
    }

    /**
     * Generates radioList component with span tag required by Metronic .
     * @param array $items radioList items
     * @param array $options radioList options
     * @return $this
     */
    public function radioList($items, $options = []) {
        $options = array_merge_recursive($options, [
            'class' => 'mt-radio-list',
            'item' => function ($index, $label, $name, $checked, $value) {
                return Html::radio($name, $checked, [
                    'labelOptions' => [
                        'class' => 'mt-radio mt-radio-outline'
                    ],
                    'value' => $value,
                    'label' => $label . '<span></span>',
                ]);
            },
        ]);

        if ($this->inline) {
            Html::addCssClass($options, 'mt-radio-inline');
        }

        parent::radioList($items, $options);

        return $this;
    }

    /**
     * Generates dateRangePicker component [[DateRangePicker]].
     * @param array $options dateRangePicker options
     * @return $this
     */
    public function dateRangePicker($options = [])
    {
        if ($this->form->layout == 'vertical')
        {
            //$options = array_merge($options, ['options' => ['style' => 'display:table-cell;']]);
            $options = array_merge($options, ['options' => ['class' => 'show']]);
        }
        $this->parts['{input}'] = DateRangePicker::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    /**
     * Generates datePicker component [[DatePicker]].
     * @param array $options datePicker options
     * @return $this
     */
    public function datePicker($options = [])
    {
        /* if ($this->form->type == ActiveForm::TYPE_VERTICAL) {
          //$options = array_merge($options, ['options' => ['style' => 'display:table-cell;']]);
          $options = array_merge($options, ['options' => ['class' => 'show']]);
          } */
        $this->parts['{input}'] = DatePicker::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    /**
     * Generates timePicker component [[TimePicker]].
     * @param array $options timePicker options
     * @return $this
     */
    public function timePicker($options = [])
    {
        $this->parts['{input}'] = TimePicker::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    /**
     * Generates select component [[Select]].
     * @param array $options select options
     * @return $this
     */
    public function select($options = [])
    {
        $this->parts['{input}'] = Select::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    /**
     * Generates select2 component [[Select2]].
     * @param array $options select2 options
     * @return $this
     */
    public function select2($options = [])
    {
        if (!isset($options['multiple']) || !$options['multiple']) {
            $options['data'] = ['' => ''] + $options['data'];
        }

        $this->parts['{input}'] = Select2::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    /**
     * Generates multiSelect component [[MultiSelect]].
     * @param array $options multiSelect options
     * @return $this
     */
    public function multiSelect($options = [])
    {
        $this->parts['{input}'] = MultiSelect::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    public function range($options = [])
    {
        $this->parts['{input}'] = IonRangeSlider::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    public function tree($options = [])
    {
        $this->parts['{input}'] = Tree::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }
}
