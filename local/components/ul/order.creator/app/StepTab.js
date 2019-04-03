/**
 * Created by dremin_s on 30.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
// import cn from 'classnames';

class StepTab extends React.Component {

	constructor(props) {
		super(props);

	}

	prevStep(curStep) {
		// this.props.prevStep(curStep);
		if(this.props.active > curStep){
			this.props.prevStep(curStep);
		}
		// else if(this.props.active <= curStep){
		//
		// }

	}

	compileTabs(active = 1){
		let tabs = [], limit = 3;
		for(let i = 1; i <= limit; i++){
			let activeClass = '';
			if(i === active){
				activeClass = 'active';
			}
			if(i < active){
				activeClass = 'active active2'
			}

			if(i === limit){
				activeClass += ' dot_end';
			}

			tabs.push(
				<li className={activeClass} onClick={this.prevStep.bind(this, i)}>
					<div className="dot" />
					<div className="title">{i}</div>
				</li>
			);
		}

		return tabs;
	}

	render() {
		return (
			<div className="order__tabs step">
				<ul>{this.compileTabs(this.props.active)}</ul>
			</div>
		);
	}
}

export default StepTab;