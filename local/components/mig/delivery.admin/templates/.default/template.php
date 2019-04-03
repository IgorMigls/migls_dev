<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
$this->setFrameMode(true);?>

<div id="order_app">
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav">
				<li class="nav-item">
					<router-link class="nav-link" active-class="nav-link-active" to="/">Новые заказы</router-link>
				</li>
				<li class="nav-item">
					<router-link class="nav-link" active-class="nav-link-active" to="/complect">На сборку</router-link>
				</li>
				<li class="nav-item">
					<router-link class="nav-link" active-class="nav-link-active" to="/delivery">На доставку</router-link>
				</li>
			</ul>
		</div>
	</nav>
	<router-view v-loading="loaderList" class="order_wraps"></router-view>
</div>
