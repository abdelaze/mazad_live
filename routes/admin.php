<?php
const ASSET_PATH = '';
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\{OnboardingControler,AttributeController,SubAttributeController,BrandAttribute,PrivacyPolicyController,UsagePolicyController,RoleController,UserController,AdsController, BrandsController, CategoryController,SubcategoryController,MazdatController, ProductController};

//Route::group(['namespace' =>'Admin','middleware' => 'auth:web'],function (){



Route::group(['namespace' =>'Admin','middleware' => 'auth:admin'],function (){
    // Admin routes go here.
    Route::get('/dashboard','DashboardController@index')->name('admin.dashboard');
    Route::get('logout', 'DashboardController@logout')->name('admin.logout');
});

Route::group(['namespace' =>'Admin\Auth','middleware' => 'guest:admin'],function (){
    Route::get('/login',  'LoginController@showAdminLoginForm')->name('get.admin.login');
    Route::post('/login', 'LoginController@adminLogin')->name('admin.login');
});
//Route::group(['middleware' => ['auth:admin']], function() {

Route::group(['middleware' => ['auth:admin']], function() {
    Route::resource('roles',            RoleController::class);
    Route::resource('users',            UserController::class);
    Route::get('admins',           [UserController::class , 'admins'])->name('admins.index');
    Route::post('users/update/status', [UserController::class , 'updateUserStatus'])->name('admin.update_user_status');
    Route::post('admins/update/status', [UserController::class , 'updateAdminStatus'])->name('admin.update_admin_status');
    Route::resource('onboardings',      OnboardingControler::class);
    Route::resource('categories',       CategoryController::class);
    Route::resource('mazdats',          MazdatController::class);
    Route::resource('products',         ProductController::class);
    Route::get('products/all/bills',                       [ProductController::class , 'getUserBills'])->name('product_user.bills');
    Route::get('user_products/show/bill/details/{user_id}',       [ProductController::class , 'showUserBillDetails'])->name('product_user.bill.details');
    Route::post('add/product/bill',                              [ProductController::class , 'addUserBill'])->name('product_user.pay.bill');
    Route::resource('brands',           BrandsController::class);
    Route::resource('usage_policies',   UsagePolicyController::class);
    Route::resource('privacy_policies', PrivacyPolicyController::class);
    Route::resource('abouts'           , AboutController::class);
    Route::post('mazdats/update/status', [MazdatController::class , 'updateMazadStatus'])->name('admin.update_maza_status');
    Route::post('product/update/status', [ProductController::class , 'updateProductStatus'])->name('admin.update_product_status');
    Route::get('mazdats/all/bills',                       [MazdatController::class , 'getUserBills'])->name('mazad_user.bills');
    Route::get('user_mazadats/show/bill/details/{user_id}',       [MazdatController::class , 'showUserBillDetails'])->name('mazad_user.bill.details');
    Route::post('add/mazad/bill',                              [MazdatController::class , 'addUserBill'])->name('mazad_user.pay.bill');
    
    
    Route::get('/file-import',[CategoryController::class,'importView'])->name('cat-import-view');
    Route::post('/import',[CategoryController::class,'import'])->name('import-cat');
    Route::get('/export-cats',[CategoryController::class,'exportCategories'])->name('export-cats');
    Route::get('category/show_atributes/{cat_id}', [CategoryController::class,'showAttributes'] )->name('category.show_attributes');


    Route::get('subcat/file-import',[SubcategoryController::class,'importView'])->name('subcat-import-view');
    Route::post('subcat/import',[SubcategoryController::class,'import'])->name('import-subcat');
    Route::get('subcat/export-subcats',[SubcategoryController::class,'exportSubCategories'])->name('export-subcats');

    Route::get('brand-import/file-import',[BrandsController::class,'importView'])->name('brand-import-view');
    Route::post('brand-import/import',[BrandsController::class,'import'])->name('import-brand');
    Route::get('brand-export/export-brands',[BrandsController::class,'exportBrands'])->name('export-brands');

    
    Route::post('categories/sort',   [CategoryController::class , 'SortCategories'])->name('categories.sort');
    Route::post('/categories/subcategories', [CategoryController::class , 'getSubcategories'])->name('categories.subcat');
    Route::resource('subcategories', SubcategoryController::class);
    Route::post('subcategories/sort',   [SubCategoryController::class , 'SortSubCategories'])->name('subcategories.sort');
    Route::resource('ads',           AdsController::class);


     /** categories attributes */
    Route::resource('attributes',AttributeController::class);
    Route::post('attributes/preview-input',[AttributeController::class,'preview'])->name('attributes.inputs.preview');
    Route::get('attribute/radio-input',[AttributeController::class,'getRadioInput'])->name('attributes.radio.input');
    Route::get('attribute/checkbox-input',[AttributeController::class,'getCheckboxInput'])->name('attributes.checkbox.input');
    Route::get('attribute/select-input',[AttributeController::class,'getSelectInput'])->name('attributes.select.input');
    Route::get('get-attributes-category',[AttributeController::class,'getAttributesCategory'])->name('attributes.category');

     /** brands attributes */
     Route::resource('brand-attributes',BrandAttribute::class);
     Route::post('brand-attributes/preview-input',[BrandAttribute::class,'preview'])->name('brand-attributes.inputs.preview');
     Route::get('brand-attribute/radio-input',[BrandAttribute::class,'getRadioInput'])->name('brand-attributes.radio.input');
     Route::get('brand-attribute/checkbox-input',[BrandAttribute::class,'getCheckboxInput'])->name('brand-attributes.checkbox.input');
     Route::get('brand-attribute/select-input',[BrandAttribute::class,'getSelectInput'])->name('brand-attributes.select.input');
     Route::get('brand-get-attributes',[BrandAttribute::class,'getAttributesBrand'])->name('attributes.brand');
 
     /** subcategories attributes */
     Route::resource('sub-attributes',SubAttributeController::class);
     Route::post('sub-attributes/preview-input',[SubAttributeController::class,'preview'])->name('sub-attributes.inputs.preview');
     Route::get('sub-attribute/radio-input',[SubAttributeController::class,'getRadioInput'])->name('sub-attributes.radio.input');
     Route::get('sub-attribute/checkbox-input',[SubAttributeController::class,'getCheckboxInput'])->name('sub-attributes.checkbox.input');
     Route::get('sub-attribute/select-input',[SubAttributeController::class,'getSelectInput'])->name('sub-attributes.select.input');
     Route::get('get-attributes-subcategory',[SubAttributeController::class,'getAttributesSubCategory'])->name('attributes.subcategory');
   
     // clients 

     Route::get('edit/profile/{admin}', [UserController::class,'editProfile'])->name('admin.edit_profile');
     Route::put('update/profile/{admin}', [UserController::class,'updateProfile'])->name('admin.update_profile');
     
});


