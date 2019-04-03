/**
 * Created by dremin_s on 16.02.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import {Route, IndexRoute, browserHistory} from 'react-router';
import StepOne from './step/StepOne';
import StepTwo from './step/StepTwo';
import StepThree from './step/StepThree';
import StepTabs from './step/StepTabs';

const curPage = '/personal/order/make/';

class AppRoute extends React.Component {
	constructor(props) {
		super(props);

	}

	render() {
		return <div>{this.props.children}</div>;
	}
}

export default (
	<Route path={curPage} component={AppRoute}>
		<IndexRoute component={StepOne} />
		<Roote path={curPage} component={StepTabs}>
			<Route path="/step1" component={StepOne} />
			<Route path="/step2" component={StepTwo} />
			<Route path="/step2" component={StepThree} />
		</Roote>
	</Route>
);