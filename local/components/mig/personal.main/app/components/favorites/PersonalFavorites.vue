<template>
	<div class="favorites" v-loading="loader">
		<h1 v-if="!activeList">Избранное</h1>
		<h1 v-else><a href="javascript:" @click="fetchProducts(false)">Избранное</a> / <small>{{ sectionActiveName }}</small></h1>
		<p class="favorites-desc">
			Добавляйте понравившиеся вам товары в избранное.
			<span>Создавайте списки, чтобы облегчить поиск товаров в избранном.</span>
		</p>
		
		<div class="favorites-create_list">
			<h2>Создать новый список</h2>
			<div class="create_list-form_list">
				<div class="create_list-title">Название</div>
				<div class="create_list-input">
					<input type="text" v-model="sectionName" placeholder="Название" class="form__input form__input_middle form__input_m" />
				</div>
				<div class="create_list-btn">
					<button type="button" class="b-button b-button_green b-button_big" @click="saveSection">Создать список</button>
				</div>
			</div>
			
			<ul class="favorites-list">
				<li class="lk__lists__item" v-for="item in sections" :key="item.ID">
					<span class="custom-num">{{item.CNT_PRODUCTS}}</span>
					<a href="javascript:" class="lk__lists__link ng-binding" @click="getItemsByList(item.ID)">
						{{item.NAME}}
					</a>
					<span class="del_section" @click="deleteSection(item)"><i class="el-icon-circle-close"></i></span>
				</li>
			</ul>
		</div>
		
		<product-list />
	</div>
</template>

<script>
	import {mapActions, mapGetters} from 'vuex';
	import ProductList from "./ProductList";
	
	export default {
		name: "personal-favorites",
		props: {},
		data() {
			return {
				sectionName: ''
			}
		},
		methods: {
			...mapActions('favorite', [
				'fetchSections', 'addSection', 'deleteSection', 'fetchProducts'
			]),
			
			saveSection() {
				this.addSection({ NAME: this.sectionName }).then(res => {
					if(res.data.DATA !== null)
						this.sectionName = '';
				});
			},
			
			getItemsByList(listId) {
				this.fetchProducts(listId);
				this.$store.commit('favorite/activeList', listId);
			},
		},
		watch: {},
		created() {
			this.fetchSections();
		},
		beforeUpdate() {},
		components: {
			ProductList
		},
		computed: {
			...mapGetters('favorite', [
				'sections', 'loader', 'productItems', 'activeList'
			]),
			
			sectionActiveName() {
				let index = _.findIndex(this.sections, (o) => {
					return o.ID == this.activeList;
				});
				if(index !== -1){
					return this.sections[index]['NAME'];
				}
				return 'Вне списка';
			}
		},
		mounted() {}
	}
</script>

<style scoped lang="scss">
	.favorites {
		padding-top: 42px;
		padding-bottom: 30px;
		
		h1 {
			font-size: 30px;
			font-family: 'HelveticaNeueCyr-Thin';
			padding-bottom: 20px;
		}
		
		&-desc {
			font-size: 1.1rem;
			
			span {
				font-size: 0.9rem;
				color: #898989;
				display: block;
			}
		}
		
		&-create_list {
			margin-top: 40px;
		}
		
		.create_list-form_list {
			display: flex;
			align-items: center;
			padding-top: 20px;
		}
		.create_list-title {
			margin-right: 20px;
		}
		
		.favorites-list {
			padding: 0;
			margin: 20px 0 0 0;
			
			.lk__lists__item .del_section {
				display: none;
				color: rgba(176, 0, 0, 0.74);
				margin-left: 5px;
				cursor: pointer;
			}
			
			.lk__lists__item:hover > .del_section {
				display: inline-block;
				color: rgba(0, 0, 0, 0.6);
			}
		}
	}
</style>