<template>
	<transition name="custom-classes-transition"
			enter-active-class="animated zoomIn"
			leave-active-class="animated zoomOut">
		<span :class="styleWrap" v-show="show">
			<span :class="styleMessage"><slot></slot></span>
		</span>
	</transition>
</template>
<script>
	export default {
		props: {
			classWrap: {type: String},
			classMsg:{type: String},
			show: {type: Boolean, default: () => {return false}, required: false},
		},
		data() {
			return {}
		},
		computed: {
			styleWrap(){
				return [this.classWrap, 'error_ui_field'];
			},
			styleMessage(){
				return [this.classMsg, 'error_ui_message'];
			}
		},
	}
</script>
<style lang="sass">
	.error_ui_field
		background: #f9543e
		padding: 4px 10px
		border-radius: 0 0 4px 4px
		display: inline-block
		animation-duration: 0.4s
		
		.error_ui_message
			color: #fff
			font-size: 0.89em
</style>