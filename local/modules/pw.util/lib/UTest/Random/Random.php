<?php namespace PW\Tools\UTest\Random;

class Random extends RandomFields
{
	protected $fields;
	protected $params;
	protected $className = null;
	protected $fn = null;
	protected $cnt;
	protected $result = null;

	/**
	 * Random constructor.
	 *
	 * @param array $fields
	 * @param int $cnt
	 * @param array $handler
	 */
	public function __construct(array $fields, $cnt = 1, $handler = [])
	{
		parent::__construct();

		$this->fields = $fields;

		if(count($handler) > 0){
			$this->className = $handler[2];
			$this->fn = $handler[1];
		}

		$this->cnt = $cnt;
	}

	/**
	 * @method start
	 *
	 * @return $this
	 */
	public function start()
	{
		for($i = 0; $i < $this->cnt; $i++){
			$this->fioRand();
			foreach ($this->fields as $code => $field) {
				if(is_array($field)){
					foreach ($field as $func => $params) {
						if(is_callable(array($this, $func))){
							$this->result[$i][$code] = call_user_func_array(array($this, $func), $params);
						} else {
							$this->result[$i][$code] = $func;
						}
					}
				} else {
					if(is_callable(array($this, $field))){
						$this->result[$i][$code] = call_user_func(array($this, $field));
					} else {
						$this->result[$i][$code] = $field;
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @method getResult
	 *
	 * @return null|array
	 */
	public function getResult()
	{
		return $this->result;
	}
}