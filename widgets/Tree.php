<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace isrba\metronic\widgets;

use yii\helpers\Html;
use \yii\helpers\ArrayHelper;
use isrba\metronic\bundles\TreeAsset;

class Tree extends InputWidget {

    /**
     * @var array items to be traversed in tree
     */
    public $items = [];
	
	/**
     * @var string nestable level attr (depth)
     */
    public $levelAttr = 'level';

    /**
     * @var boolean indicates if is checkable
     */
    public $checkable = false;

    /**
     * @var string item list tag
     */
    public $listTag = 'ul';

    /**
     * @var string item tag
     */
    public $itemTag = 'li';
	
	
	/**
     * @var string content tag
     */
    public $contentTag = '';

    /**
     * @var string list class
     */
    public $listClass = 'tree';

    /**
     * @var string item class
     */
    public $itemClass = 'item';

    /**
     * @var string item cotent class
     */
    public $contentClass = '';

    /**
     * @var \Closure callback for generating content
     */
    public $contentCallback;
	
	/**
     * @var \Closure callback for generating item options
     */
    public $itemOptionsCallback;
	
	/**
     * @var array tree options
     */
    public $treeOptions = [];  
	
    /**
     * @var array default tree config
     */
    protected $defaultTreeOptions = [
        'plugins' => [
            'wholerow',
        ],
        'core' => [
			'expand_selected_onload' => false,
            'themes' => [
                'responsive' => true,
                'icons' => true
            ],
        ],
    ];
	
	/**
     * @var mixed array when assigned items are loaded, null otherwise
     */
    private $_assignedItems = null;

    /**
     * Inits widget
     */
    public function init()
    {
        $this->jsTree();
    }

    /**
     * Runs widget
     */
    public function run()
    {
        $builder = \isrba\metronic\builders\TreeBuilder::instance($this->items, array(
                'treeTag' => $this->listTag,
                'itemTag' => $this->itemTag,
                'contentTag' => $this->contentTag,
                'levelAttr' => $this->levelAttr,
                'contentCallback' => $this->contentCallback,
				'treeHtmlOptions' => function() {
                    return $this->getTreeOptions();
                },
                'itemHtmlOptions' => function($model, $level) {
                    return $this->getItemOptions($model, $level);
                },
                'contentHtmlOptions' => function($model, $level) {
                    return $this->getContentOptions($model, $level);
                },
        ));

        echo $this->renderTree($builder->build());
    }

    /**
     * Retrieves tree html options
     * @return array tree html options
     */
    protected function getTreeOptions()
    {
        return array(
            'class' => $this->listClass,
        );
    }

    /**
     * Retrieves item html options
     * @param array $id passed item id
     */
    protected function getItemOptions($model, $level)
    {
		if (is_callable($this->itemOptionsCallback)) {
            $options = (array) call_user_func($this->itemOptionsCallback, $model, $level);
        } else {
			$options = [];
		}
		
		$options = ArrayHelper::merge($options, array(
            'class' => $this->itemClass,
            'data-id' => $model->id,
            'data-jstree' => [
                'selected' => $this->isItemAssigned($model->id),
            ],
        ));
		
		$options['data-jstree'] = json_encode($options['data-jstree']);
		
        return $options;
    }

    /**
     * Retrieves content html options
     * @return type
     */
    protected function getContentOptions($model, $level)
    {
        return array(
            'class' => $this->contentClass,
        );
    }

    /**
     * Indicates if item is currently assigned to model
     * @param int $id given item id
     */
    protected function isItemAssigned($id)
    {
        if (null === $this->_assignedItems)
        {
            $this->_assignedItems = $this->pullAssignedItems();
        }

        return in_array($id, $this->_assignedItems);
    }

    /**
     * Pulls assigned items from model
     */
    protected function pullAssignedItems()
    {
        if (is_string($this->model{$this->attribute}))
        {
            return explode(',', $this->model{$this->attribute});
        }

        return (array) $this->model{$this->attribute};
    }

    /**
     * Renders output
     * @param string $tree 
     */
    protected function renderTree($tree)
    {
        $html = Html::beginTag('div', ['id' => $this->id, 'class' => 'dd']);

        $html .= $tree;

        $html .= Html::endTag('div');

        if ($this->checkable)
        {
            $html .= $this->renderHiddenField();
        }

        return $html;
    }

    /**
     * Renders hidden form field
     */
    protected function renderHiddenField()
    {
        if ($this->hasModel())
        {
            return Html::activeInput('hidden', $this->model, $this->attribute, ['id' => $this->getHiddenFieldId()]);
        }

        return Html::input('hidden', $this->name, $this->value, ['id' => $this->getHiddenFieldId()]);
    }

    /**
     * Registres JS files
     */
    protected function jsTree()
    {
        if ($this->checkable)
        {
            $this->defaultTreeOptions = ArrayHelper::merge($this->defaultTreeOptions, [
                    'core' => [
                        'dblclick_toggle' => false,
                    ],
                    'plugins' => [
                        'checkbox'
                    ],
                    'checkbox' => [
                        'keep_selected_style' => false,
                        'three_state' => true,
						'cascade_to_disabled' => false,
                    ],
            ]);
        }

        $treeOptions = json_encode(ArrayHelper::merge($this->defaultTreeOptions, $this->treeOptions));

        $view = $this->getView();
        $view->registerJs("jQuery('#{$this->id}').jstree({$treeOptions});");
        $this->registerAdditionalJs();
        TreeAsset::register($view);
    }

    protected function registerAdditionalJs()
    {
        $view = $this->getView();

        if ($this->checkable)
        {
            $view->registerJs("jQuery('#{$this->id}').on('changed.jstree', function (e, data) {
            var i, j, r = [];

            for (i = 0, j = data.selected.length; i < j; i++) {
                r.push(data.instance.get_node(data.selected[i]).data.id);
            }

            jQuery('#{$this->getHiddenFieldId()}').val(r.join(','));
        });");
        }

        $view->registerJs("jQuery('#{$this->id}').on('select_node.jstree', function (e, data) {
            var \$this = $(this);
            \$this.jstree('open_node', data.node);
        });");
    }

    /**
     * Retrieves hidden field id
     */
    private function getHiddenFieldId()
    {
        return strtolower(sprintf('%s-%s', $this->model->formName(), $this->attribute));
    }
}
?>
