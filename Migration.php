<?php
/*!
 * yii2 - component - migration
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-components
 * https://raw.githubusercontent.com/xiewulong/yii2-components/master/LICENSE
 * create: 2016/8/7
 * update: 2016/8/7
 * since: 0.0.1
 */

namespace yii\components;

use Yii;

class Migration extends \yii\db\Migration {

	/**
	 * i18n messages path
	 */
	public $messagesPath;

	/**
	 * i18n message category
	 */
	public $messageCategory;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->registerTranslations();
	}

	/**
	 * register translations
	 *
	 * @since 0.0.1
	 */
	public function registerTranslations() {
		$i18n = \Yii::$app->i18n;
		if($this->messagesPath && $this->messageCategory && !isset($i18n->translations[$this->messageCategory])) {
			$i18n->translations[$this->messageCategory] = [
				'class' => 'yii\i18n\PhpMessageSource',
				'basePath' => $this->messagesPath,
				'sourceLanguage' => \Yii::$app->sourceLanguage,
			];
		}
	}

}
