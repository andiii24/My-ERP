<?php

Route::get('/merchandises/level/warehouse/{warehouse}', 'MerchandiseInventoryLevelController@getCurrentMerchandiseLevelByWarehouse');

Route::post('/transfers/{transfer}/transfer', 'TransferController@transfer')->name('transfers.transfer');

Route::resource('suppliers', 'SupplierController');

Route::resource('warehouses', 'WarehouseController');

Route::resource('customers', 'CustomerController');

Route::resource('gdns', 'GdnController');

Route::resource('transfers', 'TransferController');
