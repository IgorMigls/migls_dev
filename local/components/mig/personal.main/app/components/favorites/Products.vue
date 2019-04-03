<template>
	<div></div>
</template>

<script>
	import { mapActions, mapGetters } from 'vuex';
	
	export default {
		name: "",
		props: {},
		data() {
			return {}
		},
		methods: {
			...mapActions('favorite', [])
		},
		watch: {},
		computed: {},
		created() {
		},
		mounted() {

		},
		components: {},
	}
</script>

<style lang="scss"></style>