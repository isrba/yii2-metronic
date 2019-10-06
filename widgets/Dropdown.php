<?php

/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace isrba\metronic\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Dropdown renders a Metronic dropdown menu component.
 *
 * For example:
 *
 * Dropdown::widget([
 *    'title' => 'Dropdown title',
 *    'more' => ['label' => 'xxx', 'url' => '/', 'icon' => 'm-icon-swapright'],
 *    'scroller' => ['height' => 200],
 *    'items' => [
 *        ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
 *        '<li class="divider"></li>',
 *        '<li class="dropdown-header">Dropdown Header</li>',
 *        ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
 *     ],
 * ]);
 *
 */
class Dropdown extends \yii\bootstrap\Dropdown {

    /**
     * @var string the dropdown title
     */
    public $title;

    /**
     * @var array the dropdown last item options
     * with the following structure:
     * ```php
     * [
     *     // optional, item label
     *     'label' => 'Show all messages',
     *     // optional, item icon
     *     'icon' => 'm-icon-swapright',
     *     // optional, item url
     *     'url' => '/',
     * ]
     * ```
     */
    public $more = [];

    /**
     * @var array the dropdown item options
     * is an array of the following structure:
     * ```php
     * [
     *   // required, height of the body portlet as a px
     *   'height' => 150,
     *   // optional, HTML attributes of the scroller
     *   'options' => [],
     *   // optional, footer of the scroller. May contain string or array(the options of Link component)
     *   'footer' => [
     *     'label' => 'Show all',
     *   ],
     * ]
     * ```
     */
    public $scroller = [];

    /**
     * @var bool if we should use pull-right menu
     */
    public $pullRight = false;

    /**
     * @var bool if this is a drop-up
     */
    public $dropUp = false;

    public function init()
    {
        if ($this->dropUp) Html::addCssClass($this->options, 'bottom-up' );
        if ($this->pullRight) Html::addCssClass($this->options, 'pull-right' );

        parent::init();
    }

    /**
     * Executes the widget.
     */
    public function run()
    {

        echo $this->renderItems($this->items);
    }

    /**
     * Renders menu items.
     * @param array $items the menu items to be rendered
     * @return string the rendering result.
     * @throws InvalidConfigException if the label option is not specified in one of the items.
     */
    protected function renderItems($items, $options = [])
    {
        $lines = [];
        if ($this->title)
        {
            $title = Html::tag('h3', $this->title);

            if (!empty($this->more))
            {
                $icon = ArrayHelper::getValue($this->more, 'icon', '');
                $label = ArrayHelper::getValue($this->more, 'label', '');
                $url = ArrayHelper::getValue($this->more, 'url', '#');
                if ($icon)
                {
                    $icon = Html::tag('i', '', ['class' => $icon]);
                }
                $more = Html::tag('a',  $icon . ' ' . $label, ['href' => $url]);
            } else {
                $more = '';
            }

            $lines[] = Html::tag('li', $title . ' ' . $more, ['class' => 'external']);
        }

        if (!empty($this->scroller))
        {
            if (!isset($this->scroller['height']))
            {
                throw new InvalidConfigException("The 'height' option of Scroller is required.");
            }
            $lines[] = Html::beginTag('li');
            $lines[] = Html::beginTag('ul', [
                'style' => 'height: ' . $this->scroller['height'] . 'px;',
                'class' => 'dropdown-menu-list scroller',
                ]
            );
        }

        foreach ($items as $i => $item)
        {
            if (isset($item['visible']) && !$item['visible'])
            {
                unset($items[$i]);
                continue;
            }
            if (is_string($item))
            {
                $lines[] = $item;
                continue;
            }

            if (in_array('divider', $item, true))
            {
                $lines[] = Html::tag('li', '', ['class' => 'divider']);
                continue;
            }

            if (!isset($item['label']))
            {
                throw new InvalidConfigException("The 'label' option is required.");
            }
            $label = $this->encodeLabels ? Html::encode($item['label']) : $item['label'];

            $icon = ArrayHelper::getValue($item, 'icon', null);
            if ($icon)
            {
                $label = Html::tag('i', '', ['alt' => $label, 'class' => $icon]) . ' ' . $label;
            }
            $label .= ArrayHelper::getValue($item, 'badge', '');
            $options = ArrayHelper::getValue($item, 'options', []);
            $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
            $linkOptions['tabindex'] = '-1';
            $content = Html::a($label, ArrayHelper::getValue($item, 'url', '#'), $linkOptions);
            $lines[] = Html::tag('li', $content, $options);
        }

        if (!empty($this->scroller))
        {
            $lines[] = Html::endTag('ul');
            $lines[] = Html::endTag('li');
        }

        return Html::tag('ul', implode("\n", $lines), $this->options);
    }

}
