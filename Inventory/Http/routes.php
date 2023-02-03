<?php
//Route::group(['middleware' => ['web', 'authh', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu'], 'prefix' => 'asset', 'namespace' => 'Modules\AssetManagement\Http\Controllers'], function () {



    Route::get('install', 'InstallController@index');
    Route::post('install', 'InstallController@install');
    Route::get('install/uninstall', 'InstallController@uninstall');
    Route::get('install/update', 'InstallController@update');


    Route::get("inventory" , "inventory@index")->name("inventory");
    Route::post("createNewInventory" , "inventory@createNewInventory");
    Route::get("showInventoryList" , "inventory@showInventoryList")->name("showInventoryList");
    Route::get("makeInventory/{id}" , "inventory@makeInevtory");
    Route::get("inventory/get_products/{id}" , "inventory@getProducts");
    Route::post("inventory/get_purchase_entry_row" , "inventory@getPurchaseEntryRow");
    Route::post("updateProductQuantity" , "inventory@updateProductQuantity");
    Route::post("saveInventoryProducts" , "inventory@saveInventoryProducts");
    Route::get("showInventoryReports/{id}/{branch_id}" , "inventory@showInventoryReports");
    Route::get("inventoryIncreaseReports/{inventory_id}/{branch_id}" , "inventory@inventoryIncreaseReports");
    Route::get("inventoryDisabilityReports/{inventory_id}/{branch_id}" , "inventory@inventoryDisabilityReports");


//});
