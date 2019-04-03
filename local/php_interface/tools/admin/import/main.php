<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$root = \Bitrix\Main\Application::getDocumentRoot();

use PW\Tools\Debug;
use UL\Import\MainCsvTable;

$post = $request->getPostList()->toArray();
if(check_bitrix_sessid() && $post['action'] == 'uploadMainTmp' && strlen($post['file']) > 0){
	$fileName = $root.$post['file'];
	$File = new \SplFileObject($fileName);
	$File->setFlags(SplFileObject::READ_CSV);
	$File->setCsvControl(';');

	$connect = \Bitrix\Main\Application::getConnection();
	$entityMain = MainCsvTable::getEntity();
	$tbl = MainCsvTable::getTableName();

	if($connect->isTableExists($tbl)) {
		$connect->dropTable($tbl);
	}
	$entityMain->createDbTable();

	$errors = null;
	$adds = 0;

	foreach ($File as $k => $item) {
		if($k > 0){
			$save = [
				'ARTICLE' => $item[5],
				'PRODUCT_NAME' => $item[0],
				'PRODUCT_TEXT' => $item[1],
				'PRODUCT_DETAIL_TEXT' => $item[2],
				'BARCODE' => $item[6],
				'PHOTO' => '/upload/img/auchan_images/'.$item[7]
			];

			TrimArr($save);

			if(count($save) > 0){
				$res = MainCsvTable::add($save);
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