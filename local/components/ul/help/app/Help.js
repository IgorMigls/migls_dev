import {Provider, connect} from 'react-redux';
import {HelperCtrl as Ctrl} from './controller';

class HelpApp extends React.Component {
	constructor(props) {
		super(props);

	}

	componentDidMount() {
		this.props.getSections();
	}

	render() {

		const Help = this.props.Help;

		if(Help.Sections.length == 0){
			return null;
		}

		let first = [];
		$.each(Help.Sections, (k, el) => {
			let elements = [];
			if(el.elements != undefined && el.elements.length > 0){

				elements = el.elements.map(elementItem => {
					return(
						<li className="acord__item-2">
							<span className="jsAcordTab">{elementItem.NAME}</span>
							<ul className="acord__sub-2 animated fadeInUp">
								<li className="acord__item-3">
									<span dangerouslySetInnerHTML={{__html: elementItem.DETAIL_TEXT}} />
								</li>
							</ul>
						</li>

					);
				});
			}

			first.push(
				<li className="acord__item" key={el.ID}>
					<a href="javascript:" className="acord__link">{el.NAME}</a>
					{
						elements.length > 0 ?
							<ul className="acord__sub animated fadeInUp">
									{elements}
							</ul>
							:
							false
					}
				</li>
				)
		});

		return (
			<div className="b-help-acord-wrapper">
				<div className="b-help-acord">
					<ul className="acord-main">
						{first}
					</ul>
				</div>
			</div>
		);
	}
}

export default connect(Ctrl.mapStateToProps, Ctrl.mapDispatchToProps)(HelpApp);