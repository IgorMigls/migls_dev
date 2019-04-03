<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
//dump($arResult['ORDER_SECTIONS']);
?>
<table width="100%" class="bx_order_list_table">
	<tr>
		<td>
			Заказ №<?=$arResult['FIELDS']['ACCOUNT_NUMBER']?>
			от <?if($arResult['FIELDS']['DATE_INSERT'] instanceof \Bitrix\Main\Type\DateTime){
				echo $arResult['FIELDS']['DATE_INSERT']->format('d.m.Y H:i:s');
			} else {
				echo $arResult['FIELDS']['DATE_INSERT'];
			} ?>
		</td>
	</tr>
	<tr>
		<td>На сумму <?=$arResult['SUM_FORMAT']?> руб.</td>
	</tr>
</table>
<table width="100%" style="margin-top: 40px" class="bx_order_list_table">
	<thead>
	<tr>
		<th style="text-align: left"><b>Доставка:</b></th>
	</tr>
	</thead>
	<tr>
		<td>
			<?=$arResult['FIELDS']['ADDRESS_FORMAT'];?>
			<ul>
				<? foreach ($arResult['SHOP'] as $arShop) {?>
					<li><?=$arShop['NAME']?> - <?=$arShop['DELIVERY_TIME']?></li>
				<?}?>
			</ul>
			<br />
			<?if(strlen($arResult['FIELDS']['PHONE']) > 0):?>
				Телефон: <b><?=$arResult['FIELDS']['PHONE']?></b>
			<?endif;?>
		</td>
	</tr>
</table>

<table width="100%" style="margin-top: 40px" class="bx_order_list_table">
	<thead>
	<tr>
		<th style="text-align: left"><b>Состав:</b></th>
	</tr>
	</thead>
	<tr>
		<td>
			<hr />
			<? foreach ($arResult['SHOP'] as $orderId => $item) { ?>
			<table width="100%" class="bx_order_list_table_items">
				<thead>
				<tr>
					<th style="text-align: left"><b><?=$item['NAME']?></b></th>
				</tr>
				</thead>
				<tbody>

				<tr>
					<td>
						<table width="100%" style="margin-top: 15px">
							<thead>
							<tr>
								<th>Фото</th>
								<th>Название</th>
								<th>Количетсво</th>
								<th>Цена</th>
								<th>Штрихкод</th>
								<th>Вес/объем</th>
							</tr>
							</thead>
							<tbody>
							<? foreach ($arResult['ORDER_SECTIONS'][$orderId] as $sections) {
								$PRODUCT = $sections['ELEMENT']; ?>
								<tr>
									<td style="border: 1px solid #999;">
										<a href="<?=$PRODUCT['ORIGINAL_IMG']['SRC']?>" target="_blank">
											<img src="<?=$PRODUCT['IMG']['src']?>" />
										</a>
									</td>
									<td style="border: 1px solid #999;">
										<?=$PRODUCT['PRODUCT_NAME']?><br /><br/>
										<b>Категория:</b>
										<?
										$chainSection = [];
										foreach ($sections['CHAIN'] as $chain) {
											$chainSection[] = $chain['NAME'];
										}
										echo implode(' -> ', $chainSection);
										?>
										<br /><br />
										<?if(count($sections['REPLACES']) > 0):?>
										<b>Замены:</b>
											<table style="margin-bottom: 20px">
												<thead>
												<tr>
													<th>Название</th>
													<th>Штрихкод</th>
													<th>Цена</th>
												</tr>
												</thead>
												<?
												foreach ($sections['REPLACES'] as $replace) {?>
													<tr>
														<td style="border: 1px solid #999;">
															<a href="<?=$replace['IMG']['SRC']?>" target="_blank">
																<img src="<?=$replace['IMG']['RESIZE']['src']?>" />
															</a><br />
															<b><?=$replace['NAME']?></b>
														</td>
														<td style="border: 1px solid #999;"><?=$replace['PROPERTY_CML2_LINK_XML_ID']?></td>
														<td style="border: 1px solid #999;"><?=$replace['PRICES']['PRICE']?></td>
													</tr>
												<?}?>
											</table>
										<?endif; ?>
									</td>
									<td style="text-align: center; border: 1px solid #999;">
										<?=(float)$sections['QUANTITY']?>
									</td>
									<td style="text-align: center; border: 1px solid #999;">
										<?
										$price = $sections['PRICE'] * $sections['QUANTITY'];
										echo \SaleFormatCurrency($price, 'RUB');
										?>
									</td>
									<td style="text-align: center;border: 1px solid #999;"><?=$PRODUCT['BARCODE']?></td>
									<td style="text-align: center;border: 1px solid #999;"><?=$sections['VALUE_WEIGHT']?></td>
								</tr>
							<?}?>
							</tbody>
						</table>
						<br /><br />
					</td>
				</tr>
				</tbody>
			</table>
			<? } ?>
		</td>
	</tr>
</table>
<p>Комментарий покупателя:</p>
<p><?=$arResult['FIELDS']['USER_DESCRIPTION']?></p>