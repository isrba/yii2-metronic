<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace isrba\metronic\widgets;

use yii\helpers\Html;
use yii\web\JsExpression;
use isrba\metronic\bundles\MultiSelectAsset;

/**
 * MultiSelect renders MultiSelect component.
 *
 * For example:
 * ```php
 * echo MultiSelect::widget([
 *     'name' => 'select1',
 *     'data' => ['1' => 'Item 1', '2' => 'Item 2'],
 * ]);
 * ```
 *
 * @see http://loudev.com/
 */
class MultiSelect extends InputWidget
{
    /**
     * @var bool indicates whether the multiSelect is disabled or not.
     */
    public $disabled;
    /**
     * @var array the option data items. The array keys are option values, and the array values
     * are the corresponding option labels. The array can also be nested (i.e. some array values are arrays too).
     * For each sub-array, an option group will be generated whose label is the key associated with the sub-array.
     * If you have a list of data models, you may convert them into the format described above using
     * [[\yii\helpers\ArrayHelper::map()]].
     */
    public $data = [];
	
	public $searchable = false;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->options['multiple'] = true;
        if ($this->disabled) {
            $this->options['disabled'] = true;
        }
		
		if ($this->searchable) {
			$this->clientOptions['selectableHeader'] = "<input type='text' class='form-control ms-search-input' autocomplete='off' placeholder=''>";
			$this->clientOptions['selectionHeader'] = "<input type='text' class='form-control ms-search-input' autocomplete='off' placeholder=''>";
			$this->clientOptions['afterInit'] = new JsExpression('function(ms){
				var that = this,
					$selectableSearch = that.$selectableUl.prev(),
					$selectionSearch = that.$selectionUl.prev(),
					selectableSearchString = \'#\'+that.$container.attr(\'id\')+\' .ms-elem-selectable:not(.ms-selected)\',
					selectionSearchString = \'#\'+that.$container.attr(\'id\')+\' .ms-elem-selection.ms-selected\';

				that.qs1 = $selectableSearch.quicksearch(selectableSearchString, {
					\'show\': function () {
						$(this).removeClass(\'hidden\');
						$(this).parent().show();
					},
					\'hide\': function () {
						$(this).addClass(\'hidden\');
						
						if ($(this).siblings(\'.ms-elem-selectable:not(.hidden)\').size() == 0) {
							$(this).parent().hide();
						}
					},
					\'testQuery\': function (query, txt, _row) {
						for (var i = 0; i < query.length; i += 1) {
						    if (txt.indexOf(query[i]) === -1 && $(_row).siblings(\'.ms-optgroup-label\').text().indexOf(query[i]) === -1) {
                                return false;
                            }
                        }
                        return true;
					},
				})
				.on(\'keydown\', function(e){
				  // down arrow
				  if (e.which === 40){
					that.$selectableUl.focus();
					return false;
				  }
				  // escape
				  if (e.which === 27){
					$selectableSearch.val(\'\');
					return false;
				  }				  
				});

				that.qs2 = $selectionSearch.quicksearch(selectionSearchString, {
					\'show\': function () {
						$(this).removeClass(\'hidden\');
						$(this).parent().show();
					},
					\'hide\': function () {
						$(this).addClass(\'hidden\');
						
						if ($(this).siblings(\'.ms-elem-selection:not(.hidden)\').size() == 0) {
							$(this).parent().hide();
						}
					},
					\'testQuery\': function (query, txt, _row) {
						for (var i = 0; i < query.length; i += 1) {
						    if (txt.indexOf(query[i]) === -1 && $(_row).siblings(\'.ms-optgroup-label\').text().indexOf(query[i]) === -1) {
                                return false;
                            }
                        }
                        return true;
					},
				})
				.on(\'keydown\', function(e){
				  // down arrow
				  if (e.which == 40){
					that.$selectionUl.focus();
					return false;
				  }
				  // escape
				  if (e.which === 27){
					$selectionSearch.val(\'\');
					return false;
				  }	
				});
			  }');
			  
			$this->clientOptions['afterSelect'] = new JsExpression('function(){
				this.qs1.cache();
				this.qs2.cache();
			  }');
			  
			$this->clientOptions['afterDeselect'] = new JsExpression('function(){
				this.qs1.cache();
				this.qs2.cache();
			  }');
		}
		
        Html::addCssClass($this->options, 'multi-select');
    }
    /**
     * Executes the widget.
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->data, $this->options, true);
        } else {
            echo Html::dropDownList($this->name, $this->value, $this->data, $this->options, true);
        }
        MultiSelectAsset::register($this->view);
        $this->registerPlugin('multiSelect');
    }


}