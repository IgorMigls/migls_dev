<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 26.08.2016
 * Time: 12:59
 */

namespace UL\Main\Import;


use Bitrix\Main\Error;

class Result extends \Bitrix\Main\Entity\Result
{
	private $id;
	private $allSuccess = true;
	private $finish;
	private $itemSuccess = true;

	/**
	 * Result constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setFinish(false);
	}


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

	/**
	 * @method getIsSuccess - get param isSuccess
	 * @return boolean
	 */
	public function getIsSuccess()
	{
		return $this->isSuccess;
	}

	/**
	 * @method setIsSuccess - set param IsSuccess
	 * @param boolean $isSuccess
	 */
	public function setIsSuccess($isSuccess)
	{
		$this->isSuccess = $isSuccess;
	}

	/**
	 * @method getAllSuccess - get param allSuccess
	 * @return mixed
	 */
	public function getAllSuccess()
	{
		return $this->allSuccess;
	}

	/**
	 * @method setAllSuccess - set param AllSuccess
	 * @param mixed $allSuccess
	 */
	public function setAllSuccess($allSuccess)
	{
		$this->allSuccess = $allSuccess;
	}

	/**
	 * @method getFinish - get param finish
	 * @return mixed
	 */
	public function getFinish()
	{
		return $this->finish;
	}

	/**
	 * @method setFinish - set param Finish
	 * @param mixed $finish
	 */
	public function setFinish($finish)
	{
		$this->finish = $finish;
	}

	/**
	 * @method addError
	 * @param Error $error
	 */
	public function addError(Error $error)
	{
		parent::addError($error);
		$this->itemSuccess = false;
	}

	/**
	 * @method setItemSuccess - set param ItemSuccess
	 * @param boolean $itemSuccess
	 */
	public function setItemSuccess($itemSuccess)
	{
		$this->itemSuccess = $itemSuccess;
	}

	/**
	 * @method getItemSuccess - get param itemSuccess
	 * @return boolean
	 */
	public function getItemSuccess()
	{
		return $this->itemSuccess;
	}



}