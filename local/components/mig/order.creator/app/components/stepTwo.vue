<template>
	<div class="step-2" v-loading="preloader.show" :element-loading-text="preloader.text">
		<div class="check__title">Выберите время доставки</div>

		<div class="step-2__times">
			<div class="step-2__time-shop-wrap" v-for="(shop, code) in basketData.items" :key="code">
				<div class="time-shop">
					<div class="img_shop" v-if="shop.PICTURE" @click="setCurrentShop(code)">
						<img :src="shop.PICTURE.src" />
					</div>
					<div class="time-info">
						<div class="shop_name">{{shop.NAME}}</div>
						<a href="javascript:" class="shop_time_selected" @click="setCurrentShop(code)" v-if="formatDate[code]">
							<span v-if="formatDate[code]['timeDateFormat']">
								{{formatDate[code]['timeDateFormat']}}<br/>
								{{formatDate[code]['timeFrom']}} &ndash; {{formatDate[code]['timeTo']}}
							</span>
							<span v-else>выбрать время</span>
						</a>
					</div>
				</div>
				<span class="error_time_valid" v-if="errorTime[code] && !formatDate[code]['timeDateFormat']">Выберите время доставки</span>
				<!--<div class="item_message" v-if="shop.SUM < 2000">
					Заказ на {{shop.SUM_FORMAT}}р. Добавьте товаров до 2000р и сэкономьте на доставке 300р
				</div>-->
			</div>
		</div>

		<div slot="order_actions">
			<button type="button" @click="prevStep" class="b-button b-button_back b-button_big">Назад</button>
			<button type="button" @click="nextStep" class="b-button b-button_green b-button_check b-button_big b-button_width">Дальше</button>
		</div>

		<transition name="custom-classes-transition" enter-active-class="animated slideInLeft" leave-active-class="animated slideOutLeft">
			<div class="calendar" v-show="currentShop">
				<div class="time-shop">
					<div class="img_shop" v-if="currentShop.PICTURE">
						<img :src="currentShop.PICTURE.src" />
					</div>
					<div class="time-info">
						<div class="shop_name">{{currentShop.NAME}}</div>
					</div>
				</div>
				<div class="calendar__body">
					<el-tabs type="card" v-model="activeDay">
						<el-tab-pane :name="'day_'+index" v-for="(day, index) in currentShop.CALENDAR" :key="'day_'+index">
							<span slot="label">{{day.NAME}}, {{day.FORMAT.DAY}}<br />{{day.FORMAT.MONTH_LOCALE}}</span>
							<div class="tab_item_body">
								<div :class="['row_item', {'no_active': timeLine.ACTIVE != 'Y' || timeLine.CLOSED_BY_ADMIN == 'Y'}]"
										v-for="timeLine in currentShop.CALENDAR[index]['TIMES']"
										:key="'line_'+index+timeLine.ID"
										@click="selectTime(timeLine, currentShop, day)">

									<div class="row_item__name">{{timeLine.NAME}}</div>
									<div class="row_item__price">{{timeLine.PRICE}} <i class="fa fa-rouble"></i></div>
									<div class="row_item__status">
										<span v-if="timeLine.ACTIVE != 'Y' || timeLine.CLOSED_BY_ADMIN == 'Y'">недоступно</span>
										<a href="javascript:" v-else>Выбрать</a>
									</div>

								</div>
								<!--<pre>{{currentShop.CALENDAR[index]}}</pre>-->
							</div>
						</el-tab-pane>
					</el-tabs>
				</div>
				<!--<pre>{{currentShop.CALENDAR}}</pre>-->
			</div>
		</transition>
	</div>
</template>
<script>
	import { mapGetters, mapActions } from 'vuex';
	import Map from 'Utilities/maps/Map';

	export default {
		data() {
			return {
				basket: {},
				currentShop: false,
				activeDay: 'day_0',
				formatDate: {},
				validStep: false,
				errorTime: {},
			}
		},
		methods: {
			nextStep(){
				this.validate();
				if(this.validStep)
					this.$store.commit('setStep', 3);

			},
			prevStep(){
				this.$store.commit('setStep', 1);
				const mapData = new Map({
					mainComponent: BX('#main_order_form')
				});
				this.$store.dispatch('loadMap', mapData);
			},
			...mapActions(['loadBasket']),

			setCurrentShop(code){
				this.currentShop = false;
				this.currentShop = this.basketData.items[code];
			},

			selectTime(timeLine, currentShop, day){
				if(timeLine.ACTIVE == 'Y' && timeLine.CLOSED_BY_ADMIN != 'Y'){
					let save = {
						shopCode: currentShop.CODE,
						shopId: currentShop.SHOP_ID,
						timeDateFormat: `${day.NAME}, ${day.FORMAT.DAY} ${day.FORMAT.MONTH_LOCALE}`,
						timeFrom: timeLine.TIME_FROM,
						timeTo: timeLine.TIME_TO,
						timestamp: day.TIMESTAMP,
						id: timeLine.ID,
						price: _.toInteger(timeLine.PRICE)
					};
					this.$store.commit('setDelivery', save);
					this.formatDate[currentShop.CODE] = save;
					this.currentShop = false;
					this.$nextTick(() => {
						this.validate();
					});
				}
			},
			validate(){
				this.validStep = true;
				_.forEach(this.basketData.items, (el, code) => {
					if(_.isEmpty(this.delivery[code])){
						this.validStep = false;
						this.errorTime[code] = true;
					}
				});
			}
		},
		watch: {
			basketData(data){
				if(data.hasOwnProperty('items')){
					_.forEach(data.items, (el, code) => {
						this.formatDate[code] = {};
						this.errorTime[code] = false;
						if(this.delivery.hasOwnProperty(code)){
							this.formatDate[code] = this.delivery[code];
						}
					});
				}
			}
		},
		created() {
			this.loadBasket();
		},
		computed: {
			...mapGetters([
				'preloader', 'basketData', 'delivery'
			])
		},
	}
</script>
<style lang="scss">
	$greyBorder: 1px solid #e5e5e5;
	$successColor: #34c263;
	$successColorText: #fff;

	.step-2{

		&__times {

		}

		&__time-shop-wrap {
			margin-bottom: 30px;

			&:hover .img_shop {
				box-shadow: 0 0 20px #c7c7c7;
				transition: 0.4s;
			}

			&:hover .shop_time_selected {
				color: $successColor;
				border-color: $successColor;
			}
		}


		.shop_time_selected {
			font-size: 12px;
			color: #373737;
			text-decoration: none;
			line-height: 16px;
		}

		.item_message {
			margin: 7px 0 25px 0;
			font-size: 12px;
			color: #898989;
			width: 250px;
			line-height: 20px;
		}
	}

	.time-info {
		display: flex;
		flex-direction: column;
	}

	.time-shop {
		display: flex;
	}

	.img_shop {
		width: 128px;
		height: 84px;
		display: flex;
		justify-content: center;
		align-items: center;
		border: $greyBorder;
		margin-right: 20px;
		cursor: pointer;


		img {
			width: 80%;
			height: auto;
		}
	}

	.shop_name {
		font-size: 24px;
		color: #000;
		margin-bottom: 10px;
	}

	.calendar {
		background: #fff;
		min-height: 595px;
		width: 850px;
		position: absolute;
		top: 0;
		left: -215%;

		.time-shop {
			padding: 25px 20px 20px;
			align-items: center;

			.img_shop {
				margin-right: 30px;
			}
		}

		.el-tabs__nav {
			.el-tabs__item {
				font-size: 13px;
				color: #373737;
				line-height: 18px;
				text-align: center;
				height: 65px;
				border: none;
				border-radius: 0;
				padding-top: 10px;
			}

		}

		.el-tabs--card>.el-tabs__header .el-tabs__nav {
			border: none;
			border-radius: 0;
			top: 1px;
			left: 15px;
		}
		.el-tabs--card>.el-tabs__header .el-tabs__item.is-active {
			border: $greyBorder;
			border-bottom: 1px solid #fff;
		}

		.el-tabs__nav-wrap {
			padding: 0 30px;
		}
		.el-tabs__header {
			padding: 0;
			margin-bottom: 0;
		}

		.el-tabs__nav-next, .el-tabs__nav-prev {
			height: 65px;
			font-size: 20px;
			display: flex;
			align-items: center;
			width: 25px;

			&:hover {
				border: $greyBorder;
				border-bottom: none;
			}
		}

		.tab_item_body {
			.row_item {
				padding: 20px 30px;
				border-bottom: $greyBorder;
				display: flex;

				&:hover {
					background: #f0f0f0;
					cursor: pointer;
				}

				&__name {
					flex: 10;
				}
				&__price {
					flex: 16;
				}
				&__status {
					flex: 10;
					text-align: right;
					font-weight: bold;
					font-size: 15px;
				}
				&__status, &__status a {
					color: #3abb4c;
					text-decoration: none;
				}
			}

			.row_item.no_active {
				opacity: 0.5;

				&:hover {
					background: transparent;
					cursor: default;
				}
			}
		}
	}

	.error_time_valid {
		color: #d1404a;
		font-size: 13px;
	}
</style>