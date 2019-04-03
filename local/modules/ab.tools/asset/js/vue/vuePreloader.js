/**
 * Created by dremin_s on 28.06.2017.
 /** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

const vuePreloader = {
	template: `
		<div v-if="show">
			<div class="r_ajax_overlay"></div>
			<div id="r_preloader_ajax">
				<div class="loader">
					<div id="floatingBarsG" class="ajax_left">
						<div class="blockG" id="rotateG_01"></div>
						<div class="blockG" id="rotateG_02"></div>
						<div class="blockG" id="rotateG_03"></div>
						<div class="blockG" id="rotateG_04"></div>
						<div class="blockG" id="rotateG_05"></div>
						<div class="blockG" id="rotateG_06"></div>
						<div class="blockG" id="rotateG_07"></div>
						<div class="blockG" id="rotateG_08"></div>
					</div>
					<span class="load_text"><slot>{{ text }}</slot></span>
				</div>
			</div>
		</div>	
	`,
	props: {
		show: {
			type: Boolean,
			default: false
		},
		text: {
			type: String,
			default: 'Загрузка...'
		}
	}
};

export default vuePreloader;
