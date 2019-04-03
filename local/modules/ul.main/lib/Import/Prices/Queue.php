<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 09.01.2017
 */

namespace UL\Main\Import\Prices;

use Bitrix\Main\Application;
use PW\Tools\Debug;
use UL\Main\Import\Helper;
use UL\Main\Import\Model\QueueTable;
use UL\Main\Import\Model\PriceTmpTable;
use Bitrix\Main\Type;
use Bitrix\Main\IO;

class Queue
{
	const PERIOD = '1 hour';

	/** @var array|null */
	protected $process = null;

	/** @var null|string */
	protected $root;

	protected $arShops = [];

	public function __construct()
	{
		$process = QueueTable::getRow([
			'order' => ['ID' => 'DESC'],
		]);
		$this->process = $process;

		if ($process['IN_PROCESS'] === QueueTable::QUEUE_IMPORT_IN_PROCESS){
			throw new \Exception('Процесс импорта уже запущен. Дождитесь конца операции', 400);
		}

		$this->root = Application::getDocumentRoot();
	}

	/**
	 * @method start
	 * @throws \Exception
	 */
	public function start()
	{
		if ($this->getProcess()['IN_PROCESS'] === QueueTable::QUEUE_IMPORT_NOT_PROCESS){
			QueueTable::update($this->process['ID'], [
				'IN_PROCESS' => QueueTable::QUEUE_IMPORT_IN_PROCESS,
				'LAST_IMPORT' => new Type\DateTime(),
			]);
		} else {
			throw new \Exception('Процесс уже запущен', 400);
		}
	}

	/**
	 * @method stop
	 */
	public function stop()
	{
		QueueTable::update($this->getProcess()['ID'], [
			'IN_PROCESS' => QueueTable::QUEUE_IMPORT_NOT_PROCESS
		]);
	}

	/**
	 * @method getProcess - get param process
	 * @return array|null
	 */
	public function getProcess()
	{
		if (is_null($this->process)){
			$res = QueueTable::add([
				'IN_PROCESS' => QueueTable::QUEUE_IMPORT_NOT_PROCESS,
				'LAST_IMPORT' => new Type\DateTime(),
			]);

			$this->process = QueueTable::getRow(['filter' => ['=ID' => $res->getId()]]);
		}

		return $this->process;
	}

	/**
	 * @param array|null $process
	 *
	 * @return Queue
	 */
	public function setProcess($process)
	{
		$this->process = $process;

		return $this;
	}

	public function fileList()
	{
		/** @var Type\DateTime $dateLastImport */
		$dateLastImport = $this->getProcess()['LAST_IMPORT'];
		$dateNow = new Type\DateTime();
		$files = Helper::recursiveProductIterator($this->root.'/upload/PRICES');

		if($dateNow > $dateLastImport->add(self::PERIOD)){
			/** @var IO\File $file */
			foreach ($files as $file) {
				$dateLast = Type\DateTime::createFromTimestamp($file->getModificationTime());
				$dateNow->add(self::PERIOD);

				if ($dateNow > $dateLast){

					preg_match('#.*_(\d+).csv#i', $file->getName(), $mFile);
					if(intval($mFile[1]) > 0){
						$this->arShops[] = $mFile[1];
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @method getArShops - get param arShops
	 * @return array
	 */
	public function getShops()
	{
		return $this->arShops;
	}

	/**
	 * @param array $arShops
	 *
	 * @return Queue
	 */
	public function setShops($arShops)
	{
		$this->arShops = $arShops;

		return $this;
	}


}