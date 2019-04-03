<template>
	
	<transition name="custom-classes-transition"
			enter-active-class="animated zoomIn"
			leave-active-class="animated zoomOut">
		
		<div class="replace_wrap" v-show="items !== false && replaces !== undefined" v-loading="replaceLoader.show">
			<div class="replace_wrap__head">
				<span>Выберите замену</span>
				<button class="b-button check__back" type="button" @click="denyReplace">Запретить замену</button>
				<button class="b-popup__close" type="button" @click="closeReplace"></button>
			</div>
			<div class="replace_wrap__search">
				<input v-model="searchQuery" type="text" placeholder="Поиск" class="replace_wrap__input" />
			</div>
			<scroll-bar class="replace_wrap__products" v-if="itemsExist">
				<div class="product_item" v-for="product in items" :key="'rep_'+product.ID">
					<div class="product_item__img" v-if="product.PICTURE">
						<img :src="product.PICTURE.src" />
					</div>
					<div class="product_item__name">{{product.NAME}}</div>
					<div class="product_item__price">
						<span>{{product.PRICE_FORMAT}}&nbsp;<span class="check-rub-cart">₽</span> /{{product.MEASURE_SHORT_NAME}}</span>
						<button type="button" @click="addReplace(product)" class="b-button b-button_green">Заменить</button>
					</div>
				</div>
			</scroll-bar>
			<div class="replace_wrap__not-found" v-if="!itemsExist && replaceLoader.loaded">
				-- Товары не найдены --
			</div>
		</div>
		
	</transition>
	
</template>
<script>
	import ScrollBar from 'ScrollBar';
	import {mapActions, mapGetters} from 'vuex';
	
	export default {
		
		data() {
			return {
				searchQuery: '',
				items: false,
			}
		},
		methods: {
			...mapActions([
				'searchReplace', 'saveReplace'
			]),
			
			closeReplace(){
				this.items = false;
				// this.$store.commit('currentReplaceBasket', false);
			},
			
			getReplaceItems:_.debounce(function () {
				this.searchReplace(_.escape(this.searchQuery));
			}, 500),
			
			addReplace(product){
				this.saveReplace(product);
				this.closeReplace();
			},
			
			denyReplace(){
				this.saveReplace({NAME: 'Запрещено', DENY: true});
				this.closeReplace();
			}
		},
		watch: {
			searchQuery(value){
				if(_.size(value) > 3){
					this.getReplaceItems();
					this.items = this.searchResult;
				} else {
					this.items = this.replaces;
				}
			},
			replaces(items){
				this.items = items;
			},
			searchResult(items){
				this.items = items;
			}
		},
		created() {
			this.items = this.replaces;
		},
		components: { ScrollBar },
		computed: {
			...mapGetters([
				'replaces',
				'searchResult',
				'currentReplaceBasket',
				'replaceLoader'
			]),
			
			itemsExist(){
				return _.size(this.items) > 0;
			}
		}
	}
</script>
<style scoped lang="scss">
	.replace_wrap__head {
		.b-popup__close {
			right: auto;
			top: auto;
			margin-left: 208px;
		}
	}
	
	.product_item__price {
		.check-rub-cart {
			font-size: 16px;
		}
	}
	
</style>
