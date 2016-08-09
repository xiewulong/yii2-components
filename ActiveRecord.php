<?php
/*!
 * yii2 - component - active record
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-components
 * https://raw.githubusercontent.com/xiewulong/yii2-components/master/LICENSE
 * create: 2016/8/7
 * update: 2016/8/7
 * since: 0.0.1
 */

namespace yii\components;

use Yii;

class ActiveRecord extends \yii\db\ActiveRecord {

	private $_firstErrorAttribute = false;

	/**
	 * Returns the first error's attribute name
	 *
	 * @since 0.0.1
	 * @param {string} $attribute
	 * @return {string|null}
	 */
	public function isFirstErrorAttribute($attribute) {
		if($this->_firstErrorAttribute === false) {
			$errorAttributes = array_keys($this->firstErrors);
			$this->_firstErrorAttribute = array_shift($errorAttributes);
		}

		return $attribute == $this->_firstErrorAttribute;
	}

	/**
	 * Returns the first error
	 *
	 * @since 0.0.1
	 * @return {string|null}
	 */
	public function getFirstErrorInFirstErrors() {
		$firstErrors = $this->firstErrors;

		return array_shift($firstErrors);
	}

}
