/**
 * Created by Grandmaster.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import { connect, Provider } from "react-redux";
import Store from "./Store";
import { mapDispatchToProps, mapStateToProps } from "./Controller";
import StepTab from './StepTab';
import StepOne from './Parts/StepOne';
import StepTwo from './Parts/StepTwo';
import StepThree from './Parts/StepThree';

class OrderComponent extends React.Component {
	constructor(props) {
		super(props);

		this.setActiveStep = this.setActiveStep.bind(this);
	}

	componentWillReceiveProps(nextProps){

	}

	componentDidMount () {
		if(this.props.Data.hasOwnProperty('testMode')){
			setTimeout(() => {
				this.props.testDataInsert();
			}, 300);
		}
	}

	setActiveStep(step){
		/*let allowSwitch = false;
		switch (step){
			case 1:
				if(this.props.step1.isValid === true || (this.props.step1.isValid === true && step === 2)){
					allowSwitch = true;
				}
				if(this.props.step2.active === true && step === 1){
					allowSwitch = true;
				}
				break;
			case 2:
				if(this.props.step2.isValid === true || this.props.step1.isValid === true){
					allowSwitch = true;
				}
				if(this.props.step3.active === true && step === 2){
					allowSwitch = true;
				}
				break;
			case 3:
				if(this.props.step2.isValid === true){
					allowSwitch = true;
				}
				break;
		}
		if(allowSwitch === true){
			this.props.prevStep(step);
		}*/

		this.props.prevStep(step);
	}

	render() {

		const {Data} = this.props;

		if(is.empty(Data))
			return null;

		return (
			<div className="b-popup-check">
				{!this.props.step3.hasOwnProperty('order') && <StepTab active={Data.activeStep} prevStep={this.setActiveStep} />}
				<StepOne/>
				{this.props.step2.active === true &&
					<StepTwo/>
				}
				<StepThree/>
			</div>
		);
	}
}

const OrderComponentWrap = connect(mapStateToProps, mapDispatchToProps)(OrderComponent);

$(function () {
	ReactDOM.render(<Provider store={Store()}><OrderComponentWrap /></Provider>, BX('order_creator'));
});