<template>
	<div class="form_wrap">
		<form method="post" @submit.prevent="createComponent">
			<div class="form_row">
				<label class="form_title">Папка:</label>
				<div class="form_field">
					<el-select v-model="form.folder" @change="fetchNamespace" placeholder="Местоположение компонента">
						<el-option
								v-for="item in folderList"
								:key="item.value"
								:label="item.label"
								:value="item.value">
						</el-option>
					</el-select>
				</div>
			</div>
			
			<div class="form_row">
				<label class="form_title"><span class="star">*</span> Пространство имен: </label>
				<div class="form_field">
					<el-select v-model="form.namespace" placeholder="Пространство имен компонента"
							:loading="namespaceLoading" loading-text="Загрузка..."
							data-vv-value-path="innerValue" data-vv-name="namespace" v-validate="'required'">
						<el-option
								v-for="value in namespaceList"
								:key="value"
								:label="value"
								:value="value">
						</el-option>
					</el-select>
					<error-field :show="errors.has('namespace')">Выберите пространство имен компонента</error-field>
				</div>
			</div>
			
			<div class="form_row" v-if="form.namespace == 'create_new'">
				<label class="form_title">Создать новое:</label>
				<div class="form_field">
					<el-input v-model="form.newNamespace"></el-input>
				</div>
			</div>
			
			<div class="form_row">
				<label class="form_title"><span class="star">*</span> Название компонента:</label>
				<div class="form_field">
					<el-input data-vv-value-path="innerValue" v-validate="'regex:^[0-9a-z_.]+$|required'"
							data-vv-name="componentName" v-model="form.componentName"></el-input>
					<error-field :show="errors.has('componentName')">Допустимо латинсике строчные буквы и символы "_."</error-field>
				</div>
			</div>
			
			
			<div class="form_row">
				<label class="form_title"><span class="star">*</span> Класс компонента:</label>
				<div class="form_field">
					<el-input v-model="form.componentClass" placeholder="\Esd\Main\Components\MyClass" name="componentClass"
							data-vv-value-path="innerValue" v-validate="validateClassRule" data-vv-name="componentClass"></el-input>
					<error-field :show="errors.has('componentClass')">Допустимо латинсике буквы и символы _\</error-field>
				</div>
			</div>
			
			<div class="form_row">
				<label class="form_title">Создать бызовые js-скрипты:</label>
				<div class="form_field">
					<el-switch v-model="form.crateScripts" on-color="#86ad00" off-color="#bfcbd9"></el-switch>
				</div>
			</div>
			
			<div class="form_row">
				<label class="form_title"></label>
				<div class="form_field">
					<input type="submit" class="adm-btn-save" value="Создать компонент" />&nbsp;&nbsp;
					<a href="javascript:" @click="clearFields">Отмена</a>
				</div>
			</div>
		</form>
	</div>
</template>
<script>
	import actions from '../actions';
	import ErrorField from 'Utilities/ui/ErrorField.vue';
	import swal from 'sweetalert2';
	import 'sweetalert2/dist/sweetalert2.min.css';

	const defaultState = {
		folder: 'local',
		namespace: 'create_new',
		newNamespace: '',
		componentClass: '',
		crateScripts: false,
		componentName: ''
	};

	export default {
		data() {
			return {
				folderList: [
					{label: 'local', value: 'local'},
					{label: 'bitrix', value: 'bitrix'},
				],
				namespaceList: ['create_new'],
				form: {...defaultState},
				namespaceLoading: false,
				validateClassRule: {rules: {
					regex: /^[0-9a-zA-Z\\_]+$/,
					required: true
				}}
			}
		},
		methods: {
			fetchNamespace() {
				this.form.namespace = '';
				this.namespaceLoading = true;
				this.http.getNamespace({folder: this.form.folder}).then(res => {
					if (res.data.DATA !== null) {
						this.namespaceList = _.concat(this.namespaceList, res.data.DATA);
					}
					this.namespaceLoading = false;
				});
			},

			async createComponent() {
				try {
					if(await this.validate() === true){
						this.http.createComponent(this.form).then(res => {
							if(typeof res.data.DATA  === 'string'){
								swal('', 'Компонент '+ res.data.DATA +' создан', 'success');
								this.clearFields();
							}
						});
					}
				}catch (err){
					console.error(err.msg);
				}
			},

			async validate(){
				return await this.$validator.validateAll();
			},

			clearFields() {
				this.form = {...defaultState};
			}
		},
		watch: {},
		created() {
			this.http = this.$resource('/', {sessid: BX.bitrix_sessid()}, actions);
			this.fetchNamespace();
		},
		beforeUpdate() {
		},
		components: {
			ErrorField
		}
	}
</script>
<style lang="sass">
	.form_wrap
		width: 90%
		margin: 25px auto
		
		.form_row
			display: flex
			align-items: baseline
			margin: 15px 0
			
			.el-select
				width: 100%
			
			.form_title
				flex: 3
				text-align: right
				padding-right: 10px
			
			.form_field
				flex: 9
			
			.star
				color: red
				font-size: 1.2em
				font-weight: bold
</style>