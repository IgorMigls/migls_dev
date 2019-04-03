<template>
	<div>
		<div id="shop_time_window_150618" style="" class="shop_time_window">
			<div class="b-popup b-popup-card b-popu-card_interval">
				<div class="b-popup-interval">
					<button class="b-button b-button__close-popup" @click="close"></button>
					<div class="b-popup-cart__head">
						<div class="b-products-block-top b-ib bg_reverse">
							<div class="cart__img-wrapper">
								<div class="cart__prod-title">
									<div class="search__title">Интервалы</div>
								</div>
							</div>
						</div>
					</div>
					<div class="interval__content">
						<div class="interval__img-wrapper">
							<div class="interval__img">
								<a href="javascript:" v-if="showTimes.PICTURE"><img :src="showTimes.PICTURE.src" /></a>
							</div>
						</div>
						<div class="interval__table">
							<div class="interval_wrap">
								<el-tabs tab-position="left" :value="active">
									<el-tab-pane class="is-left" :name="'index_'+ index" v-for="(item, index) in showTimes.CALENDAR" :key="index">
										<span slot="label">{{item.NAME}}, {{item.FORMAT.DAY}} {{item.FORMAT.MONTH_LOCALE}}</span>
										<div class="time_item_item" v-for="time in item.TIMES" :key="time.ID">
											<span class="interval__time">{{time.TIME_FROM}} - {{time.TIME_TO}}</span>
											<span class="interval__price">
												<span v-if="time.ACTIVE === 'N' || time.CLOSED_BY_ADMIN == 'Y'">недоступно</span>
												<span v-else>{{time.PRICE}} <i class="fa fa-rouble"></i></span>
											</span>
										</div>
									</el-tab-pane>
								</el-tabs>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import {mapActions, mapGetters} from 'vuex';
	
	export default {
		name: "delivery-times",
		props: {},
		data() {
			return {
				active: 'index_0'
			}
		},
		methods: {
			close(){
				this.$store.commit('showTimes', false);
			}
		},
		watch: {},
		created() {
		},
		beforeUpdate() {
		},
		components: {},
		computed: {
			...mapGetters([
				'showTimes'
			])
		},
		mounted() {
		}
	}
</script>

<style lang="scss">
	.shop_time_window {
		margin-right: 10px;
		
		.b-button__close-popup {
			right: 15px;
			z-index: 100;
			top: 20px;
			background-image: url(/local/dist/images/sprite2.png);
			background-position: -510px -512px;
			width: 33px;
			height: 33px;
		}
		
		.search__title {
			text-align: left;
		}
		
		.interval_wrap {
			text-align: left;
		}
		
		.el-tabs__item.is-left {
			text-align: left;
		}
		
		.el-tabs__item {
			height: 25px;
			line-height: 25px;
			border-radius: 12px;
			padding: 0 10px;
			color: #000;
			margin-bottom: 5px;
			
			&::before {
				content: '';
				height: 12px;
				width: 12px;
				border-radius: 50%;
				background: #efeaea;
				display: inline-block;
				vertical-align: middle;
				margin-right: 10px;
				position: relative;
				top: -1px;
			}
			
			&:hover {
				background: #f1c40f;
			}
		}
		
		.el-tabs__item.is-active {
			color: #000;
			background: #f1c40f;
		}
		
		.el-tabs--left .el-tabs__header.is-left {
			margin-right: 20px;
		}
		
		.el-tabs__active-bar {
			background: transparent;
		}
		
		.el-tabs__nav-wrap::after {
			background: transparent;
		}
	}
	
</style>