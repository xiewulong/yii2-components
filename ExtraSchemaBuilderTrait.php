<?php
/*!
 * yii2 - component - extra schema builder trait
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-components
 * https://raw.githubusercontent.com/xiewulong/yii2-components/master/LICENSE
 * create: 2016/12/30
 * update: 2016/12/30
 * since: 0.0.1
 */

namespace yii\components;

trait ExtraSchemaBuilderTrait {

	protected abstract function getDb();

	/**
	 * Creates a medium text column
	 *
	 * @since 0.0.1
	 * @return {object}
	 */
	public function mediumText() {
		return $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext');
	}

	/**
	 * Creates a long text column
	 *
	 * @since 0.0.1
	 * @return {object}
	 */
	public function longText() {
		return $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext');
	}

}
