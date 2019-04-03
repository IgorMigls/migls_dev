<template>
	<div class="b-lk-wrapper">
		<div class="b-lk__content">
			<div class="lk__col1">
				<div class="lk__title">Личный кабинет</div>
				<div class="lk__menu">
					<router-link to="/profile" class="lk__link"><span class="lk__item"></span>Профиль</router-link>
					<router-link to="/address" class="lk__link"><span class="lk__item"></span>Адреса</router-link>
					<router-link to="/coupons" class="lk__link"><span class="lk__item"></span>Скидочные купоны</router-link>
					<router-link to="/orders" class="lk__link"><span class="lk__item"></span>История заказов</router-link>
					<router-link to="/favorite" class="lk__link"><span class="lk__item"></span>Избранное</router-link>
				</div>
			</div>
			<div class="lk__col2">
				<router-view />
				<div id="ya_maps_lk"></div>
			</div>
		</div>
	</div>
</template>

<script>
	import {mapActions, mapGetters} from 'vuex';
	
	export default {
		name: "personal-main",
		props: {},
		data() {
			return {}
		},
		methods: {
			...mapActions('address', [
				'loadMap'
			])
		},
		watch: {},
		created() {
		},
		beforeUpdate() {
		},
		components: {},
		computed: {},
		mounted() {
			this.loadMap();
		}
	}
</script>
<style lang="scss">
	
	.b-lk__content {
		
		display: flex;
		
		.lk__col1 {
			width: 25%;
		}
		.lk__col2 {
			padding-left: 25px;
			width: 75%;
		}
		
		.lk__title {
			font-size: 30px;
		}
	}
	
	
	.address_edit_wrap .modal_content {
		position: fixed;
		left: calc(50% - 20%);
	}
</style>