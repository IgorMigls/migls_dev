<?php namespace PW\Tools\UTest\Random;

class RandomFields
{
	const MALE = 'male';
	const FEMALE = 'female';
	/**
	 * @var null|RandomFields
	 */
	protected static $instance = null;
	protected $randomIniNames = [];
	protected $name = '';
	protected $secondName = '';
	protected $lastName = '';

	/**
	 * RandomFields constructor.
	 */
	public function __construct()
	{
		$this->randomIniNames = parse_ini_file(dirname(__FILE__).'/names_random.ini', true);
	}


	/**
	 * @method getInstance - get param instance
	 * @return null|RandomFields
	 */
	public static function getInstance()
	{
		if(is_null(self::$instance)){
			self::$instance = new RandomFields();
		}

		return self::$instance;
	}

	/**
	 * @method stringRand
	 * @param int $length
	 * @param bool|false $symbols
	 *
	 * @return string
	 */
	public function stringRand($length = 10, $symbols = false)
	{
		$out = '';
		if(strlen($symbols) > 0){
			switch($symbols){
				case 'a-z':
					$out = randString($length, 'abcdefghijklnmopqrstuvwxyz');
					break;
				case '0-9':
					$out = randString($length, '0123456789');
					break;
				case '*':
					$out = randString($length, 'abcdefghijklnmopqrstuvwxyz0123456789_-');
					break;
				default:
					$out = randString($length);
					break;
			}
		}
		return $out;
	}

	/**
	 * @method emailRand
	 * @param string $temp
	 * @param string $domain
	 *
	 * @return string
	 */
	public function emailRand($temp = 'W', $domain = '1c.ru')
	{
		$out = '';
		$temp = str_replace('W', self::stringRand(6,'a-z'), $temp);
		$temp = str_replace('A', self::stringRand(6,'*'), $temp);
		$temp = str_replace('I', self::stringRand(6,'0-9'), $temp);

		$out = $temp.'@'.$domain;
		return $out;
	}

	/**
	 * @method fioRand
	 * @param string|bool $type
	 * @param array $arNames
	 *
	 * @return string
	 */
	public function fioRand($type = false, $arNames = [])
	{
		if(count($arNames) == 0 && count($this->randomIniNames) == 0){
			$this->randomIniNames = parse_ini_file(dirname(__FILE__).'/names_random.ini', true);
		} elseif(count($arNames) > 0){
			$this->randomIniNames = $arNames;
		}

		$gender = [self::MALE, self::FEMALE];

		$typeGender = [];
		$types = array_keys($this->randomIniNames);

		if(!$type){
			$genderKey = array_rand($gender);
			$type = $gender[$genderKey];
		}

		foreach ($types as $k => $typeKey) {
			if(preg_match('#(.*)_'.$type.'$#i', $typeKey)){
				$typeGender[] = $typeKey;
			}
		}

		$nameKey = array_rand($this->randomIniNames[$typeGender[0]]);
		$secondNameKey = array_rand($this->randomIniNames[$typeGender[1]]);
		$this->name = $this->randomIniNames[$typeGender[0]][$nameKey];
		$this->secondName = $this->randomIniNames[$typeGender[1]][$secondNameKey];

		$this->lastName = 'Ïóïêèí'.($type == self::FEMALE ? 'a' : false);

		return $this->name.' '.$this->secondName.' '.$this->lastName;
	}

	/**
	 * @method nameRand
	 * @return string
	 */
	public function nameRand()
	{
		return $this->name;
	}

	/**
	 * @method secondNameRand
	 * @return string
	 */
	public function secondNameRand()
	{
		return $this->secondName;
	}

	/**
	 * @method lastNameRand
	 * @return string
	 */
	public function lastNameRand()
	{
		return $this->lastName;
	}

	/**
	 * @method phoneRand
	 * @param int $code
	 * @param string $mask
	 *
	 * @return string
	 */
	public function phoneRand($code = 7, $mask = '(\d\d\d)(\d\d\d)(\d\d)(\d\d)')
	{
		$num = self::stringRand(10,'0-9');
		preg_match('#'.$mask.'#', $num, $arPhone);
		$phone = '+'.$code.' ('.$arPhone[1].') '.$arPhone[2].'-'.$arPhone[3].'-'.$arPhone[4];

		return $phone;
	}

}