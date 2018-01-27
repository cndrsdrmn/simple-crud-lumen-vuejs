<!DOCTYPE html>
<html>
<head>
	<title>Lumen VueJs</title>
	<!-- CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
	<script type="text/javascript" src="/js/app.js"></script>
	<style type="text/css">
		.title-no {
			width: 75px;
		}
		.action-th {
			width: 130px;
		}
		.custom-datatble label {
			font-weight: normal;
			text-align: left;
			white-space: nowrap;
		}
		.custom-datatble select {
			width: 75px;
			display: inline-block;
		}
		.custom-datatble input {
			margin-left: 0.5em;
			display: inline-block;
			width: auto;
		}
		.paddingT7 {
			padding-top: 7.5px;
		}
		.modal-mask {
			position: fixed;
			z-index: 9998;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, .5);
			display: table;
			transition: opacity .5s ease;
		}
		/*
		 * The following styles are auto-applied to elements with
		 * transition="modal" when their visibility is toggled
		 * by Vue.js.
		 *
		 * You can easily play with the modal transition by editing
		 * these styles.
		 */

		 .modal-enter {
		 	opacity: 0;
		 }

		 .modal-leave-active {
		 	opacity: 0;
		 }

		 .modal-enter .modal-container,
		 .modal-leave-active .modal-container {
		 	-webkit-transform: scale(1.1);
		 	transform: scale(1.1);
		 }
		 [v-clock] {
		 	display: none;
		 }
	</style>
	<script type="text/javascript">
		window.BASE_URL = "{!! url() !!}";
	</script>
</head>
<body>
	<header class="page-header">
		<img src="https://vuejs.org/images/logo.png" width="150" class="img-responsive center-block" alt="">
	</header>
	<div class="container">
		<div class="row" id="app">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<h4 class="panel-title pull-left paddingT7">Panel header</h4>
						<div class="pull-right">
							<button class="btn btn-sm btn-primary" @click="doAction('Save')">
								<span class="glyphicon glyphicon-plus"></span>
							</button>
						</div>
					</div>

					<div class="panel-body">
						<div class="table-responsive">
							<div class="pull-left custom-datatble">
								<label>
									Show
									<select v-model="filter" @change="doFilter" class="form-control input-sm">
										<option>10</option>
										<option>25</option>
										<option>50</option>
										<option>100</option>
									</select>
									entry
								</label>
							</div>
							<div class="pull-right custom-datatble">
								<label>
									Search: 
									<input type="search" v-model="search" class="form-control input-sm" @keyup="doSearch" aria-controls="example">
								</label>
							</div>

							<vuetable
								ref="vuetable"
								api-url="{{ url('examples') }}"
								table-wrapper="#content"
								pagination-path=""
								:per-page="filter"
								:fields="columns"
								:sort-order="sortOrder"
								:css="css.table"
								:append-params="moreParams"
								@vuetable:pagination-data="onPaginationData"
							>
								<template slot="actions" scope="props">
									<div class="btn-group-sm">
										<button class="btn btn-xs btn-primary"
											@click="doAction('show', props.rowData.id)">
											<i class="glyphicon glyphicon-eye-open"></i>
										</button>
										<button class="btn btn-xs btn-success"
											@click="doAction('Edit', props.rowData.id)">
											<i class="glyphicon glyphicon-pencil"></i>
										</button>
										<button class="btn btn-xs btn-danger"
											@click="doAction('delete', props.rowData)">
											<i class="glyphicon glyphicon-trash"></i>
										</button>
									</div>
								</template>
							</vuetable>
						</div>
						<div>
							<vuetable-pagination-info ref="paginationInfo"
								:css="css.pagination"
								info-class="pull-left"
							></vuetable-pagination-info>
							<vuetable-pagination ref="pagination"
								:css="css.pagination"
								@vuetable-pagination:change-page="onChangePage"
							></vuetable-pagination>
						</div>
					</div>
				</div>
			</div>
			<modal :show="showModal" v-if="showModal" :data-of-content="modal" @close="showModal = false">
				<h4 class="modal-title" slot="header" v-clock>Modal title</h4>
			</modal>
		</div>
	</div>

	<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.min.js"></script> -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vee-validate/2.0.3/vee-validate.min.js"></script>
	<script src="https://unpkg.com/vuetable-2@1.6.0"></script>
	<!-- JavaScript -->
	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>

	<!-- template for the modal component -->
	<!-- template for the modal component -->
	<script type="text/x-template" id="modal-template">
		<transition name="modal">
			<div class="modal-mask" @click="close" v-show="show">
				<div class="modal-dialog modal-lg">
					<div class="modal-content modal-container" @click.stop>

						<div class="modal-header">
							<slot name="header">
								default header
							</slot>
						</div>
						<form @submit.prevent="validateBeforeSubmit">
							<div class="modal-body">
								<input type="hidden" v-model="id">
								<div :class="{'form-group': true, 'has-error': errors.has('title') || validates.title}">
									<label for="recipient-name" class="control-label">Title:</label>
									<p v-if="dataOfContent.isTextOnly">@{{ title }}</p>
									<input v-validate="'required|min:10'" class="form-control" name="title" type="text" v-if="dataOfContent.isForm" v-model="title">
									<span v-show="errors.has('title') || validates.title" class="help-block">
										@{{ errors.first('title') || validates.title }}
									</span>
								</div>
								<div :class="{'form-group': true, 'has-error': errors.has('content') || validates.content}">
									<label for="message-text" class="control-label">Content:</label>
									<textarea class="form-control" v-validate="'required|min:100'" name="content" v-if="dataOfContent.isForm" v-model="content"></textarea>
									<p v-if="dataOfContent.isTextOnly">@{{ content }}</p>
									<span v-show="errors.has('content') || validates.content" class="help-block">
										@{{ errors.first('content') || validates.content }}
									</span>
								</div>
							</div>

							<div class="modal-footer">
								<button class="btn btn-sm btn-default" type="button" @click="close">
									Close
								</button>
								<button class="btn btn-sm btn-primary" type="submit" v-if="dataOfContent.isForm" v-clock>
									@{{ dataOfContent.title }}
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</transition>
	</script>

	<script type="text/javascript">
		alertify.defaults.theme.ok = "btn btn-primary";
		alertify.defaults.theme.cancel = "btn btn-danger";
		alertify.defaults.theme.input = "form-control";

		Vue.use(Vuetable);
		Vue.use(VeeValidate);

		Vue.component('modal', {
			template: '#modal-template',
			props: ['show', 'data-of-content'],
			data: function () {
				return {
					title: '',
					content: '',
					method: 'POST',
					action: `${BASE_URL}/example`,
					validates: {
						title: '',
						content: '',
					}
				};
			},
			methods: {
				close () {
					this.title = '';
					this.content = '';
					this.$emit('close');
				},
				fetchId (id) {
	            	Vue.nextTick(() => {
						axios.get(`${BASE_URL}/example/${id}`)
							.then((response) => {
								let data = response.data;
								this.title = data.title;
								this.content = data.content;
							})
							.catch((error) => {
								console.log(error);
							});
	            	});
				},
				doSubmit () {
					let params = {
						id: this.id,
						title: this.title,
						content: this.content,
					}

					if (this.id) {
						this.method = 'PUT';
						this.action += `/${this.id}`;
					}

					axios({
						method: this.method,
						url: this.action,
						data: params
					}).then((response) => {
						alertify.success(response.data.message);
						this.close();
						app.$emit('submited');
					}).catch((error) => {
						if (error.response) {
							let errors = error.response.data;
							switch (error.response.status) {
								case 422: 
									for (var key in errors) {
										if (errors.hasOwnProperty(key)) {
											this.validates[key] = errors[key][0];
										}
									}
									break;
								default:
									alertify.error(error.response.statusText);
									this.close();
									break;
							}
						}
					});
				},
				validateBeforeSubmit () {
					this.$validator.validateAll().then((result) => {
						if (result) {
							this.doSubmit();
							return;
						}
					});
				}
			},
			mounted () {
				document.addEventListener("keydown", (e) => {
					if (this.show && e.keyCode == 27) {
						this.close();
					}
				});

				if (this.dataOfContent.id) {
					this.fetchId(this.dataOfContent.id);	
				}
			},
			computed: {
				id: function () {
					return this.dataOfContent.id;
				}
			}
		});

	    window.app = new Vue({
	        el: '#app',
	        data: {
	        	showModal: false,
	        	modal: {
	        		id: '',
		        	isTextOnly: false,
		        	isForm: false,
	        		title: '',
	        	},
	        	filter: 10,
	        	search: '',
	        	moreParams : {},
	            columns: [
	                {name: 'id', titleClass: 'text-center title-no', sortField: 'id', dataClass: 'text-center'},
	                {name: 'title', titleClass: 'text-center', sortField: 'title'},
	                {name: 'content', titleClass: 'text-center', sortField: 'content'},
	                {name: '__slot:actions', title: 'Actions', titleClass: 'text-center action-th', dataClass: 'text-center'}
				],
				sortOrder: [
		            {field: 'id', sortField: 'id', direction: 'asc'}
	            ],
	            css: {
	            	table: {
	            		handleIcon: 'glyphicon glyphicon-sort',
	            		tableClass: 'table table-striped table-bordered table-condensed',
	            		ascendingIcon:  'glyphicon glyphicon-sort-by-attributes',
	            		descendingIcon: 'glyphicon glyphicon-sort-by-attributes-alt',
	            	},
	            	pagination: {
	            		infoClass: 'pull-left',
	            		wrapperClass: 'vuetable-pagination pull-right btn-group-sm',
	            		activeClass: 'btn-primary',
	            		disabledClass: 'disabled',
	            		pageClass: 'btn btn-xs',
	            		linkClass: 'btn btn-xs',
	            		icons: {
	            			first: 'glyphicon glyphicon-backward',
	            			prev: 'glyphicon glyphicon-chevron-left',
	            			next: 'glyphicon glyphicon-chevron-right',
	            			last: 'glyphicon glyphicon-forward',
	            		},
	            	}
	            },
				paginationComponent: 'vuetable-pagination',
	        },
	        components:{
	        	'vuetable-pagination': Vuetable.VuetablePagination,
	        	'vuetable-pagination-info': Vuetable.VuetablePaginationInfo,
	        },
	        mounted () {
	        	this.$on('search-set', search => this.onSearchSet(search));
	        	this.$on('filter-set', () => this.refreshTable());
	        	this.$on('submited', () => this.refreshTable());
	        },
	        methods: {
	            onPaginationData (paginationData) {
	            	this.$refs.pagination.setPaginationData(paginationData);
	            	this.$refs.paginationInfo.setPaginationData(paginationData);
	            },
	            onChangePage (page) {
	            	this.$refs.vuetable.changePage(page);
	            },
	            onSearchSet (text) {
	            	this.moreParams = {search : text};
	            	Vue.nextTick(() => this.$refs.vuetable.refresh());
	            },
	            refreshTable () {
	            	Vue.nextTick(() => this.$refs.vuetable.refresh());
	            },
	            doSearch () {
	            	this.$emit('search-set', this.search);
				},
				doFilter () {
	            	this.$emit('filter-set');
				},
	            doAction (title, data = null) {
	            	if (title != 'delete') {
		            	this.showModal = true;

		            	this.modal = {
		            		id: data,
		            		title: title,
		            		isForm: title != 'show',
		            		isTextOnly: title == 'show'
		            	}
		            	return;
	            	}

	            	alertify.confirm(`Delete item <b>${data.title}</b>`, `Are you sure to delete the item <b>${data.title}</b>`, () => {
	            		axios.delete(`/example/${data.id}`)
	            			.then((response) => {
	            				alertify.success(response.data.message);
	            				this.$emit('submited');
	            			}).catch((error) =>{
	            				alertify.error(error.response.statusText);
	            			});
	            	}, () => {});
			  	}
	        },
	        events: {
	            'vuetable:action': function(action, data) {
	                console.log('vuetable:action', action, data)
	                if (action == 'view-item') {
	                    // this.viewProfile(data.id)
	                }
	            },
	            'vuetable:load-error': function(response) {
	                console.log('Load Error: ', response)
	            }
	        }
        })
	</script>
</body>
</html>