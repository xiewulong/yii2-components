<?php
/*!
 * yii2 - component - active record
 * xiewulong <xiewulong@vip.qq.com>
 * https://github.com/xiewulong/yii2-components
 * https://raw.githubusercontent.com/xiewulong/yii2-components/master/LICENSE
 * create: 2016/8/7
 * update: 2016/12/14
 * since: 0.0.1
 */

namespace yii\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\validators\FileValidator;
use yii\validators\RangeValidator;
use yii\web\Cookie;

class ActiveRecord extends \yii\db\ActiveRecord {

	private $_attributeItemTypes = [
		'Name',
		'Unsupport',
	];

	private $_attributeNameItemsList = [];

	private $_attributeUnsupportItemsList = [];

	private $_firstErrorAttribute = false;

	private $statisticsParam = '_statistics';

	protected $statisticsEnable = false;

	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		$scenarios = parent::scenarios();

		$scenarios[$this->statisticsParam] = [
			'pv',
			'uv',
		];

		return $scenarios;
	}

	/**
	 * Check if it can increase page view
	 *
	 * @since 0.0.1
	 * @return {boolean}
	 */
	protected function pvRuleCheck() {
		return !\Yii::$app->request->isPost && !\Yii::$app->request->isAjax;
	}

	/**
	 * Check if it can increase unique visitor
	 *
	 * @since 0.0.1
	 * @return {boolean}
	 */
	protected function uvRuleCheck() {
		$expire = strtotime(date('Y-m-d', time() + 60 * 60 * 24));
		$item = strtr(static::classname(), '\\', '_') . '_' . $this->id;

		$session = \Yii::$app->session->get($this->statisticsParam);
		if(!\Yii::$app->session->has($this->statisticsParam) || $session['expire'] != $expire) {
			$session = [
				'expire' => $expire,
				'items' => [],
			];
		}

		if(!isset($session['items'][$item])) {
			$session['items'][$item] = true;
			\Yii::$app->session->set($this->statisticsParam, $session);

			return true;
		}

		return false;
	}

	/**
	 * Add pv and uv when accessed
	 *
	 * @since 0.0.1
	 * @return {boolean}
	 */
	public function accessedHandler() {
		if(!$this->statisticsEnable
			|| $this->scenario != $this->statisticsParam
			|| !$this->validate()) {
			return false;
		}

		if($this->pvRuleCheck()) {
			$this->pv++;
		}
		if($this->uvRuleCheck()) {
			$this->uv++;
		}

		return $this->save(false);
	}

	/**
	 * Data accessed
	 *
	 * @since 0.0.1
	 * @param {boolean} $statisticsEnable
	 * @return {object}
	 */
	public function accessed($statisticsEnable = true) {
		if($statisticsEnable) {
			$this->scenario = $this->statisticsParam;
			$this->accessedHandler();
		}

		return $this;
	}

	/**
	 * Cache attribute items
	 *
	 * @since 0.0.1
	 * @param {string} $attribute
	 * @return {none}
	 */
	private function cacheAttributeItems($attribute) {
		$nameItems = [];
		$unsupportItems = [];

		$_attribute = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)))) . 'Items';
		if($this->hasMethod($_attribute)) {
			$attributeitems = $this->$_attribute();

			$_defaultNameItems = $attributeitems[0];
			if($_defaultNameItems && is_array($_defaultNameItems)) {
				$_scenario = [];
				foreach($this->getActiveValidators($attribute) as $validator) {
					if($validator instanceof RangeValidator) {
						$_scenario = ArrayHelper::merge($_scenario, ArrayHelper::filter($_defaultNameItems, $validator->range));
					}
				}
				$nameItems[$this->scenario] = array_unique($_scenario);
				$nameItems['_default'] = $_defaultNameItems;
			}

			if(isset($attributeitems[1]) && is_array($attributeitems[1])) {
				$unsupportItems = $attributeitems[1];
			}
		}

		$this->_attributeNameItemsList[$attribute] = $nameItems;
		$this->_attributeUnsupportItemsList[$attribute] = $unsupportItems;
	}

	/**
	 * Return attribute items
	 *
	 * @since 0.0.1
	 * @param {string} $attribute
	 * @param {int} [$type]
	 * @param {string} [$scenario]
	 * @return {array}
	 */
	public function getAttributeItems($attribute, $type = 0, $json = false, $default = false) {
		$cacheName = '_attribute' . $this->_attributeItemTypes[$type] . 'ItemsList';
		if(!isset($this->$cacheName[$attribute])) {
			$this->cacheAttributeItems($attribute);
		}

		if($type) {
			$items = $this->$cacheName[$attribute];

			return $json ? Json::encode($items) : $items;
		}

		$scenario = $default ? '_default' : $this->scenario;
		$attributeItems = $this->$cacheName[$attribute];
		$items = isset($attributeItems[$scenario]) ? $attributeItems[$scenario] : [];

		return $json ? Json::encode($items) : $items;
	}

	/**
	 * Return attribute items
	 *
	 * @since 0.0.1
	 * @param {string} $attribute
	 * @return {array}
	 */
	public static function defaultAttributeItems($attribute, $type = 0, $json = false) {
		$static = new static;

		return $static->getAttributeItems($attribute, $type, $json, true);
	}

	/**
	 * Return status text
	 *
	 * @since 0.0.1
	 * @param {string} $attribute
	 * @return {string|null}
	 */
	public function getAttributeText($attribute) {
		$items = $this->getAttributeItems($attribute, 0, false, true);

		return isset($items[$this->$attribute]) ? $items[$this->$attribute] : null;
	}

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
