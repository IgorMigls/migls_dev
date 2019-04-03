<template>
	<div class="bs_basket_item" v-if="product" v-blur-element="product.CAN_BUY" v-loading="isLoading">
		<div class="bs_wrap_item">
			
			<div class="b-product__count b-ib">
				<div class="b-count__in">
					<button class="b-button" @click="getReplaces(product)">
						<i class="fas fa-sync-alt" aria-hidden="true" style="font-size: 90%"></i>&nbsp;Добавить замены
					</button>
				</div>
				<div class="b-count__in b-cont__in_cart">
					<button class="b-button" @click.prevent="showCommentForm = !showCommentForm">Добавить комментарий</button>
				</div>
			</div>
			
			<div class="bs_item_info">
				<div class="bs_item_img" v-if="product.PICTURE">
					<img :src="product.PICTURE.src" />
				</div>
				<div class="bs_product_item">
					<div class="bs_product_name">{{product.NAME}}</div>
					<div class="bs_product_actions">
						<div class="bs_product_price">{{sumFormat}} <span class="check-rub-cart">₽</span></div>
						<div class="bs_counter">
							<div class="b-product-preview__count b-product-preview__count_cart b-ib">
								<input type="text" @change="change" @blur="watchQuantity" class="b-product-preview__input" v-model="quantity" />
								<button type="button" @click="plus" class="b-button b-button_plus">+</button>
								<button type="button" @click="minus" class="b-button b-button_minus">–</button>
								<span class="input-measure">{{product.MEASURE_SHORT_NAME}}</span>
							</div>
						</div>
						<div class="bs_product_delete">
							<button type="button" @click="deleteItem(product)" class="b-button b-button__del false">Удалить</button>
						</div>
					</div>
					
					<transition name="custom-classes-transition"
							enter-active-class="animated fadeInLeft"
							leave-active-class="animated fadeOutRight">
						
						<div v-if="showCommentForm">
							<div class="b-product-textarea">
								<textarea name="comment" maxlength="100" v-model="comment" class="form__textarea form__textarea_cart"></textarea>
							</div>
							<div class="b-ib cart-com-buttons">
								<button type="button" @click="saveComment" class="b-button b-button_green b-button_small">Сохранить</button>
								<button type="button" @click="cancelComment" class="b-button b-button_green b-button_small b-button_grey">Отменить</button>
							</div>
						</div>
						<div class="cart__comments" v-if="!showCommentForm && product.COMMENT">
							<span class="cart__comments_span">
								Комментарий: <i class="fa fa-pencil js-toggle-class" @click="showCommentForm = true"></i>
							</span>
							<span class="cart__comments_span text">{{product.COMMENT}}</span>
						</div>
					
					</transition>
				</div>
			</div>
			<div class="bs_product_item__replace" v-if="productItem.REPLACE && productItem.REPLACE instanceof Object">
				<span class="bs_item_img">Замена:</span>
				<span class="bs_product_item">{{productItem.REPLACE.NAME}}</span>
				<span class="replace_delete">
					<button type="button" @click="delReplace" class="b-button b-button__del false">Удалить</button>
				</span>
			</div>
		</div>
	
	</div>
</template>
<script>
	import {Rest} from '../store/actions';
	import {mapActions, mapGetters} from 'vuex';
	
	export default {
		props: {
			productItem: {type: Object, required: true}
		},
		data() {
			return {
				product: this.productItem,
				showCommentForm: false,
				comment: this.productItem.COMMENT || '',
				quantity: _.toNumber(this.productItem.QUANTITY),
				isLoading: false,
				replace: this.productItem.REPLACE || false,
			}
		},
		methods: {
			async saveComment() {
				try{
					let commentRes = await this.http.saveComment({
						id: this.productItem.ID,
						comment: this.comment
					});
					if(commentRes.data.DATA === true){
						this.showCommentForm = false;
						this.product.COMMENT = this.comment;
					}
				} catch (Err){
					console.info(Err);
				}
				
//				this.$emit('save-comment', this.product);
			},
			
			cancelComment(){
				this.showCommentForm = false;
				
				if(!this.product.COMMENT)
					this.comment = '';
			},
			
			plus(){
				this.quantity = _.toNumber(this.quantity) + _.toNumber(this.productItem.MEASURE_RATIO);
			},
			
			minus(){
				if(this.quantity > _.toNumber(this.productItem.MEASURE_RATIO)){
					this.quantity = _.toNumber(this.quantity) - _.toNumber(this.productItem.MEASURE_RATIO);
				}
			},

			watchQuantity(ev){
				let val = _.toNumber(ev.target.value);
				let ratio = _.toNumber(this.productItem.MEASURE_RATIO);

				if(ev.target.value.length === 0){
					this.quantity = 0;
				} else if(val === 0){
					this.quantity = ratio;
				} else if ((val / ratio) % 1 != 0 ) {
				    this.quantity = Math.ceil(val / ratio) * ratio;
				}

                this.quantity = val;
			},
			change(ev){
				if(ev.target.value.length === 0){
					this.quantity = this.productItem.MEASURE_RATIO;
				}
			},
			
			delReplace(){
				this.deleteReplace(this.product);
				// this.http.delReplace({id: this.product.ID}).then(res => {
				// 	if(res.data.DATA === true){
				// 		this.product['REPLACE'] = false;
				// 	}
				// })
			},
			...mapActions([
				'quantityUpdate', 'deleteItem', 'getReplaces', 'deleteReplace'
			]),
		},
		computed: {
			sumFormat(){
				let format = BX.util.number_format(this.sum, 2, '.', ' ');
				this.product.SUM_FORMAT = format;
				
				return format;
			},
			sum(){
			    //console.log(this.product.QUANTITY, this.product.PRICE);
				this.product.SUM = this.product.QUANTITY * _.toNumber(this.product.PRICE);
				return this.product.SUM
			},
			...mapGetters(['currentReplaceBasket'])
		},
		watch: {
			quantity(val){
				if(_.toNumber(this.product.QUANTITY)  === _.toNumber(val)){
					return ;
				}
				
				this.product.QUANTITY = val;
				this.isLoading = true;
				this.quantityUpdate(this.product);
				this.isLoading = false;

				window.MigBus.$emit('on-quantity-' + this.product.PRODUCT_ID, this.product);
			},

			productItem(data){
				if(data.QUANTITY !== this.quantity){
					this.quantity = data.QUANTITY;
				}
			}
		},
		created() {
			this.http = Rest;
		},
		components: {},
		mounted(){
			window.MigBus.$on('deleteFormBasket', (productId) => {
				if(this.product.PRODUCT_ID === productId){
					this.deleteItem(this.product);
				}
			});
		}
	}
</script>