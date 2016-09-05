<?php
/*!
 * yii2 - component - asset bundle
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-components
 * https://raw.githubusercontent.com/xiewulong/yii2-components/master/LICENSE
 * create: 2016/9/5
 * update: 2016/9/5
 * since: 0.0.1
 */

namespace yii\components;

use Yii;

class AssetBundle extends \yii\web\AssetBundle {

	protected $minimal;

	public function init() {
		parent::init();

		if(YII_ENV == 'prod' || \Yii::$app->request->get('assets') == 'minify') {
			$this->minimal = '.min';
		}
	}

}
