<?php
/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace isrba\metronic\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ActionColumn extends \yii\grid\ActionColumn {

    /**
     * @var array the HTML options for the data cell tags.
     */
    public $headerOptions = ['class' => 'text-center text-nowrap'];

    /**
     * @var array the HTML options for the data cell tags.
     */
    public $contentOptions = ['class' => 'text-center text-nowrap'];

    /**
     * @var string the template that is used to render the content in each data cell.
     */
    public $template = '{view}{update}';

    /**
     * @var string the icon for the view button.
     */
    public $viewButtonIcon = 'fa fa-search';

    /**
     * @var string the icon for the update button.
     */
    public $updateButtonIcon = 'fa fa-edit';

    /**
     * @var string the icon for the delete button.
     */
    public $deleteButtonIcon = 'fa fa-trash';

    /**
     * @var string btn view class
     */
    public $btnViewClass = 'btn btn-outline btn-xs blue-madison';

    /**
     * @var string btn update class
     */
    public $btnUpdateClass = 'btn btn-outline btn-xs blue-madison';

    /**
     * @var string btn delete class
     */
    public $btnDeleteClass = 'action-delete';

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view']))
        {
            $this->buttons['view'] = function ($url, $model, $key) {
                return Html::a('<i class="'.$this->viewButtonIcon.'"></i> View', $url, [
                        'title' => \Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                        'class' => $this->btnViewClass,
                ]);
            };
        }
        if (!isset($this->buttons['update']))
        {
            $this->buttons['update'] = function ($url, $model, $key) {
                return Html::a('<i class="'.$this->updateButtonIcon.'"></i> Edit', $url, [
                        'title' => \Yii::t('yii', 'Update'),
                        'data-pjax' => '0',
                        'class' => $this->btnUpdateClass,
                ]);
            };
        }
        if (!isset($this->buttons['delete']))
        {
            $this->buttons['delete'] = function ($url, $model, $key) {
                return Html::a('<span class="'.$this->deleteButtonIcon.'"></span>', $url, [
                        'title' => \Yii::t('yii', 'Delete'),
                        'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                        'class' => $this->btnDeleteClass,
                ]);
            };
        }
    }
}