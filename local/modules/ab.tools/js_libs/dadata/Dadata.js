import axios from '../ajaxService';
import classNames from 'classnames';

const Ajax = new axios({
	baseURL: '/rest/UL/Suggestions'
});

class Dadata extends React.Component{
 	constructor(props){
 		super(props);

	}

	componentDidMount() {
		let id = this.props.id;
		$('#'+id).autocomplete({
			source: (request, response) => {
				let post = {query: request.term, count: 10};
				Ajax.post('/getAddress', post).then(result => {
					let sResult = [];
					if (result.data.STATUS = 1 && is.array(result.data.DATA.suggestions)) {
						$.each(result.data.DATA.suggestions, function (k, arItem) {
							sResult.push(arItem);
						});
						response(sResult.length === 1 && sResult[0].length === 0 ? [] : sResult);

						$('.ui-autocomplete').css({'z-index': '1080'});
					}
				});
			}
		});
	}

    render () {

 		return(
 			<input {...this.props}/>
		)
    }
}

export default Dadata;