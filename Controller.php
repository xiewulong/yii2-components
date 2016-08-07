<?php
/*!
 * yii2 - component - controller
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-components
 * https://raw.githubusercontent.com/xiewulong/yii2-components/master/LICENSE
 * create: 2016/8/7
 * update: 2016/8/7
 * since: 0.0.1
 */

namespace yii\components;

use Yii;

class Controller extends \yii\web\Controller {

	public function init() {
		parent::init();

		// set referrer from request
		if($referrer = \Yii::$app->request->get('referrer')) {
			\Yii::$app->user->setReturnUrl($referrer);
		}
	}

	/**
	 * ajax response
	 *
	 * @since 0.0.1
	 * @param {string|array} [$response] response data
	 * @param {string} [$format=json] response data format
	 * @param {string} [$callbackParam] jsonp callback param name catched from request
	 * @return {string|array|null}
	 */
	public function respond($response = null, $format = \yii\web\Response::FORMAT_JSON, $callbackParam = null) {
		\Yii::$app->response->format = \Yii::$app->request->get('format', $format);
		if(($callback = \Yii::$app->request->get($callbackParam)) && \Yii::$app->response->format == 'jsonp' && $response) {
			$_response = $response;
			$response = [
				'data' => $_response,
				'callback' => $callback,
			];
		}

		return $response;
	}

}
