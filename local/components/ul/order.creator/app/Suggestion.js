/**
 * Created by dremin_s on 31.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import { Field } from 'UIForm';
class Suggestion extends React.Component{
	constructor(props){
		super(props);

		this.state = {};

		this.change = this.change.bind(this);
	}

	static defaultProps = {
		onSelected: (ui) => {},
		defaultValue: ''
	};

	componentDidMount () {
		let $node = $(ReactDOM.findDOMNode(this)).find('input');

		$node.autocomplete({
			source: (request, response)  => {

				if(this.props.onProcess instanceof Function){
					this.props.onProcess(request, response, $node);
				}
			},
			minLength: 3,
			select: (event, ui) => {
				if(this.props.onSelected instanceof Function){
					this.props.onSelected(ui, event);
					this.setState({...this.state, value: ui.item.value});
				}
			}
		});
	}

	change(data){
		this.setState(data);
	}

	render(){
		return (
			<Field.String {...this.props} onChange={this.change} value={this.state.value} defaultValue={this.props.defaultValue}/>
		);
	}
}

export default Suggestion;