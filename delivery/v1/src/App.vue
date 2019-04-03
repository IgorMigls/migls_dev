<template>
	<!-- Don't drop "q-app" class -->
	<div id="q-app">
		<q-layout ref="layout" view="lhh LpR fFf" :left-class="{'bg-grey-2': true}">
			<q-toolbar slot="header" color="primary">
				<q-toolbar-title>{{titlePage}}</q-toolbar-title>
			</q-toolbar>
			<div v-if="userId !== null">
				<router-view />
			</div>
			
			
			<q-toolbar slot="footer">
				<q-tabs position="bottom" align="justify">
					<q-route-tab icon="home" to="/" exact slot="title" />
					<q-route-tab icon="add_shopping_cart" to="/complect" exact slot="title" />
					<q-route-tab icon="alarm_on" to="/delivery" exact slot="title" />
					<q-route-tab icon="favorite" to="/myDelivery" exact slot="title" />
				</q-tabs>
			</q-toolbar>
		</q-layout>
	</div>
</template>

<script>
	import {
		dom,
		event,
		openURL,
		QLayout,
		QToolbar,
		QToolbarTitle,
		QIcon,
		QTabs,
		QRouteTab
	} from 'quasar'
	
	import {mapActions, mapGetters} from 'vuex';
	export default {
		components: {
			QLayout,
			QToolbar,
			QToolbarTitle,
			QIcon,
			QTabs,
			QRouteTab
		},
		methods: {
			...mapActions([
				'isAuth'
			])
		},
		created(){
			this.isAuth();
		},
		computed: {
			...mapGetters([
				'userId', 'titlePage'
			])
		}
		
	}
	
</script>

<style lang="scss">
	body, html {
		max-width: 800px;
		margin: 0 auto;
	}
	#q-app, body {
		max-width: 800px;
		margin: 0 auto;
	}
	.q-loading > div {
		text-shadow: none;
	}
	.q-loading.bg-white {
		opacity: 0.5;
	}
	
	.layout-footer {
		.q-tabs {
			width: 100%;
		}
	}
</style>
