<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace isrba\metronic\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use isrba\metronic\bundles\GridViewSortableAsset;
use yii\widgets\Pjax;

class GridView extends \kartik\grid\GridView {

    /**
     * @var string grid view layout
     */
    //public $layout = "{items}\n{summary}\n{pager}";
    public $layout = "{items}\n<div class=\"row\"><div class=\"col-md-5 col-sm-12\">{summary}</div>\n<div class=\"col-md-7 col-sm-12 text-right\">{pager}</div></div>";

    /**
     * @var boolean indicates if grid is sortable
     */
    public $sortable = false;

    /**
     * Inits widget
     */
    public function init()
    {
        parent::init();

        $this->initPager();

        $this->initVisible();

        $this->initSortable();

        //GridViewAsset::register($this->view);
    }

    /**
     * Renders the data models for the grid view.
     */
    public function renderItems()
    {
        /*
          $content = array_filter([
          $this->renderCaption(),
          $this->renderColumnGroup(),
          $this->showHeader ? $this->renderTableHeader() : false,
          $this->showFooter ? $this->renderTableFooter() : false,
          $this->renderTableBody(),
          ]);

          $table = Html::tag('table', implode("\n", $content), $this->tableOptions);
          if ($this->responsive)
          {
          $table = Html::tag('div', $table, ['class' => 'table-responsive']);
          }
          else
          {
          $table = Html::tag('div', $table, ['class' => 'table-scrollable']);
          }

          return $table;
         *
         */
        return parent::renderItems();
    }

    /**
     * Inits pager
     */
    protected function initPager()
    {
        $this->pager['firstPageLabel'] = Html::tag('i', '', [
                'class' => 'fa fa-angle-double-left',
        ]);

        $this->pager['lastPageLabel'] = Html::tag('i', '', [
                'class' => 'fa fa-angle-double-right',
        ]);

        $this->pager['prevPageLabel'] = Html::tag('i', '', [
                'class' => 'fa fa-angle-left',
        ]);

        $this->pager['nextPageLabel'] = Html::tag('i', '', [
                'class' => 'fa fa-angle-right',
        ]);
    }

    protected function initVisible()
    {
        $columns = $this->getStorageColumns();
        if (empty($columns))
        {
            return;
        }
        foreach ($this->columns as $i => $column)
        {
            if (array_search($i, $columns) === false)
            {
                unset($this->columns[$i]);
            }
        }
    }

    /**
     * Inits sortable behavior on gridview
     */
    protected function initSortable()
    {
        $route = ArrayHelper::getValue($this->sortable, 'url', false);

        if ($route)
        {
            $url = Url::toRoute($route);

            $options = json_encode(ArrayHelper::getValue($this->sortable, 'options', []));

            $view = $this->getView();
            $view->registerJs("jQuery('#{$this->id}').SortableGridView('{$url}', {$options});");
            GridViewSortableAsset::register($view);
        }
    }

    protected function getStorageColumns()
    {
        return [];
    }

    /**
     * Begins the markup for the [[Pjax]] container.
     */
    protected function beginPjax()
    {
        if (!$this->pjax) {
            return;
        }
        $view = $this->getView();
        if (empty($this->pjaxSettings['options']['id'])) {
            $this->pjaxSettings['options']['id'] = $this->options['id'] . '-pjax';
        }
        $container = 'jQuery("#' . $this->pjaxSettings['options']['id'] . '")';
        $js = $container;
        if (ArrayHelper::getValue($this->pjaxSettings, 'neverTimeout', true)) {
            $js .= ".on('pjax:timeout', function(e){e.preventDefault()})";
        }
        $loadingCss = ArrayHelper::getValue($this->pjaxSettings, 'loadingCssClass', 'kv-grid-loading');
        $postPjaxJs = "setTimeout({$this->_gridClientFunc}, 2500);";
        $grid = 'jQuery("#' . $this->containerOptions['id'] . '")';
        if ($loadingCss !== false) {
            if ($loadingCss === true) {
                $loadingCss = 'kv-grid-loading';
            }
            $js .= ".on('pjax:send', function(){{$grid}.addClass('{$loadingCss}')})";
            $postPjaxJs .= "{$grid}.removeClass('{$loadingCss}');";
        }

        $js .= ".on('pjax:send', function(){App.blockUI({
                    target: {$grid},
                    animate: true,
                })})";
        $postPjaxJs .= "App.unblockUI($grid);\n";

        $postPjaxJs .= "\n" . $this->_toggleScript;
        if (!empty($postPjaxJs)) {
            $event = 'pjax:complete.' . hash('crc32', $postPjaxJs);
            $js .= ".off('{$event}').on('{$event}', function(){{$postPjaxJs}})";
        }
        if ($js != $container) {
            $view->registerJs("{$js};");
        }
        Pjax::begin($this->pjaxSettings['options']);
        echo ArrayHelper::getValue($this->pjaxSettings, 'beforeGrid', '');
    }
}