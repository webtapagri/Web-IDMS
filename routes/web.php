<?php

use App\Http\Controllers\MaterialController;
use Yajra\DataTables\Facades\DataTables;
use App\DataTables\RoadDataTable;

Auth::routes();

Route::group(['middleware' => ['web']], function () {
	Route::get('/cace', 	'MasterController@cace')->name('sksskm');
	Route::get('/caces', 	'MasterController@caces')->name('sksskms');
});

Route::group(['middleware' => [ 'auth' ]], function () {
	
	Route::get('/', 'RoadController@index')->name('road');
	Route::get('/icons', 'HomeController@icons')->name('icons');

	Route::group(['prefix'=>'api'], function () {
		
		Route::group(['prefix'=>'master'], function () {
			Route::get('/road-status', 				['as'=>'master.api_road_status', 'uses'=>'RoadController@api_status']);
			Route::get('/road-category/{id}', 		['as'=>'master.api_road_category', 'uses'=>'RoadController@api_category']);
			
			Route::get('/sync-afd/{comp}', 				['as'=>'master.api_sync_afd', 'uses'=>'MasterController@sync_afd']);
			Route::get('/sync-comp', 				['as'=>'master.api_sync_comp', 'uses'=>'MasterController@sync_comp']);
			// Route::get('/sync-block', 				['as'=>'master.api_sync_block', 'uses'=>'MasterController@sync_block']);
			Route::get('/sync-block/{comp}/{est}', 				['as'=>'master.api_sync_block', 'uses'=>'MasterController@sync_block']);
			Route::get('/sync-est', 				['as'=>'master.api_sync_est', 'uses'=>'MasterController@sync_est']);	
			
			Route::get('/company', 				'MasterController@api_company')->name('api.master.company');
			Route::get('/estate_tree/{id}', 	'MasterController@api_estate_tree')->name('api.master.api_estate_tree');
			Route::get('/afdeling_tree/{id}', 	'MasterController@api_afdeling_tree')->name('api.master.api_afdeling_tree');
			Route::get('/block_tree/{id}/{werks}', 		'MasterController@api_block_tree')->name('api.master.api_block_tree');
			
			Route::get('/estate', 	'MasterController@api_estate')->name('api.master.estate');
			Route::get('/werks', 				['as'=>'master.api_werks', 'uses'=>'ConfigurationController@api_werks']);
		});
		
		Route::group(['prefix'=>'history'], function () {
			Route::get('/progress-perkerasan-detail/{id}', 	'TransactionController@api_progress_perkerasan_detail')->name('api.history.api_progress_perkerasan_detail');
			Route::get('/road-status-detail/{id}', 	'TransactionController@api_road_status_detail')->name('api.history.api_road_status_detail');
		});
	});


	Route::group(['prefix'=>'master'], function () {
		Route::get('/company', 				['as'=>'master.company', 'uses'=>'MasterController@company']);
		Route::get('/company-datatables', 	['as'=>'master.company_datatables', 'uses'=>'MasterController@company_datatables']);
		Route::get('/estate', 				['as'=>'master.estate', 'uses'=>'MasterController@estate']);
		Route::get('/estate-datatables', 	['as'=>'master.estate_datatables', 'uses'=>'MasterController@estate_datatables']);
		Route::get('/afdeling', 			['as'=>'master.afdeling', 'uses'=>'MasterController@afdeling']);
		Route::get('/afdeling-datatables', 	['as'=>'master.afdeling_datatables', 'uses'=>'MasterController@afdeling_datatables']);
		Route::get('/block', 				['as'=>'master.afdeling', 'uses'=>'MasterController@block']);
		Route::get('/block-datatables', 	['as'=>'master.block_datatables', 'uses'=>'MasterController@block_datatables']);
		
		Route::get('/road-status', 				['as'=>'master.road_status', 'uses'=>'RoadController@status']);
		Route::get('/road-status-datatables', 	['as'=>'master.road_status_datatables', 'uses'=>'RoadController@status_datatables']);
		Route::get('/road-status-add', 			['as'=>'master.road_status_add', 'uses'=>'RoadController@add']);
		Route::post('/road-status-save', 		['as'=>'master.road_status_save', 'uses'=>'RoadController@save']);
		Route::post('/road-status-update', 		['as'=>'master.road_status_update', 'uses'=>'RoadController@update']);
		Route::get('/road-status-delete/{id}', 	['as'=>'master.road_status_delete', 'uses'=>'RoadController@delete']);
		
		Route::get('/road-category', 			['as'=>'master.road_category', 'uses'=>'RoadController@category']);
		Route::post('/road-category-save', 		['as'=>'master.road_category_save', 'uses'=>'RoadController@category_save']);
		Route::get('/road-category-datatables', 	['as'=>'master.road_category_datatables', 'uses'=>'RoadController@category_datatables']);
		Route::post('/road-category-update', 		['as'=>'master.road_category_update', 'uses'=>'RoadController@category_update']);
		Route::get('/road-category-delete/{id}', 	['as'=>'master.road_category_delete', 'uses'=>'RoadController@category_delete']);
		
		Route::get('/road', 			['as'=>'master.road', 'uses'=>'RoadController@road']);
		Route::get('/road-add', 			['as'=>'master.road_add', 'uses'=>'RoadController@road_add']);
		Route::get('/road-bulk-add', 			['as'=>'master.road_bulk_add', 'uses'=>'RoadController@road_bulk_add']);
		Route::post('/road-bulk-save', 			['as'=>'master.road_bulk_save', 'uses'=>'RoadController@road_bulk_save']);
		Route::post('/road-save', 			['as'=>'master.road_save', 'uses'=>'RoadController@road_save']);
		Route::get('/road-datatables.json', 	['as'=>'master.road_datatables', 'uses'=>'RoadController@road_datatables']);
		Route::post('/road-update', 	['as'=>'master.road_update', 'uses'=>'RoadController@road_update']);
		Route::get('/road-delete/{id}', 	['as'=>'master.road_delete', 'uses'=>'RoadController@road_delete']);
		
    });

	Route::group(['prefix'=>'history'], function () {
		
		Route::get('/progres-perkerasan', 	'TransactionController@progres_perkerasan')->name('history.progres_perkerasan');
		Route::get('/progres-perkerasan/bulkadd', 	'TransactionController@progres_perkerasan_bulkadd')->name('history.progres_perkerasan_bulkadd');
		Route::post('/progres-perkerasan/bulksave', 	'TransactionController@progres_perkerasan_bulksave')->name('history.progres_perkerasan_bulksave');
		Route::get('/progres-perkerasan-datatables', 	'TransactionController@progres_perkerasan_datatables')->name('history.progres_perkerasan_datatables');
		Route::post('/progres-perkerasan-update', 	'TransactionController@progres_perkerasan_update')->name('history.progres_perkerasan_update');
		
		Route::get('/road-status', 	'TransactionController@road_status')->name('history.road_status');
		Route::get('/road-status-datatables', 	'TransactionController@road_status_datatables')->name('history.road_status_datatables');
		Route::post('/road-status-update', 	'TransactionController@road_status_update')->name('history.road_status_update');
		
	});
	
	Route::group(['prefix'=>'report'], function () {
		Route::get('/road', 	'ReportsController@road')->name('report.road');
		Route::get('/road-datatables', 	'ReportsController@road_datatables')->name('report.road_datatables');
		Route::post('/download-road', 	'ReportsController@download_road')->name('report.download_road');

		Route::get('/progress-perkerasan', 	'ReportsController@progress_perkerasan')->name('report.progress_perkerasan');
		Route::get('/progress-perkerasan-datatables', 	'ReportsController@progress_perkerasan_datatables')->name('report.progress_perkerasan_datatables');
		Route::get('/progress-perkerasan-download', 	'ReportsController@progress_perkerasan_download')->name('report.progress_perkerasan_download');
	});

	// Route::get('/report/road', function(RoadDataTable $dataTable) {
	// 	return $dataTable->render('report.road');
	// });

	Route::group(['prefix'=>'setting'], function () {
		Route::get('/period', 	'ConfigurationController@period')->name('setting.period');
		Route::post('/period-save', 			['as'=>'setting.period_save', 'uses'=>'ConfigurationController@period_save']);
		Route::post('/period-update', 			['as'=>'setting.period_update', 'uses'=>'ConfigurationController@period_update']);
		Route::get('/period-delete/{id}', 			['as'=>'setting.period_delete', 'uses'=>'ConfigurationController@delete']);
		Route::get('/period-datatables', 			['as'=>'setting.period_datatables', 'uses'=>'ConfigurationController@period_datatables']);
	});


});




###################################################################################################### 

/* USER SETTINGS */

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::post('/ldaplogin', 'LDAPController@login');
Route::post('/ldaplogout', 'LDAPController@logout');

Route::resource('/users', 'UsersController');
Route::post('/users/post', 'UsersController@store');
Route::get('/users/edit/', 'UsersController@show');
Route::post('/users/inactive', 'UsersController@inactive');
Route::post('/users/active', 'UsersController@active');
Route::match(['get', 'post'], 'grid-users', [
    'as' => 'get.users',
    'uses' => 'UsersController@dataGrid'
]);

Route::resource('/menu', 'MenuController');
Route::post('/menu/post', 'MenuController@store');
Route::get('/menu/edit/', 'MenuController@show');
Route::post('/menu/inactive', 'MenuController@inactive');
Route::post('/menu/active', 'MenuController@active');
Route::match(['get', 'post'], 'grid-menu', [
    'as' => 'get.menu_grid',
    'uses' => 'MenuController@dataGrid'
]);

Route::resource('/modules', 'ModuleController');
Route::post('/modules/post', 'ModuleController@store');
Route::get('/modules/edit/', 'ModuleController@show');
Route::post('/modules/inactive', 'ModuleController@inactive');
Route::post('/modules/active', 'ModuleController@active');
Route::match(['get', 'post'], 'grid-modules', [
    'as' => 'get.grid_modules',
    'uses' => 'ModuleController@dataGrid'
]);

Route::resource('/roles', 'RolesController');
Route::post('/roles/post', 'RolesController@store');
Route::get('/roles/edit/', 'RolesController@show');
Route::post('/roles/inactive', 'RolesController@inactive');
Route::post('/roles/active', 'RolesController@active');
Route::match(['get', 'post'], 'grid-role_grid', [
    'as' => 'get.role_grid',
    'uses' => 'RolesController@dataGrid'
]);

Route::resource('/accessright', 'AccessRightController');
Route::post('/accessright/post', 'AccessRightController@store');
Route::get('/accessright/edit/', 'AccessRightController@show');
Route::post('/accessright/inactive', 'AccessRightController@inactive');
Route::post('/accessright/active', 'AccessRightController@active');
Route::match(['get', 'post'], 'grid-accessright', [
    'as' => 'get.accessright_grid',
    'uses' => 'AccessRightController@dataGrid'
]);

/* DOCS */
Route::get('SapDownloadExcel', 'SAPController@downloadExcel');

/* JSON DATA SOURCE */
Route::get( 'get-outstandingdetail', ['as' => 'get.outstandingdetail', 'uses' => 'OutstandingController@requestDetail']);
Route::get( 'get-outstandingdetailfiles', ['as' => 'get.outstandingdetailfiles', 'uses' => 'OutstandingController@requestDetailFiles']);
Route::get( 'get-outstandingdetailitem', ['as' => 'get.outstandingdetailitem', 'uses' => 'OutstandingController@requestDetailItem']);
Route::get( 'get-outstandingdetailitempo', ['as' => 'get.outstandingdetailitempo', 'uses' => 'OutstandingController@requestDetailItemPO']);
Route::get( 'get-outstandingdetailitemfile', ['as' => 'get.outstandingdetailitemfile', 'uses' => 'OutstandingController@requestDetailItemFile']);


/* SELECT 2 */
Route::get('get-select_module', ['as' => 'get.select_module', 'uses' => 'ModuleController@select2']);
Route::get('get-select_menu', ['as' => 'get.select_menu', 'uses' => 'MenuController@select2']);
Route::get('get-select_role', ['as' => 'get.select_role', 'uses' => 'RolesController@select2']);
Route::get('get-generaldataplant', ['as' => 'get.generaldataplant', 'uses' => 'Select2Controller@generaldataplant']);
Route::get('get-assetgroup', ['as' => 'get.assetgroup', 'uses' => 'Select2Controller@assetgroup']);
Route::get('get-assetgroupcondition', ['as' => 'get.assetgroupcondition', 'uses' => 'Select2Controller@assetgroupcondition']);
Route::get('get-assetsubgroup', ['as' => 'get.assetsubgroup', 'uses' => 'Select2Controller@assetsubgroup']);
Route::get( 'get-jenisasset', ['as' => 'get.jenisasset', 'uses' => 'Select2Controller@jenisasset']);
Route::get('get-select_workflow_code', ['as' => 'get.select_workflow_code', 'uses' => 'WorkflowController@workflowcode']);
Route::get('get-select_workflow_detail_code', ['as' => 'get.select_workflow_detail_code', 'uses' => 'WorkflowController@workflowcodedetail']);
Route::get('get-select_workflow_detail_role', ['as' => 'get.select_workflow_detail_role', 'uses' => 'WorkflowController@workflowcoderole']);
Route::get('get-select_workflow_detail_code', ['as' => 'get.select_workflow_detail_code', 'uses' => 'WorkflowController@workflowcodedetail']);
Route::get('get-select_jenis_asset_code', ['as' => 'get.select_jenis_asset_code', 'uses' => 'AssetClassController@select_jenis_asset_code']);
Route::get('get-select_jenis_asset_code_text_only', ['as' => 'get.select_jenis_asset_code_text_only', 'uses' => 'AssetClassController@select_jenis_asset_code_text_only']);
Route::get('get-select_group_code', ['as' => 'get.select_group_code', 'uses' => 'AssetClassController@select_group_code']);
Route::get('get-select_subgroup_code', ['as' => 'get.select_subgroup_code', 'uses' => 'AssetClassController@select_subgroup_code']);
Route::get('get-select_subgroup_code_condition', ['as' => 'get.select_subgroup_code_condition', 'uses' => 'AssetClassController@select_subgroup_code_condition']);
Route::get('get-select_asset_controller', ['as' => 'get.select_asset_controller', 'uses' => 'AssetClassController@select_asset_controller']);
Route::get('get-generaldata-assetcontroller', ['as' => 'get.generaldata_assetcontroller', 'uses' => 'Select2Controller@generaldata_assetcontroller']);
Route::get('get-select_role_idname', ['as' => 'get.select_role_idname', 'uses' => 'RolesController@select_role']);
Route::get('get-select_user', ['as' => 'get.select_user', 'uses' => 'UsersController@select2']);
Route::get('get-select_uom', ['as' => 'get.select_uom', 'uses' => 'Select2Controller@select_uom']);
Route::get('get-tujuan_business_area', ['as' => 'get.tujuan_business_area', 'uses' => 'Select2Controller@tujuan_business_area']);

/* WORKFLOW SETTING */
Route::resource('/setting/workflow', 'WorkflowController');
Route::post('/workflow/post', 'WorkflowController@store');
Route::post('/workflow/post-detail', 'WorkflowController@store_detail');
Route::post('/workflow/post-detail-job', 'WorkflowController@store_detail_job');
Route::get('/workflow/edit/', 'WorkflowController@show');
Route::get('/workflow/edit-detail/', 'WorkflowController@show_detail');
Route::get('/workflow/edit-detail-job/', 'WorkflowController@show_detail_job');
Route::match(['get', 'post'], 'grid-workflow', [
    'as' => 'get.grid_workflow',
    'uses' => 'WorkflowController@dataGrid'
]);
/*
Route::match(['get', 'post'], 'grid-workflow-detail', [
    'as' => 'get.grid_workflow_detail',
    'uses' => 'WorkflowController@dataGrid'
]);
*/
Route::post('grid-workflow-detail/{id}', 'WorkflowController@dataGridDetail')->name('grid-workflow-detail/{id}');
Route::post('grid-workflow-detail-job/{id}', 'WorkflowController@dataGridDetailJob')->name('grid-workflow-detail-job/{id}');

/* MASTER GENERAL DATA SETTING */
Route::resource('/setting/general-data', 'GeneralDataController');
Route::match(['get', 'post'], 'grid-general-data', [
    'as' => 'get.grid_general_data',
    'uses' => 'GeneralDataController@dataGrid'
]);
Route::post('/general-data/post', 'GeneralDataController@store');
Route::get('/general-data/edit/', 'GeneralDataController@show');

/* ASSET CLASS - SETTING */
Route::resource('/setting/asset-class', 'AssetClassController');
Route::match(['get', 'post'], 'grid-asset-class', [
    'as' => 'get.grid_asset_class',
    'uses' => 'AssetClassController@dataGrid'
]);
Route::post('/asset-class/post', 'AssetClassController@store');
Route::post('/asset-class/post-group-asset', 'AssetClassController@store_group_asset');
Route::post('/asset-class/post-subgroup-asset', 'AssetClassController@store_subgroup_asset');
Route::post('/asset-class/post-asset-map', 'AssetClassController@store_asset_map');
Route::get('/asset-class/edit/', 'AssetClassController@show');
Route::get('/asset-class/edit-group-asset/', 'AssetClassController@show_group_asset');
Route::get('/asset-class/edit-subgroup-asset/', 'AssetClassController@show_subgroup_asset');
Route::get('/asset-class/edit-asset-map/', 'AssetClassController@show_asset_map');
Route::post('grid-ac-group-asset/{id}', 'AssetClassController@dataGridGroupAsset')->name('grid-ac-group-asset/{id}');
Route::post('grid-ac-subgroup-asset/{id}/{id_jenis_asset_code}', 'AssetClassController@dataGridSubGroupAsset')->name('grid-ac-subgroup-asset/{id}/{id_jenis_asset_code}');
Route::post('grid-ac-asset-map/{id}', 'AssetClassController@dataGridAssetMap')->name('grid-ac-asset-map/{id}');

/* SETTING - ROLE MAP */
Route::resource('/setting/role-map', 'RoleMapController');
Route::match(['get', 'post'], 'grid-role-map', [
    'as' => 'get.grid_role_map',
    'uses' => 'RoleMapController@dataGrid'
]);
Route::get('/role-map/edit/', 'RoleMapController@show');
Route::post('/role-map/post', 'RoleMapController@store');

/* SEND EMAIL */
Route::post('/request/email_create_po','FamsEmailController@index');

/* MASTER ASSET */
Route::resource('/master-asset', 'MasterAssetController');
Route::match(['get', 'post'], 'grid-master-asset', [
    'as' => 'get.grid_master_asset',
    'uses' => 'MasterAssetController@dataGrid'
]);
//Route::get('/master-asset/edit/', 'MasterAssetController@show');
Route::get('/master-asset/show-data/{id}', 'MasterAssetController@show_edit');
Route::get('/master-asset/show_qrcode/{ams}', 'MasterAssetController@show_qrcode')->name('ams');
Route::get('/test_qrcode', 'MasterAssetController@test_qrcode');
Route::get('/master-asset/print-qrcode/{noreg}', 'MasterAssetController@print_qrcode')->name('noreg');
Route::post('/master-asset/download', 'MasterAssetController@download')->name('master_asset.download');
Route::get('bulk-download', 'MasterAssetController@view_download_masterasset_qrcode')->name('view_download_masterasset_qrcode');
Route::post('download_masterasset_qrcode', 'MasterAssetController@download_masterasset_qrcode')->name('download_masterasset_qrcode');

/* REQUEST ASET LAINNYA */
Route::resource('/request', 'RequestAsetLainController');
Route::get('/create/aset-lain', 'RequestAsetLainController@create');
Route::post('/aset-lain/post', 'RequestAsetLainController@store');

/* RESUME PROCESS */
Route::get('/resume/document', 'ResumeController@index');
Route::post('/resume/document-submit','ResumeController@document_submit');
Route::get('/resume/user', 'ResumeController@user');
Route::get('get-select_role_resume', ['as' => 'get.select_role_resume', 'uses' => 'Select2Controller@select_role']);
Route::get('get-select_user_resume', ['as' => 'get.select_user_resume', 'uses' => 'Select2Controller@select_user']);
Route::post('/resume/user-submit','ResumeController@user_submit');

/* ALL REPORT */
// Route::get('/report/list-asset', 'ReportController@list_asset');
// Route::post('/report/list-asset/submit', 'ReportController@list_asset_submit');
// Route::post('/report/list-asset/download', 'ReportController@list_asset_download');

/* DISPOSAL */
Route::resource('/disposal-penjualan', 'DisposalController');
Route::get('/disposal-penjualan/add/{id}/{pengajuan}', 'DisposalController@add');
Route::get('/disposal-penjualan/delete/{kode_asset_ams}', 'DisposalController@remove');

Route::get('/disposal-hilang', 'DisposalController@index_hilang');
Route::get('/disposal-hilang/add_hilang/{id}/{pengajuan}', 'DisposalController@add_hilang');
Route::get('/disposal-hilang/delete_hilang/{kode_asset_ams}', 'DisposalController@remove_hilang');

Route::get('/disposal-rusak', 'DisposalController@index_rusak');
Route::get('/disposal-rusak/add_rusak/{id}/{pengajuan}', 'DisposalController@add_rusak');
Route::get('/disposal-rusak/delete_rusak/{kode_asset_ams}', 'DisposalController@remove_rusak');

Route::post('/proses_disposal/{tipe}','DisposalController@proses');
Route::post('/disposal/edit_harga', 'DisposalController@update_harga_perolehan');
Route::post('/disposal/upload_berkas_hilang', 'DisposalController@upload_berkas_hilang');
Route::post('/disposal/upload_berkas_rusak', 'DisposalController@upload_berkas_rusak');
Route::post('/disposal/upload_berkas', 'DisposalController@upload_berkas');
Route::get('/approval/berkas-disposal/{no_reg}', 'ApprovalController@berkas_disposal')->name('no_reg');
Route::get('/approval/berkas-mutasi/{no_reg}', 'ApprovalController@berkas_mutasi')->name('no_reg');
Route::get('/disposal/view-berkas/{no_reg}', 'DisposalController@berkas_disposal')->name('no_reg');
Route::get('/disposal/view-berkas-serah-terima/{kode_asset_ams}', 'DisposalController@berkas_serah_terima')->name('kode_asset_ams');
Route::get('/disposal/view-berkas-detail/{kode_asset_ams}/{file_category}', 'DisposalController@berkas_disposal_detail')->name('kode_asset_ams');
Route::get('/disposal/list-kategori-upload/{kode_asset_ams}/{jenis_pengajuan}', 'DisposalController@list_file_category')->name('kode_asset_ams');
Route::get('/disposal/view-berkas-notes/{kode_asset_ams}', 'DisposalController@berkas_notes')->name('kode_asset_ams');
Route::get('/disposal/view-berkas-by-type/{kode_asset_ams}/{file_category}', 'DisposalController@file_download')->name('kode_asset_ams');
Route::get('/disposal/delete_berkas_temp','DisposalController@delete_berkas_temp');
/* END DISPOSAL */



