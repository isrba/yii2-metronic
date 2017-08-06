<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace isrba\metronic\widgets;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\web\JsExpression;
use yii\web\View;
use isrba\metronic\bundles\NotificationAsset;

/**
 * Notification renders a toastr notification box
 *
 * For example,
 * ```php
 * Notification::widget([
 *     'title' => 'Success! Some Header Goes Here',
 *     'body' => 'Duis mollis, est non commodo luctus',
 *     'type' => Notification::TYPE_INFO,
 * ]);
 * ```
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the alert box:
 * ```php
 * Notification::begin(['type' => Notification::TYPE_DANGER]);
 * echo 'Some title and body';
 * Notification::end();
 * ```
 * @see https://github.com/CodeSeven/toastr
 */
class Notification extends  Widget
{
    // type
    const  TYPE_ERROR = 'error';
    const  TYPE_INFO = 'info';
    const  TYPE_SUCCESS = 'success';
    const  TYPE_WARNING = 'warning';
    // position
    const POSITION_TOP_RIGHT = 'toast-top-right';
    const POSITION_BOTTOM_RIGHT = 'toast-bottom-right';
    const POSITION_BOTTOM_LEFT = 'toast-bottom-left';
    const POSITION_TOP_LEFT = 'toast-top-left';
    const POSITION_TOP_CENTER = 'toast-top-center';
    const POSITION_BOTTOM_CENTER = 'toast-bottom-center';
    const POSITION_FULL_WIDTH = 'toast-top-full-width';
    // easing
    const EASING_LINEAR = 'linear';
    const EASING_SWING = 'swing';

    /**
     * @var string the notification title
     */
    public $title = '';
    /**
     * @var string the notification body
     */
    public $body = '';
    /**
     * @var string the notification type.
     * Valid values  are 'danger', 'info', 'success', 'warning'.
     */
    public $type = self::TYPE_SUCCESS;

    /**
     * Executes the widget.
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
        ob_start();
        ob_implicit_flush(false);
    }

    public function run()
    {
        $this->body .= ob_get_clean();

        $js =  'toastr.options = ' . Json::encode($this->clientOptions) . ';';
        $this->view->registerJs($js, View::POS_READY);
        $js = 'toastr["' . $this->type. '"]("' . $this->body . '", "' . $this->title . '")';
        $this->view->registerJs($js, View::POS_READY);

        NotificationAsset::register($this->view);
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    public function initOptions()
    {
        $defaultOptions = [
            'closeButton'=> true,
            'debug'=> false,
            'positionClass'=> 'toast-top-right',
            'onclick'=> null,
            'showDuration'=> '1000',
            'hideDuration'=> '1000',
            'timeOut'=> '5000',
            'extendedTimeOut'=> '1000',
            'showEasing'=> 'swing',
            'hideEasing'=> 'linear',
            'showMethod'=> 'fadeIn',
            'hideMethod'=> 'fadeOut'
        ];

        $this->clientOptions = array_merge($defaultOptions, $this->clientOptions);
    }
}
