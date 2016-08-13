<?php
/*!
 * yii2 - component - active record
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-components
 * https://raw.githubusercontent.com/xiewulong/yii2-components/master/LICENSE
 * create: 2016/8/7
 * update: 2016/8/13
 * since: 0.0.1
 */

namespace yii\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\FileValidator;
use yii\validators\RangeValidator;
use yii\web\UploadedFile;

class ActiveRecord extends \yii\db\ActiveRecord {

	private $_attributeItemList = [];

	// private $_activeFileValidator;

	private $_firstErrorAttribute = false;

	/**
	 * Return attribute item list
	 *
	 * @since 0.0.1
	 * @param {string} $attribute
	 * @return {array}
	 */
	private function getAttributeItemList($attribute) {
		if(!isset($this->_attributeItemList[$attribute])) {
			$list = [];
			$_attribute = $attribute . 'Items';
			if($this->hasMethod($_attribute)) {
				$_default = $this->$_attribute();
				if($_default && is_array($_default)) {
					$_scenario = [];
					foreach($this->getActiveValidators($attribute) as $validator) {
						if($validator instanceof RangeValidator) {
							$_scenario = ArrayHelper::merge($_scenario, ArrayHelper::filter($_default, $validator->range));
						}
					}
					$list[$this->scenario] = array_unique($_scenario);
					$list['_default'] = $_default;
				}
			}
			$this->_attributeItemList[$attribute] = $list;
		}

		return $this->_attributeItemList[$attribute];
	}

	/**
	 * Return attribute items
	 *
	 * @since 0.0.1
	 * @param {string} $attribute
	 * @param {string} [$scenario]
	 * @return {array}
	 */
	public function getAttributeItems($attribute, $allScenario = false) {
		$scenario = $allScenario ? '_default' : $this->scenario;
		$items = $this->getAttributeItemList($attribute);

		return isset($items[$scenario]) ? $items[$scenario] : [];
	}

	/**
	 * Return attribute items
	 *
	 * @since 0.0.1
	 * @param {string} $attribute
	 * @return {array}
	 */
	public static function defaultAttributeItems($attribute) {
		$static = new static;

		return $static->getAttributeItems($attribute);
	}

	/**
	 * Return status text
	 *
	 * @since 0.0.1
	 * @param {string} $attribute
	 * @return {string|null}
	 */
	public function getAttributeText($attribute) {
		$items = $this->getAttributeItems($attribute, true);

		return isset($items[$this->$attribute]) ? $items[$this->$attribute] : null;
	}

	/**
	 * @inheritdoc
	 */
	// public function beforeValidate() {
	// 	return parent::beforeValidate() && $this->setUploadedFiles();
	// }

	/**
	 * Check active FileValidator and set UploadedFile object to attributes
	 *
	 * @since 0.0.1
	 * @return {boolean}
	 */
	// private function setUploadedFiles() {
	// 	$scenario = $this->getScenario();
	// 	foreach($this->getValidators() as $validator) {
	// 		if($validator instanceof FileValidator && $validator->isActive($scenario)) {
	// 			$this->_activeFileValidator = $validator;
	// 		}
	// 	}

	// 	if($this->_activeFileValidator) {
	// 		foreach($this->_activeFileValidator->attributes as $attribute) {
	// 			$this->$attribute = $this->_activeFileValidator->maxFiles === 1 ? UploadedFile::getInstance($this, $attribute) : UploadedFile::getInstances($this, $attribute);
	// 		}
	// 	}

	// 	return true;
	// }

	/**
	 * Save uploaded files
	 *
	 * @since 0.0.1
	 * @return {boolean}
	 */
	// public function saveUploadedFiles() {
	// 	if($this->_activeFileValidator) {
	// 		foreach($this->_activeFileValidator->attributes as $attribute) {
	// 			if($this->_activeFileValidator->maxFiles === 1) {
	// 				$file = $this->$attribute;
	// 				$file->saveAs('assets/' . $file->baseName . '.' . $file->extension);
	// 			} else {
	// 				foreach($this->$attribute as $file) {
	// 					$file->saveAs('assets/' . $file->baseName . '.' . $file->extension);
	// 				}
	// 			}
	// 		}
	// 	}

	// 	return true;
	// }

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
