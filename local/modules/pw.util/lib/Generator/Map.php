<?php namespace PW\Tools\Generator;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main;
use PW\Tools\DBHelper;
use Esd\Debug;

class Map
{
	const TYPE_MODULE_BX = 'bitrix';
	const TYPE_MODULE_LOCAL = 'local';

	protected $params = [];
	protected static $table;
	/** @var null|Main\DB\Connection */
	protected static $connect = null;
	protected static $instance = null;
	/** @var array of Main\Entity\Field */
	protected $fields = [];

	private $fieldRaw = [];
	private $langPrefix;

	/**
	 * Map constructor.
	 *
	 * @param array $params
	 */
	public function __construct($params = [])
	{
		if(is_null(self::$connect)){
			self::$connect = Main\Application::getConnection();
		}

		if(count($params) > 0){
			$this->setParams($params);
		}
	}

	/**
	 * @method getInstance
	 * @param array $params
	 *
	 * @return null|Map
	 */
	public static function getInstance($params = [])
	{
		if(is_null(self::$instance)){
			self::$instance = new Map($params);
		}

		return self::$instance;
	}

	/**
	 * @method generator
	 * @param bool|false $showDump
	 *
	 * @return array|bool
	 */
	public function generator($showDump = false)
	{
		$fields = $this->getFields();
		$genMapStr = $this->createMapStr();
		$genLangStr = $this->createLangStr();

		if($this->params['create_files'] === true && !$showDump){
			$arFiles = [
				'MAP'=>0,
				'LANG'=>0
			];

			$type = $this->params['folder_type'] == 'local' ? self::TYPE_MODULE_LOCAL : self::TYPE_MODULE_BX;
			$pathToModule = $_SERVER['DOCUMENT_ROOT'].'/'.$type.'/modules/'.$this->params['module'];
			$pathToLib = $pathToModule.'/lib';

			$s = '/';
			if($this->params['lib']){
				$s = $this->params['lib'];
			}

			$pathToFile = $pathToLib.$s.$this->params['class'].'.php';
			$File = new Main\IO\File($pathToFile);

			if(!$File->isExists()){
				$mapCreate = $File->putContents($genMapStr);
			} else{
				$File = new Main\IO\File($pathToLib.$s.$this->params['class'].'_'.date('d_m_Y').'_'.time().'.php');
				$mapCreate = $File->putContents($genMapStr);
			}

			if(intval($mapCreate) > 0)
				$arFiles['MAP'] = $File->getPath();

			unset($File);

			$langFile = $pathToModule.'/lang/ru/lib'.$s.$this->params['class'].'.php';
			$File = new Main\IO\File($langFile);
			if(!$File->isExists()){
				$langCreate = $File->putContents($genLangStr);
			} else {
				$File = new Main\IO\File($pathToModule.'/lang/ru/lib'.$s.$this->params['class'].'_'.date('d_m_Y').'_'.time().'.php');
				$langCreate = $File->putContents($genLangStr);
			}
			if(intval($langCreate) > 0)
				$arFiles['LANG'] = $File->getPath();

			unset($File);

			return $arFiles;

		} elseif($showDump){
			echo '<pre>';
				var_dump($genMapStr);
				var_dump($genLangStr);
			echo '</pre>';
			return false;
		}

		return $fields;
	}

	/**
	 * @method createMapStr
	 * @return bool|mixed|string
	 */
	private function createMapStr()
	{
		$out = file_get_contents(__DIR__.'/map_temple');

		if(!$this->params['namespace']){
			$this->params['namespace'] = __NAMESPACE__;
		}
		$namespace = $this->params['namespace'];

		if(!isset($this->params['class'])){
			$classTmp = explode('_', self::$table);
			foreach ($classTmp as $k => $val) {
				$classTmp[$k] = ucfirst($val);
			}
			$class = implode('', $classTmp).'Table';
			$this->params['class'] = $class;
		} else {
			$class = $this->params['class'];
		}

		$out = str_replace('#NAMESPACE#', $namespace, $out);
		$out = str_replace('#CLASS_NAME#', $class, $out);

		$validatorsStr = '';

		$fieldsStr = "array(\r\n";
		foreach ($this->fields as $field) {
			/** @var Main\Entity\Field $field */

			$cl = get_class($field);
			preg_match('#\\\(\w+)$#', $cl, $fieldClassMatch);

			if($fieldClassMatch){
				$fieldClass = $fieldClassMatch[1];
			} else {
				$fieldClass = $cl;
			}

			if(strlen($this->params['lang_prefix']) > 0){
				$this->langPrefix = strtoupper($this->params['lang_prefix']);
			}

			$fieldsStr .=  "\t\t\t'".$field->getName()."' => new Entity\\".$fieldClass."('".$field->getName()."', array(\n";
			$fieldsStr .= "\t\t\t\t\t'title' => Loc::getMessage('".$this->langPrefix."_ENTITY_".$field->getName()."_FIELD'),\n";
			if($field->isRequired()){
				$fieldsStr .= "\t\t\t\t\t'required' => true,\n";
			}
			if($field->isPrimary()){
				$fieldsStr .= "\t\t\t\t\t'primary' => true,\n";
			}
			if($field->isAutocomplete()){
				$fieldsStr .= "\t\t\t\t\t'autocomplete' => true,\n";
			}

			$fieldsStr .= "\t\t\t\t)\n";
			$fieldsStr .= "\t\t\t),\n";


			if($this->params['use_validators'] == true && $fieldClass == 'StringField'){
				if(preg_match('#\((\d+)\)$#', $this->fieldRaw[$field->getName()]['Type'], $charMatch)){
					$validateFunctionName = "validate".\Bitrix\Main\Entity\Base::snake2camel($field->getName());
					$validatorsStr .= "\n\t"."public static function ".$validateFunctionName." ()\n";
					$validatorsStr .= "\t{\n";
					$validatorsStr .= "\t\t"."return array(\n";
					$validatorsStr .= "\t\t\t"."new Entity\\Validator\\Length(null, ".$charMatch[1]."),\n";
					$validatorsStr .= "\t\t);\n";
					$validatorsStr .= "\t}\n";
				}
			}
		}
		$fieldsStr .= "\t\t)";
		$out = str_replace('#TABLE_NAME#', self::$table, $out);
		$out = str_replace('#MAP_FIELDS#', $fieldsStr, $out);
		$out = str_replace('#VALIDATORS#', $validatorsStr, $out);

		return $out;
	}

	/**
	 * @method createLangStr
	 * @return string
	 */
	private function createLangStr()
	{
		$out = "<?php\n";
		$arLang = $this->getParams('lang_fields');
		foreach ($this->fields as $field) {
			/** @var Main\Entity\Field $field */
			$title = $arLang[$field->getName()] ? $arLang[$field->getName()] : false;
			$out .='$MESS[\''.$this->langPrefix.'_ENTITY_'.$field->getName().'_FIELD\'] = \''.$title.'\';'."\n";
		}

		return $out;
	}

	/**
	 * @method getFields
	 * @param string $tableName
	 *
	 * @return array
	 */
	public function getFields($tableName = '')
	{
		if(strlen($tableName) > 0){
			self::$table = $tableName;
		}

		if(count($this->fields) == 0){
			$result = [];
			$sql = 'SHOW COLUMNS FROM '.self::$connect->getSqlHelper()->quote(self::$table);
			$dbRes = self::$connect->query($sql);
			$arLang = $this->getParams('lang_fields');
			while($field = $dbRes->fetch()){
				$this->fieldRaw[$field['Field']] = $field;
				if(preg_match('/^(.*)\(\d+\)/', $field['Type'], $matchType)){
					$type = $matchType[1];
				} else {
					$type = $field['Type'];
				}

				$params = [];
				if($field['Key'] == 'PRI'){
					$params = [
						'primary'=>true,
						'autocomplete'=>true
					];
				}

				if(strlen($field["Default"]) > 0){
					$params['default_value'] = $field['Default'];
				}

				if($field['Null'] == 'NO' && $field['Key'] != 'PRI'){
					$params['required'] = true;
				}

				if(strlen($arLang[$field['Field']]) > 0){
					$params['title'] = $arLang[$field['Field']];
				}

				$obField = DBHelper::getFieldByColumnType($field['Field'], $type, $params);

				$result[$field['Field']] = $obField;
			}

			$this->fields = $result;
		}

		return $this->fields ;
	}

	/**
	 * @method getAllTables
	 * @return array|null
	 */
	public static function getAllTables()
	{
		$dbRes = self::getConnect()->query('SHOW TABLES;');
		$result = null;
		while($table = $dbRes->fetch()){
			$result[] = array_shift($table);
		}
		return $result;
	}

	/**
	 * @method getLangFields
	 * @param $data
	 *
	 * @return array|null
	 */
	public function getLangFields($data)
	{
		$arFields = $this->getFields($data['table']);
		$result = null;
		foreach ($arFields as $field) {
			$result[] = ['CODE'=>$field->getName(),'TITLE'=>''];
		}
		return $result;
	}

	/**
	 * @method generateModel
	 * @param array $data
	 *
	 * @return array|bool|null
	 */
	public static function generateModel(array $data)
	{
		$params = $data['FIELDS'];
		$params['create_files'] = true;

		$result = null;

		$Map = new self($params);

		if(!empty($data['LANG']) && count($data['LANG']) > 0){
			$langParams = [];
			foreach ($data['LANG'] as $lang) {
				$langParams[$lang['CODE']] = $lang['TITLE'];
			}
			$Map->addParams('lang_fields', $langParams);
		}

		$result = $Map->generator();

		return $result;
	}



	/* ============================================================================================================== */

	/**
	 * @method getParams
	 * @param string $key
	 *
	 * @return array
	 */
	public function getParams($key = '')
	{
		if(strlen($key) > 0)
			return $this->params[$key];

		return $this->params;
	}

	/**
	 * @method setParams
	 * @param array $params
	 *
	 * @return $this
	 * @throws GenException
	 */
	public function setParams($params = [])
	{
		$this->params = $params;

		if(!$params['table'])
			throw new GenException('Not table name');

		self::$table = $params['table'];

		if(is_null(self::$connect))
			self::$connect = Main\Application::getConnection();

		$this->langPrefix = strtoupper(self::$table);
		if(strlen($params['lang_prefix']) > 0){
			$this->langPrefix = $params['lang_prefix'];
		}

		if(empty($params['folder_type'])){
			$this->params['folder_type'] = self::TYPE_MODULE_LOCAL;
		}

		return $this;
	}

	/**
	 * @method addParams
	 * @param $key
	 * @param $val
	 *
	 * @return $this
	 */
	public function addParams($key, $val)
	{
		$this->params[$key] = $val;
		return $this;
	}

	/**
	 * @method getTable - get param table
	 * @return array
	 */
	public static function getTable()
	{
		return self::$table;
	}

	/**
	 * @method getFieldRaw - get param fieldRaw
	 * @return array
	 */
	public function getFieldRaw()
	{
		return $this->fieldRaw;
	}

	/**
	 * @method getConnect
	 * @return Main\DB\Connection|null
	 */
	public static function getConnect()
	{
		if(is_null(self::$connect)){
			self::$connect = Main\Application::getConnection();
		}
		return self::$connect;
	}


}