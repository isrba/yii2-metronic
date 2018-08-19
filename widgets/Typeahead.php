<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace isrba\metronic\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\View;
use yii\web\JsExpression;

use isrba\metronic\bundles\TypeaheadAsset;

/**
 * Typeahead renders Typeahead Autocomplete component.
 *
 * @see https://twitter.github.io/typeahead.js/
 */
class Typeahead extends InputWidget
{
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        TypeaheadAsset::register($this->view);

        $bloodhoundId = 'bloodhound' . Inflector::camelize($this->options['id']);
        $url = Url::to([
            'site/attribute-values',
            'model' => $this->model->formName(),
            'value' => $this->attribute,
            'filter' => 'QUERY',
            'controllerId' => Yii::$app->controller->uniqueId,
            'actionId' => Yii::$app->controller->action->id,
            'schema' => 'typeahead',
        ]);

        $this->view->registerJs("
            var $bloodhoundId = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '$url',
                    wildcard: 'QUERY'
                }
            });", View::POS_READY);

        $this->clientOptions = [
            'minLength' => 0,
            'highlight' => true,
            'hint' => true,
        ];

        $dataset = [
            'name' => $bloodhoundId,
            'displayKey' => 'value',
            'source' =>  new JsExpression($bloodhoundId),
        ];

        $id = $this->options['id'];
        $options = Json::encode($this->clientOptions);
        $dataset = Json::encode($dataset);

        $this->view->registerJs("jQuery('#$id').typeahead($options, $dataset);", View::POS_READY);
    }
}