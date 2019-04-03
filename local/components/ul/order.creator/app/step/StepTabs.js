/**
 * Created by dremin_s on 16.02.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import {Tabs, TabItem} from 'UI/Tabs';
import StepOne from './StepOne';
import StepTwo from './StepTwo';
import StepThree from './StepThree';
import {connect} from 'react-redux';
import Control from '../Controller';

class StepTabs extends React.Component {

	constructor(props) {
		super(props);

	}

	render() {
		return (
			<div>
				<Tabs id="order_steps" classTabWrap="order__tabs">
					<TabItem title="Шаг 1" classLink="step">
						<StepOne />
					</TabItem>
					<TabItem title="Шаг 2" classLink="step">
						<StepTwo/>
					</TabItem>
					<TabItem title="Шаг 3" classLink="step">
						<StepThree/>
					</TabItem>
				</Tabs>
			</div>
		);
	}
}

export default connect(Control.mapStateToProps, Control.mapDispatchToProps)(StepTabs);
