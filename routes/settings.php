<?php

Route::resource('products', 'ProductController');

Route::get('/product/uom/{product}', 'ProductController@getProductUOM');

Route::resource('categories', 'ProductCategoryController');

Route::resource('employees', 'EmployeeController');

Route::resource('permissions', 'PermissionController');

Route::resource('companies', 'CompanyController');

Route::resource('suppliers', 'SupplierController');

Route::resource('purchases', 'PurchaseController');

Route::resource('sales', 'SaleController');

Route::resource('warehouses', 'WarehouseController');

Route::resource('merchandises', 'MerchandiseController');
Route::post('merchandises/add-to-inventory/{purchase}', 'MerchandiseController@addToInventory')
    ->name('merchandises.addToInventory');

Route::resource('customers', 'CustomerController');

Route::get('/permission-denied', 'ErrorPageController@getPermissionDeniedPage');

Route::get('/home', 'HomeController@index')->name('home');
