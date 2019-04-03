<?php
/**
 * Created by PhpStorm.
 * User: Станислав
 * Date: 22.09.2016
 * Time: 16:58
 */

namespace AB\FormIblock;

use Bitrix\Main\Entity;

class Result extends Entity\Result
{
	private $id;

	/**
	 * @method getId - get param id
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @method setId - set param Id
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

}