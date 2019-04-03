<?namespace PW\Tools;

use Bitrix\Main;
use Bitrix\Main\DB\MysqliSqlHelper;
use Bitrix\Main\Entity;

class DBHelper
{
	/**
	 * @method getFieldByColumnType
	 * @param $name
	 * @param $type
	 * @param array|null $parameters
	 *
	 * @return Entity\DateField|Entity\DatetimeField|Entity\FloatField|Entity\IntegerField|Entity\StringField
	 */
	public static function getFieldByColumnType($name, $type, array $parameters = null)
	{
		switch ($type)
		{
			case "int":
				return new Entity\IntegerField($name, $parameters);

			case "real":
				return new Entity\FloatField($name, $parameters);

			case "datetime":
			case "timestamp":
				return new Entity\DatetimeField($name, $parameters);

			case "date":
				return new Entity\DateField($name, $parameters);
			case "text":
			case "longtext":
			case "mediumtext":
				return new Entity\TextField($name, $parameters);
		}

		return new Entity\StringField($name, $parameters);
	}
}