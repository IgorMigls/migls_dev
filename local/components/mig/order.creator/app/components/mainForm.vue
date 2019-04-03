<template>
	<div class="order_app">
		<div class="order_app__steps">
			<el-steps :active="activeStep" finish-status="finish">
				<el-step></el-step>
				<el-step></el-step>
				<el-step></el-step>
			</el-steps>
		</div>
		<div class="order_app__body">
			<slot></slot>
			<div class="order_app__actions">
				<slot name="order_actions"></slot>
			</div>
		</div>
	</div>
</template>
<script>
	import {mapGetters} from 'vuex';
	
	export default {
		props: {
			user: {type: Number, required: true}
		},
		data() {
			return {}
		},
		beforeUpdate() {
		},
		created() {
//			console.info(this.activeComponent);
		},
		methods: {
		
		},
		components: {},
		computed: {
			...mapGetters([
				'activeStep', 'activeComponent'
			])
		}
	}

</script>
<style lang="scss">
	$borderStep: #ddd;
	$textStep: #999;
	$successColor: #34c263;
	$successColorText: #fff;
	
	.order_app {
		min-height: 450px;
		
		&__steps{
			margin-bottom: 25px;
			
			.el-step__head.is-process {
				color: $textStep;
				border-color: $borderStep;
			}
			
			.el-step.is-horizontal .el-step__line {
				height: 4px;
				background: $borderStep;
			}
			.el-step__line-inner {
				border-color: $successColor;
				background: $successColor;
				height: 4px;
			}
			.el-step__head.is-finish {
				color: $successColor;
				border-color: $successColor;
				
				.el-step__icon {
					background: $successColor;
					color: $successColorText;
				}
			}
			
			.el-step__icon {
				height: 30px;
				width: 30px;
				
				&.is-text {
					top: -2px;
				}
			}
			
			
			
		}
		
		&__actions {
		
		}
	}
</style>