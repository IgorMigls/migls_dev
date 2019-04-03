<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 27.04.2017
 */

namespace Online1c\Iblock;

use Bitrix\Main\Entity;
use Online1c\Iblock\Helpers\MainHelper;

class ElementQuery extends Entity\Query
{
	public function buildQuery()
	{
		$filter = $this->getFilter();
		$ws = 0;
		foreach ($filter as $code => $value) {
			$code = str_replace(MainHelper::FILTER_OPERANDS, '', $code);
			if($code == 'WF_STATUS_ID' || $code == 'WF_PARENT_ELEMENT_ID'){
				$ws++;
			}
		}

		if($ws == 0){
			$this->addFilter('=WF_STATUS_ID', 1);
			$this->addFilter('=WF_PARENT_ELEMENT_ID', null);
		}

		return parent::buildQuery();
	}
}