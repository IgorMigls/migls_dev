<template>
	<transition name="custom-classes-transition"
			enter-active-class="animated slideInRight"
			leave-active-class="animated slideOutRight">
		
		<div>
			<el-tabs v-model="activeName">
				<el-tab-pane name="main">
					<span slot="label">Купить({{getSize(buyItems)}})</span>
					<product-list></product-list>
				</el-tab-pane>
				<el-tab-pane name="editing">
					<span slot="label">Найдено({{getSize(foundList)}})</span>
					<founded></founded>
				</el-tab-pane>
				<el-tab-pane name="replace">
					<span slot="label">Рассмотреть({{getSize(replaceTab)}})</span>
					<replace-candidate></replace-candidate>
				</el-tab-pane>
			</el-tabs>
			
			<!--<div class="btn-groups">-->
				<!--<router-link :to="'/edit/' + $route.params.id + '/products'" class="btn btn-primary">Назад</router-link>-->
			<!--</div>-->
		</div>
	</transition>
</template>

<script>
	import { mapActions, mapGetters } from 'vuex';
	import productList from './productList';
	import founded from './founded';
	import replaceCandidate from './replaceCandidate';
	
	export default {
		props: {
			show: {type: Boolean}
		},
		
		name: "product-tabs",
		
		data() {
			return {
				activeName: 'main',
				activeComponent: 'list'
			}
		},
		
		methods: {
			...mapActions([
				'getOrder', 'lockedOrder', 'deliveryOrder', 'delProduct',
				'searchReplaceItems'
			]),
			
			back(){
				this.$emit('close-products');
			},
			getSize(obj){
				return _.size(obj);
			}
		},
		
		computed: {
			...mapGetters([
				'detail', 'replacesItems', 'buyItems', 'foundList', 'replaceTab'
			]),
		},
		
		components: {
			productList,
			founded,
			replaceCandidate
		},
		
		created(){
			if(_.isEmpty(this.detail)){
				this.getOrder(this.$route.params.id);
			}
		}
	}
</script>