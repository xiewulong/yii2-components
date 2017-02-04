<?php
/*!
 * yii2 - component - action
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-components
 * https://raw.githubusercontent.com/xiewulong/yii2-components/master/LICENSE
 * create: 2017/01/24
 * update: 2017/01/24
 * since: 0.0.1
 */

namespace yii\components;

use Yii;
use yii\web\Response;

class Action extends \yii\base\Action {

	/**
	 * ajax response
	 *
	 * @since 0.0.1
	 * @param {string|array} [$response] response data
	 * @param {string} [$format=json] response data format
	 * @param {string} [$callbackParam=callback] jsonp callback param name catched from request
	 * @return {string|array|null}
	 */
	protected function respond($response = null, $format = Response::FORMAT_JSON, $jsonp = false, $callbackParam = 'callback') {
		if($jsonp && $callback = \Yii::$app->request->get($callbackParam)) {
			$_response = $response;
			$response = [
				'data' => $_response,
				'callback' => $callback,
			];

			$format = Response::FORMAT_JSONP;
		}

		\Yii::$app->response->format = $format;
		return $response;
	}

}
