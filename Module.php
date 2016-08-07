<?php
/*!
 * yii2 - component - module
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-components
 * https://raw.githubusercontent.com/xiewulong/yii2-components/master/LICENSE
 * create: 2016/8/7
 * update: 2016/8/7
 * since: 0.0.1
 */

namespace yii\components;

use Yii;

class Module extends \yii\base\Module {

	/**
	 * i18n message category
	 */
	public $messageCategory;

	/**
	 * views path
	 */
	public $viewsPath;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->registerTranslations();

		if($this->viewsPath) {
			$this->setViewPath($this->viewsPath);
		}
	}

	/**
	 * register translations
	 *
	 * @since 0.0.1
	 */
	public function registerTranslations() {
		$i18n = \Yii::$app->i18n;
		if($this->messageCategory && !isset($i18n->translations[$this->messageCategory])) {
			$i18n->translations[$this->messageCategory] = [
				'class' => 'yii\i18n\PhpMessageSource',
				'basePath' => $this->basePath . DIRECTORY_SEPARATOR . 'messages',
				'sourceLanguage' => \Yii::$app->sourceLanguage,
			];
		}
	}

}
