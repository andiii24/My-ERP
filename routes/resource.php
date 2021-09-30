<?php

use App\Http\Controllers as Controllers;
use App\Http\Controllers\Resource as Resource;
use Illuminate\Support\Facades\Route;

Route::resource('products', Resource\ProductController::class);

Route::resource('categories', Resource\ProductCategoryController::class);

// TODO
Route::resource('employees', Controllers\EmployeeController::class);

Route::resource('companies', Resource\CompanyController::class);

Route::resource('purchases', Resource\PurchaseController::class);

Route::resource('sales', Resource\SaleController::class);

Route::resource('notifications', Resource\NotificationController::class);

Route::resource('suppliers', Controllers\SupplierController::class);

Route::resource('warehouses', Controllers\WarehouseController::class);

Route::resource('customers', Controllers\CustomerController::class);

Route::resource('gdns', Controllers\GdnController::class);

Route::resource('transfers', Controllers\TransferController::class);

Route::resource('purchase-orders', Controllers\PurchaseOrderController::class);

Route::resource('grns', Controllers\GrnController::class);

Route::resource('general-tender-checklists', Controllers\GeneralTenderChecklistController::class);

Route::resource('tender-checklist-types', Controllers\TenderChecklistTypeController::class);

Route::resource('tender-statuses', Controllers\TenderStatusController::class);

Route::resource('tenders', Controllers\TenderController::class);

Route::resource('tender-checklists', Controllers\TenderChecklistController::class);

Route::resource('sivs', Controllers\SivController::class);

Route::resource('proforma-invoices', Controllers\ProformaInvoiceController::class);

Route::resource('damages', Controllers\DamageController::class);

Route::resource('adjustments', Controllers\AdjustmentController::class);

Route::resource('returns', Controllers\ReturnController::class);

Route::resource('reservations', Controllers\ReservationController::class);
