<?php
/**
 * Created by PhpStorm.
 * User: Станислав
 * Date: 26.03.2016
 * Time: 16:36
 */

namespace Soft;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;

Loader::includeModule('iblock');

class SectionTable extends \Bitrix\Iblock\SectionTable
{
	public static function getUfId()
	{
		return 'IBLOCK_9_SECTION';
	}


}