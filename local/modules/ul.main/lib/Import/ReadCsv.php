<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 07.09.2016
 * Time: 17:54
 */

namespace UL\Main\Import;


class ReadCsv
{
	private $file;
	protected $head;
	protected $data;

	/**
	 * ReadCsv constructor.
	 *
	 * @param $file
	 */
	public function __construct($file)
	{
		$this->file = new \SplFileObject($file);

		$this->file->setCsvControl(';');
		$this->file->setFlags(\SplFileObject::READ_CSV);
	}

	public function readData()
	{
		$this->head = array_flip($this->file->current());

		$i = 0;
		foreach ($this->file as $item) {
//			if ($i > 10)
//				break;

			if (count($item) > 0 && $i > 0){
				$save = [];
				$barCode = str_replace(' ', '', $item[$this->head['BARCODE']]);
				$price = str_replace(',', '.', $item[$this->head['PRICE']]);

				$save['BARCODE'] = $barCode;
				$save['ARTICLE'] = $item[$this->head['ARTICLE']];
				$save['QUANTITY'] = $item[$this->head['QUANTITY']];
				$save['PRICE'] = floatval($price);

				$this->data[] = $save;
			}
			$i++;
		}
	}

	/**
	 * @method getData - get param data
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

}