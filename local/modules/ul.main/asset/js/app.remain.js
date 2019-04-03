/**
 * Created by dremin_s on 01.02.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

import Preloader from 'preloader/Preloader';
import RestService from 'preloader/RestService'
import {SelectField} from 'form/Fields';

const Ajax = new RestService({
	baseURL: '/actions/admin/remain'
});

class RemainMain extends React.Component{
	constructor(props){
		super(props);

		this.state = {
			fileList: [
				{label: ' - Выбрать файл - ', id: null}
			],
			processFiles : []
		};
		this.currentFile = {};

		this.setFile = this.setFile.bind(this);
		this.startImport = this.startImport.bind(this);
	}

	setFile (arFile) {
		this.currentFile = arFile;
	}

	startImport(){
		Ajax.post('/addFileToQueue', {file: this.currentFile.id}).then(result => {
			if(result.data.STATUS == 1){
				swal('','Файл добавлен в очередь импорта.', 'success');
				this.getProcessFiles();
			}
		});
	}

	getProcessFiles(){
		Ajax.get('/getProcessFiles').then(res => {
			if(res.data.STATUS == 1){
				this.setState({processFiles: res.data.DATA});
			}
		});
	}

	componentDidMount () {
		Ajax.get('/getFileList').then(res => {
			if(res.data.STATUS == 1){
				let files = this.state.fileList;
				res.data.DATA.forEach((el, k) => {
					files.push(el);
				});

				this.setState({fileList: files});
			}
		});

		this.getProcessFiles();
	}

	render(){

		return (
			<div className="remail_import_wrap">

				{this.state.processFiles.length == 0 &&
					<div>
						<label>Выбрать файл:</label>&nbsp;&nbsp;
						<SelectField values={this.state.fileList} onChange={this.setFile} />&nbsp;&nbsp;
						<input type="button" className="adm-btn-save" value="Загрузить" onClick={this.startImport} />
					</div>
				}

				{this.state.processFiles.length > 0 &&
					<table className="adm-list-table">
						<thead>
						<tr className="adm-list-table-header">
							<td className="adm-list-table-cell adm-list-table-cell-sort">
								<div className="adm-list-table-cell-inner">ID</div>
							</td>
							<td className="adm-list-table-cell adm-list-table-cell-sort">
								<div className="adm-list-table-cell-inner">Файл</div>
							</td>
							<td className="adm-list-table-cell adm-list-table-cell-sort">
								<div className="adm-list-table-cell-inner">Магазин</div>
							</td>
							<td className="adm-list-table-cell adm-list-table-cell-sort">
								<div className="adm-list-table-cell-inner">Начало импорта</div>
							</td>
							<td className="adm-list-table-cell adm-list-table-cell-sort">
								<div className="adm-list-table-cell-inner">Статус</div>
							</td>
						</tr>
						</thead>
						<tbody>
						{this.state.processFiles.map((el) => {
							return (
								<tr className="adm-list-table-cell adm-list-table-popup-block">
									<td className="adm-list-table-cell">{el.ID}</td>
									<td className="adm-list-table-cell">{el.FILE}</td>
									<td className="adm-list-table-cell">
										{el.SHOP_CITY} - {el.SHOP_NAME}[{el.SHOP_ID}]
									</td>
									<td className="adm-list-table-cell">{el.LAST_IMPORT}</td>
									<td className="adm-list-table-cell">{el.IN_PROCESS === 'Y' ? 'В процессе' : 'Завершен'}</td>
								</tr>
							);
						})}
						</tbody>
					</table>
				}

			</div>
		);
	}
}


$(function () {
	ReactDOM.render(<RemainMain/>, BX('remain_file_import'));
});