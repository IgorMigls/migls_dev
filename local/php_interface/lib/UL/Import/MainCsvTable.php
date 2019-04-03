<?php namespace UL\Import;

use Bitrix\Main\Application;
use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class MainCsvTable extends Entity\DataManager
{
	public static function getTableName()
	{
		return 't_csv_main';
	}

	public static function getMap()
	{
		return array(
			'ID' => new Entity\IntegerField('ID', array(
					'title' => Loc::getMessage('TMP_CSV_ENTITY_ID_FIELD'),
					'primary' => true,
					'autocomplete' => true,
				)
			),
			'ARTICLE' => new Entity\StringField('ARTICLE', array(
					'title' => Loc::getMessage('TMP_CSV_ENTITY_ARTICLE_FIELD'),
					'default_value' => ''
				)
			),
			'PRODUCT_NAME' => new Entity\StringField('PRODUCT_NAME', array(
					'title' => Loc::getMessage('TMP_CSV_ENTITY_PRODUCT_NAME_FIELD'),
					'default_value' => ''
				)
			),
			'PRODUCT_TEXT' => new Entity\TextField('PRODUCT_TEXT', array(
					'title' => Loc::getMessage('TMP_CSV_ENTITY_PRODUCT_PREV_TEXT_FIELD'),
					'default_value' => ''
				)
			),
			'PRODUCT_DETAIL_TEXT' => new Entity\TextField('PRODUCT_DETAIL_TEXT', array(
					'title' => Loc::getMessage('TMP_CSV_ENTITY_PRODUCT_PREV_TEXT_FIELD'),
					'default_value' => ''
				)
			),
			'BARCODE' => new Entity\StringField('BARCODE', array(
					'title' => Loc::getMessage('TMP_CSV_ENTITY_BARCODE_FIELD'),
					'default_value' => ''
				)
			),
			'PHOTO' => new Entity\TextField('PHOTO', array(
					'title' => Loc::getMessage('TMP_CSV_ENTITY_MORE_PHOTO_FIELD'),
					'default_value' => ''
				)
			),
		);
	}

	protected static function saveXml()
	{
		return array(
			function ($value) {
				return strtolower($value);
			}
		);
	}

	public static function truncate()
	{
		$sql = 'TRUNCATE TABLE '.self::getTableName();
		Application::getConnection()->query($sql);
	}

	public static function createTables()
	{
		$entity = CsvTable::getEntity();

		PR($entity->getFields());

		$connect = $entity->getConnection();

		if($connect->isTableExists(self::getTableName()))
			$connect->dropTable(self::getTableName());

		$entity->createDbTable();
	}

	public static function insertInCsv($file, $det = ';')
	{
		static::createTables();

		$obFile = new \SplFileObject($file, 'r');
		$obFile->setCsvControl($det);
		$obFile->setFlags(\SplFileObject::READ_CSV);

		$i = 0;
		foreach ($obFile as $item) {
			if(count($item) > 0 && $i > 0){
				$save = [

				];
//				$res = parent::add($save);
//				if(!$res->isSuccess()){
//					Debug::toLog($res->getErrorMessages());
//				}
			}
			$i++;
		}
	}
}