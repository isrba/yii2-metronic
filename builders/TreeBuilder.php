<?php
/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace isrba\metronic\builders;

use yii\helpers\Html;

class TreeBuilder {

    /**
     * @var string items level attribute
     */
    public $levelAttr;

    /**
     * @var string item tree tag
     */
    public $treeTag;

    /**
     * @var string item tag
     */
    public $itemTag;

    /**
     * @var string item tag
     */
    public $contentTag;

    /**
     * @var Closure function generates tree html options
     */
    public $treeHtmlOptions;

    /**
     * @var Closure function generates item html options
     */
    public $itemHtmlOptions;

    /**
     * @var Closure function generates content html options
     */
    public $contentHtmlOptions;

    /**
     * @var \Closure callback for generating contentF
     */
    public $contentCallback;
	
    /**
     * @var array given items to traverse
     */
    private $_items = array();

    /**
     * Private constructor
     * @param array $items
     */
    private function __construct($items, $params = array())
    {
        $this->_items = $items;

        $this->setParams($params);
    }

    /**
     * Retrieves new instance of MTreeBuilder
     * @param array $items given items to traverse
     */
    public static function instance($items, $params = array())
    {
        return new self($items, $params);
    }

    /**
     * Build tree from given items
     */
    public function build()
    {
        return $this->renderTree();
    }

    /**
     * Sets class params
     * @param array $params given params
     * @throws Exception if param does not exists
     */
    public function setParams($params)
    {
        foreach ($params as $param => $value)
        {
            if (!property_exists($this, $param))
            {
                throw new Exception(Yii::t('app', 'Class {class} has no param called "{param}."', array(
                    '{class}' => get_class($this),
                    '{param}' => $param
                )));
            }

            $this->{$param} = $value;
        }
    }

    /**
     * Renders tree
     * @return string html
     */
    protected function renderTree()
    {
        $html = '';

        $level = -1;

        foreach ($this->_items as $model)
        {
            if ($model->{$this->levelAttr} == $level)
            {
                $html .= $this->renderItemClose();
            }
            else if ($model->{$this->levelAttr} > $level)
            {
                $html .= $this->renderTreeOpen();
            }
            else
            {
                $html .= $this->renderItemClose();

                for ($i = $level - $model->{$this->levelAttr}; $i; $i--)
                {
                    $html .= $this->renderTreeClose();

                    $html .= $this->renderItemClose();
                }
            }

            $html .= $this->renderItemOpen($model, $level);

            $html .= $this->renderContent($model, $level);
			
            $level = $model->{$this->levelAttr};
        }

        for ($i = $level; $i; $i--)
        {
            $html .= $this->renderItemClose();
            $html .= $this->renderTreeClose();
        }

        return $html;
    }

    /**
     * Renders tree open tag
     * @return string html
     */
    protected function renderTreeOpen()
    {
        if (is_callable($this->treeHtmlOptions)) {
            $options = (array) call_user_func($this->treeHtmlOptions);
        } else {
			$options = [];
		}

        return Html::beginTag($this->treeTag, $options);
    }

    /**
     * Renders item open tag
     * @return string html
     */
    protected function renderItemOpen($model, $level)
    {
        if (is_callable($this->itemHtmlOptions)) {
            $options = (array) call_user_func($this->itemHtmlOptions, $model, $level);
        } else {
			$options = [];
		}

        return Html::beginTag($this->itemTag, $options);
    }

    /**
     * Renders item content
     */
    protected function renderContent($model, $level)
    {
        if (is_callable($this->contentCallback)) {
            $content = call_user_func($this->contentCallback, $model, $level);
        } else {
			$content = '';
		}
		
		if ($this->contentTag) {
            if (is_callable($this->contentHtmlOptions)) {
				$options = (array) call_user_func($this->contentHtmlOptions, $model, $level);
			} else {
				$options = [];
			}
			
            return Html::tag($this->contentTag, $options, $content);
        }

        return $content;
    }

    /**
     * Renders item close tag
     * @return string html
     */
    protected function renderItemClose()
    {
        return Html::endTag($this->itemTag);
    }

    /**
     * Renders tree close tag
     * @return string html
     */
    protected function renderTreeClose()
    {
        return Html::endTag($this->treeTag);
    }
}