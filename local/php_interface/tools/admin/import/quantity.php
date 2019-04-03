<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$root = \Bitrix\Main\Application::getDocumentRoot();

use PW\Tools\Debug;
use UL\Import\RemainsTmpTable;

$post = $request->getPostList()->toArray();

includeModules('catalog');

if(check_bitrix_sessid() && $post['action'] == 'uploadRemains' && strlen($post['file']) > 0){
	$fileName = $root.$post['file'];
	$File = new \SplFileObject($fileName);
	$File->setFlags(SplFileObject::READ_CSV);
	$File->setCsvControl(';');

	RemainsTmpTable::createTables();

	$errors = null;
	$adds = 0;
	$artKey = $barKey = $priceKey = $qKey = false;

	$_SESSION['SHOP_ID_IMPORT'] = intval($post['shop']);

	$_SESSION['IBLOCK_PRODUCT'] = intval($post['catalog']);
	$arIblockSku = CCatalogSku::GetInfoByProductIBlock($_SESSION['IBLOCK_PRODUCT']);
	$_SESSION['IBLOCK_IMPORT'] = $arIblockSku['IBLOCK_ID'];

	foreach ($File as $k => $item) {

		if($k == 0){
			$barKey = array_search('CEANEA', $item);
			$artKey = array_search('NARTAR', $item);
			$priceKey = array_search('PRICE', $item);
			$qKey = array_search('QUANTITY', $item);
//			break;
		}

		if($k > 0 && $barKey !== false && $artKey !== false){
			$save = [
				'BARCODE' => $item[$barKey],
				'ARTICLE' => $item[$artKey],
				'SHOP_ID' => intval($post['shop']),
				'PRICE' => $item[$priceKey],
				'QUANTITY' => $item[$qKey]
			];

			TrimArr($save);

			if(count($save) > 0){
				$res = RemainsTmpTable::add($save);
				if(!$res->isSuccess()){
					$errors[] = $res->getErrorMessages();
				} else {
					$adds++;
				}
			}

		}

//		if($k > 30){
//			break;
//		}
	}

	$result = [
		'ADD' => $adds,
		'ERRORS' => $errors
	];

	echo json_encode($result);
}