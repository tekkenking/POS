<?php
//tt( $_SERVER["MYSQL_HOME"] );
//tt(parse_ini_file($_SERVER['SystemRoot'] . '\\'.'vend.txt', true));

//echo php_uname('n'); does the same thing
//echo gethostname(); does the same thing
//tt(date('h:i a', strtotime('now')), true);
// tt(date('h:i a', strtotime('2:35 PM')));

//tt(phpinfo(32), true);
//tt( $_SERVER["REDIRECT_PHPRC"] );

//tt(md5(strtotime('20th May, 2014') . 'Talkerspot-PC'));


Route::get('/errorPage', array('uses'=>'BaseController@callErrorPage'));
Route::get('/errorPage/{id}', array('uses'=>'BaseController@callErrorPage'))->where('id', '[0-9]+');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('', array('uses'=>'HomeController@show', 'as'=>'home'));

//LOGIN AREA
Route::get('/login', array('uses'=>'LoginController@index', 'as'=>'login'));
Route::post('/login', array('uses'=>'LoginController@auth', 'as'=>'loginarea'));

Route::get('/logout', array('uses'=>'SecureBaseController@logout', 'as'=>'logout'));
Route::get('/createAdmin', array('uses' => 'CreateAdminController@index', 'as'=>'createAdmin.firstUserForm'));
Route::post('/saveAdmin', array('uses' => 'CreateAdminController@createFirstAdmin', 'as'=>'createAdmin.save'));

//MESSAGE
Route::post('/create/message', array('uses'=>'MessageController@store', 'as'=>'inbox'));

Route::get('/quickview/message/{id}', array('uses'=>'MessageController@quickView', 'as'=>'quickview'))->where('id', '[0-9]');
Route::get('/inbox', array('uses'=>'MessageController@index', 'as'=>'messageinbox'));

//GRAPH SEARCH
Route::get('/search', array('uses'=>'GraphSearchController@index', 'as'=>'search'));

//Today Sale
Route::get('/todaysale', array('uses'=>'TodaySaleController@index', 'as'=>'todaysale'));

//STAFF AREA
Route::get('/changepassword', array('uses'=>'StaffController@index', 'as'=>'viewchangepassword'));
Route::post('/changepassword', array('uses'=>'StaffController@changePassword', 'as'=>'changepassword'));

//ALERTS
Route::get('/alerts/outofstockwarning_count', array('uses'=>'AlertController@productAlmostOutOfStock_count', 'as'=>'outofstockwarning_count'));

Route::get('/alerts/outofstockwarning_list', array('uses'=>'AlertController@productAlmostOutOfStock_list', 'as'=>'outofstockwarning_list'));

Route::get('/alerts/unreadinboxmessagealert_count', array('uses'=>'AlertController@unreadInboxMessageAlert_count', 'as'=>'unreadinboxmessagealert_count'));

Route::get('/alerts/unreadinboxmessagealert_list', array('uses'=>'AlertController@unreadInboxMessageAlert_list', 'as'=>'unreadinboxmessagealert_list'));

//PRODUCT AREA
Route::post('/searchproduct', array('uses'=>'ProductController@searchproduct', 'as'=>'searchproduct'));

//RECEIPT CONTROLLER
Route::post('/save_receipt', array('uses'=>'ReceiptController@saveReceipt', 'as'=>'saveReceipt'));
Route::get('/previewreceipt', array('uses'=>'ReceiptController@previewReceipt', 'as'=>'previewreceipt'));

//CART CONTROLLER
Route::post('/save_cart', array('uses'=>'CartController@autoSaveCart', 'as'=>'autoSaveCart'));
//Route::get('/cartcheckout', array('uses'=>'CartController@emptyCart', 'as'=>'emptyCart'));
Route::get('/makepayment', array('uses'=>'CartController@getMakePayment', 'as'=>'makepayment'));
Route::post('/postmakepayment', array('uses'=>'CartController@postMakePayment', 'as'=>'postmakepayment'));

//ADMIN PASSWORD RECOVERY
Route::get('/passwordrecovery', array('uses'=>'AdminPasswordRecoveryController@index', 'as'=>'adminForgotPassword'));

Route::post('/passwordrecovery', array('uses'=>'AdminPasswordRecoveryController@recover', 'as'=>'adminrecoverpassword'));

Route::get('/logout_passwordrecovery', array('uses'=>'AdminPasswordRecoveryController@logoutrecover', 'as'=>'adminlogout_recoverpassword'));

//ADMIN DASHBOARD
Route::get('/admindashboard', array('uses'=>'AdminDashboardController@index', 'as'=>'admindashboard'));
//Route::get('/admindashboard/salesummary', array('uses'=>));

//ADMIN SETTINGS
Route::get('/systemsettings', array('uses'=>'AdminSettingsController@getSystemsettings', 'as'=>'systemsettings'));

Route::get('/systemsettings/previewreceipt', array('uses'=>'AdminSettingsController@previewreceipt', 'as'=>'systemsettings.preview_receipt'));

Route::post('/savegeneralsystemsettings', array('uses'=>'AdminSettingsController@postGeneral', 'as'=>'save_generalsystemsettings'));
Route::post('/savetransactionsystemsettings', array('uses'=>'AdminSettingsController@postTransaction', 'as'=>'save_transactionsystemsettings'));
Route::post('/postreceiptsystemsettings', array('uses'=>'AdminSettingsController@postReceipt', 'as'=>'save_receiptsystemsettings'));

//STOCKMANAGER
Route::get('/stock', array('uses'=>'StockBrandController@showBrands', 'as'=>'adminstock'));
Route::get('/stock/createbrand', array('uses'=>'StockBrandController@index', 'as'=>'admincreatebrand'));
Route::post('/stock/createbrand', array('uses'=>'StockBrandController@addbrand', 'as'=>'admincreatenewbrand'));
Route::post('/stock/updatebrand', array('uses'=>'StockBrandController@updatebrand', 'as'=>'updatebrand'));
Route::any('/stock/logo', array('uses'=>'StockBrandController@logo', 'as'=>'logo'));
Route::post('/stock/deletebrands', array('uses'=>'StockBrandController@deleteBrands', 'as'=>'deletebrands'));
Route::post('/status/brand', array('uses'=>'StockBrandController@status', 'as'=>'brandStatus'));
Route::get('/storeworth', array('uses'=>'StockBrandController@getStoreWorth', 'as'=>'store.worth'));

//Category
Route::get('/stock/{brandname}', array('uses'=>'StockProductCategoryController@showCategory', 'as'=>'adminShowCategory'))->where('brandname', '[0-9a-zA-Z\+]+');
Route::get('/stock/{brandid}/{type}/addcategory', array('uses'=>'StockProductCategoryController@addCategory', 'as'=>'adminAddCategory'))->where('brandid', '[0-9]+');
Route::post('/stock/addcategory', array('uses'=>'StockProductCategoryController@saveAddCategory', 'as'=>'saveaddcategory'));
Route::post('/stock/deletecategory', array('uses'=>'StockProductCategoryController@deleteCategories', 'as'=>'deletecategory'));
Route::post('/stock/updatecategory', array('uses'=>'StockProductCategoryController@updateCategories', 'as'=>'updatecategory'));
Route::post('/status/category', array('uses'=>'StockProductCategoryController@status', 'as'=>'categoryStatus'));

//Admin Product
Route::get('/stock/{brandname}/{categoryname}', array('uses'=>'StockProductController@showProduct', 'as'=>'adminShowProduct'))->where('brandname', '[0-9a-zA-Z\+]+')->where('categoryname', '[0-9a-zA-Z\+]+');
Route::get('/stock/{brandid}/{categoryid}/addproducts', array('uses'=>'StockProductController@addProducts', 'as'=>'adminAddProduct'))->where('brandid', '[0-9]+')->where('categoryid', '[0-9]+');
Route::get('/missingrecords/{productid}/productlog', array('uses'=>'StockProductController@productLog', 'as'=>'adminProductLog'))->where('productid', '[0-9]+');
Route::post('/stock/productsaverecord', array('uses'=>'StockProductController@productSaveRecord', 'as'=>'productsaverecord'));
Route::post('/stock/saveaddproduct', array('uses'=>'StockProductController@saveaddProducts', 'as'=>'saveaddproduct'));
Route::post('/stock/updateproduct', array('uses'=>'StockProductController@updateProducts', 'as'=>'updateproduct'));
Route::post('/stock/deleteproducts', array('uses'=>'StockProductController@deleteProducts', 'as'=>'deleteproducts'));
Route::post('/status/product', array('uses'=>'StockProductController@status', 'as'=>'productStatus'));

//ADMIN RECORDS
Route::get('/adminrecords/show', array('uses'=>'AdminRecordsController@index', 'as'=>'adminHomeRecords'));

//ADMIN STAFFS
Route::get('/admin/staffs/showlogged', array('uses'=>'AdminUserController@showUserLoggedActivities', 'as'=>'adminstaffsactivityfeeds'));

Route::get('/admin/staffs/showsales', array('uses'=>'AdminUserController@showUserSalesActivities', 'as'=>'adminstaffssalesactivityfeeds'));



Route::get('/admin/staff/showform', array('uses'=>'AdminUserController@adminShowStaffForm', 'as'=>'adminshowstaffform'));

Route::get('/admin/staffs/list', array('uses'=>'AdminUserController@adminListStaffs', 'as'=>'adminliststaffs'));

Route::get('/admin/staff/preview/{staffid}', array('uses'=>'AdminUserController@adminPreviewStaffProfile', 'as'=>'adminpreviewstaffprofile'))->where('staffid', '[0-9]+');

Route::post('/admin/staff/registration', array('uses'=>'AdminUserController@staffRegistration', 'as'=>'staffregistration'));

Route::post('/admin/staff/update', array('uses'=>'AdminUserController@staffUpdate', 'as'=>'staffupdate'));

//CUSTOMER AREA
Route::get('/customerform', array('uses'=>'CustomerController@modal_showCustomerForm', 'as'=>'customerform'));

Route::get('/show_searchCustomerToken', array('uses'=>'CustomerController@show_searchCustomerToken', 'as'=>'show_searchCustomerToken'));

Route::post('/searchcustomer', array('uses'=>'CustomerController@searchCustomerToken', 'as'=>'searchcustomer'));

Route::post('/registercustomer', array('uses'=>'CustomerController@registerNewCustomer', 'as'=>'registercustomer'));

//ADMIN CUSTOMER AREA
Route::get('/admin/customers/list', array('uses'=>'AdminCustomerController@adminListCustomers', 'as'=>'adminlistcustomers'));

Route::post('/admin/customers/delete', array('uses'=>'AdminCustomerController@adminDeleteCustomers', 'as'=>'admindeletecustomers'));

Route::get('/admin/customer/preview/{customerid}', array('uses'=>'AdminCustomerController@adminPreviewCustomerProfile', 'as'=>'adminpreviewcustomerprofile'))->where('customerid', '[0-9]+');

Route::get('admin/customerform', array('uses'=>'AdminCustomerController@adminShowCustomerForm', 'as'=>'adminshowcustomerform'));

Route::get('admin/extract/{email}', array('uses'=>'AdminCustomerController@extractor', 'as'=>'extractor'));

Route::post('/admin/registercustomer', array('uses'=>'AdminCustomerController@registerNewCustomer', 'as'=>'adminregistercustomer'));

Route::post('/admin/customer/update', array('uses'=>'AdminCustomerController@update', 'as'=>'admincustomerupdate'));

Route::get('admin/history/{id}/{range}', array('uses'=>'AdminCustomerController@getHistory', 'as'=>'admingetcustomerhistory'))->where('id', '[0-9]+');

Route::get('admin/searchcustomerhistory', array('uses'=>'AdminCustomerController@searchHistory', 'as'=>'adminsearchcustomerhistory'));

// ADMIN BANK RECORDS
Route::get('admin/bankentries',
			array(
				'uses'	=>'AdminBankRecordController@index',
				'as'	=> 'entries'
			)
		);

Route::post('admin/createbankentries',
			array(
				'uses'	=>'AdminBankRecordController@create',
				'as'	=> 'createentry'
			)
		);

Route::post('admin/createbankentry',
			array(
				'uses'	=>'AdminBankRecordController@delete',
				'as'	=> 'deleteentry'
			)
		);

Route::post('admin/saveedited_bankentry',
			array(
				'uses'	=>'AdminBankRecordController@saveEdit',
				'as'	=> 'saveedit'
			)
		);

Route::get('admin/getedited_bankentry',
			array(
				'uses'	=>'AdminBankRecordController@getEdit',
				'as'	=> 'getedit'
			)
		);

// SALES BANK RECORDS
Route::get('bankentries',
			array(
				'uses'	=>'BankRecordController@index',
				'as'	=> 'sale_entries'
			)
		);

Route::post('createbankentries',
			array(
				'uses'	=>'BankRecordController@create',
				'as'	=> 'sale_createentry'
			)
		);

Route::post('createbankentry',
			array(
				'uses'	=>'BankRecordController@delete',
				'as'	=> 'sale_deleteentry'
			)
		);

//ADMIN EXPENDITURES
Route::get('expenditures/admin',
			array(
				'uses'	=>'AdminExpenditureController@index',
				'as'	=> 'expenditures'
			)
		);

Route::get('expenditures/admin/edit',
			array(
				'uses'	=>'AdminExpenditureController@edit',
				'as'	=> 'expenditure.showedit'
			)
		);

Route::post('expenditures/admin/save',
			array(
				'uses'	=>'AdminExpenditureController@save',
				'as'	=> 'expenditure.save'
			)
		);

Route::post('expenditures/admin/saveedit',
			array(
				'uses'	=>'AdminExpenditureController@saveEdit',
				'as'	=> 'expenditure.saveedit'
			)
		);

Route::post('expenditures/admin/delete',
			array(
				'uses'	=>'AdminExpenditureController@delete',
				'as'	=> 'expenditure.delete'
			)
		);

//SALES EXPENDITURE
Route::get('expenditures',
			array(
				'uses'	=> 'ExpenditureController@index',
				'as'	=> 'sales.expenditure'
				)
		);

Route::post('expenditures',
			array(
				'uses'	=> 'ExpenditureController@save',
				'as'	=> 'sales.expenditure_save'
				)
		);


//RECEIPT
Route::get('/staffs/popoverreceiptpreview/{receipt_number}', array('uses'=>'ReceiptController@popoverReceiptPreview', 'as'=>'popoverReceiptPreview'));

//About Software
Route::get('/aboutsoftware', array(
		'uses' 	=> 'AboutSoftwareController@index',
		'as' 	=> 'aboutsoftware'
	));

Route::get('/getactivatebox', array(
		'uses' 	=> 'AboutSoftwareController@getactivatebox',
		'as' 	=> 'getactivatebox'
	));

Route::post('/getactivatebox/activate', array(
		'uses' 	=> 'AboutSoftwareController@postactivatebox',
		'as' 	=> 'postactivatebox'
	));

//Vendors
Route::get('admin/vendors', array(
		'uses' => 'AdminVendorController@index',
		'as' => 'vendors'
	));

Route::post('admin/vendors/save', array(
		'uses' => 'AdminVendorController@save',
		'as' => 'vendor.save'
	));

Route::get('admin/vendors/showedit', array(
		'uses' => 'AdminVendorController@edit',
		'as' => 'vendor.showedit'
	));

Route::post('admin/vendors/saveedit', array(
		'uses' => 'AdminVendorController@saveEdit',
		'as' => 'vendor.saveedit'
	));

Route::post('admin/vendors/delete', array(
		'uses' => 'AdminVendorController@delete',
		'as' => 'vendor.delete'
	));

//STOCK UPDATE RECORDS
Route::get('admin/stockupdaterecords', array(
		'uses'	=>	'AdminStockupdateRecordController@stockUpdate',
		'as'	=>	'stockupdate.record'
	));