<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 04.08.2016
 * Time: 14:41
 */

namespace DigitalWand\AdminHelper\Widget;

use Bitrix\Main\Application;
use Bitrix\Main\Page\Asset;

class ComponentWidget extends HelperWidget
{
	protected $root;
	protected $name = '';
	protected $template;


	public function __construct($name = '', $template = '.default', array $settings = [])
	{
		$this->name = $name;
		$this->template = $template;

		parent::__construct($settings);
		$this->root = Application::getDocumentRoot();
	}


	protected function getEditHtml()
	{
		global $APPLICATION;

		ob_start();
		$APPLICATION->IncludeComponent($this->name, $this->template, $this->settings);
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function generateRow(&$row, $data)
	{
		return false;
	}

	public function showFilterHtml()
	{
		return false;
	}

	public function showBasicEditField()
	{
		print '<tr>';
		print '<td colspan="2" width="100%">';
		print '<b style="text-align: center; display: block">'.$this->getSettings('TITLE').'</b>';
		print $this->getEditHtml();
		print '</td>';
		print '</tr>';
	}
}