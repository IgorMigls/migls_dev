<div class="personal_orders">
	<div class="lk__title lk__title_reset">Ваши заказы</div>
	<div class="lk__orders">
		<div class="lk__order" ng-repeat="itemOrder in Orders">
			<div class="lk__history__col1">
				<a href="#/orders/{{itemOrder.ID}}/catalog/" class="history__order1">
					Заказ №{{itemOrder.ACCOUNT_NUMBER}}, {{itemOrder.ORDER_DATE}}
				</a>
				<span class="history__order2">{{itemOrder.ADDRESS}}</span>
			</div>
			<div class="lk__history__col2">
				<div class="span history__total">Сумма </div>
				<div class="span history__sub"><nobr>{{itemOrder.PRICE_FORMAT}}&#8381;</nobr></div>
			</div>
<!--			<div class="lk__history__col3">-->
<!--				{{itemOrder.STATUS_NAME}}-->
<!--			</div>-->
		</div>
	</div>
</div>