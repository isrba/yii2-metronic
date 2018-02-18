<?php
/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace isrba\metronic\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CheckboxColumn extends \kartik\grid\CheckboxColumn {

    /**
     * @inheritdoc
     */
    protected function renderHeaderCellContent()
    {
        if ($this->header !== null || !$this->multiple) {
            return parent::renderHeaderCellContent();
        } else {

            $content = Html::checkbox($this->getHeaderCheckBoxName(), false, ['class' => 'select-on-check-all']) .'<span></span>';
            $content = Html::tag('label', $content, ['class' => 'mt-checkbox mt-checkbox-single mt-checkbox-outline']);

            return $content;
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if (is_callable($this->checkboxOptions)) {
            $options = call_user_func($this->checkboxOptions, $model, $key, $index, $this);
        } else {
            $options = $this->checkboxOptions;
        }

        if (!isset($options['value'])) {
            $options['value'] = is_array($key) ? Json::encode($key) : $key;
        }

        if ($this->cssClass !== null) {
            Html::addCssClass($options, $this->cssClass);
        }

        $content = Html::checkbox($this->name, !empty($options['checked']), $options) .'<span></span>';
        $content = Html::tag('label', $content, ['class' => 'mt-checkbox mt-checkbox-single mt-checkbox-outline']);

        return $content;
    }
}