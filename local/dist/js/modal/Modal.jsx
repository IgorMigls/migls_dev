define(function (require) {
	'use strict';
	var React = require('react');
	var ReactDOM = require('dom');

	var Modal = function () {

		this.Window = React.createClass({
			$container: $('.ab_modal_wrap'),

			hideOverlay: function () {
				$('.ab_modal_wrap').find('.ab_modal_overlay').fadeOut(200);
			},

			open: function () {
				$('.ab_modal_wrap').removeClass('fadeOut').addClass('fadeIn');
			},

			close: function () {
				$('.ab_modal_wrap')
					.removeClass('fadeIn')
					.addClass('fadeOut');
				this.hideOverlay();
			},

			componentDidMount: function () {
				if(this.props.width){
					$('.ab_modal_content').width(this.props.width);
				}

				var $content = $('.ab_modal_wrap .ab_modal_content'),
					width = $content.width();

				$content.css({'left': '50%', 'margin-left':'-'+ width/2 +'px'});

			},

		    render: function () {
		        return(
		        	<div className="ab_modal_wrap animate fadeOut" ref="modal">
						<div className="ab_modal_content">
							<div className="b-popup-recovery">
								<div className="b-popup-cart__head">
									<div className="b-products-block-top b-ib bg_reverse">
										<div className="cart__img-wrapper">
											<div className="cart__prod-title">
												<div className="icon-g"></div>
											</div>
											<div className="recovery__title">Сохранить адрес</div>
										</div>
									</div>
								</div>
								<div className="accepted__content">
									<div className="lk__add-address">
										<h2>TEST!</h2>
									</div>
								</div>
							</div>
						</div>
						<div className="ab_modal_overlay" onClick={this.close}/>
					</div>
				);
		    }
		});

		this.render = function () {
			return ReactDOM.render(<this.Window width="400" />, BX('modal_main'));
		};

		this.destroy = function () {
			ReactDOM.unmountComponentAtNode(BX('modal_main'));
		};
	};

	return new Modal();
});