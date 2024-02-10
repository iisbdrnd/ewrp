<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('javascript', array('as'=>'setUrl', 'uses'=>'AdapterController@javascript'));
Route::get('/cache-clear', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

//File Upload //
Route::post('galleryFileUpload', array('as' => 'custom.fileUpload', 'uses' => 'GalleryUploadController@fileUpload'));

Route::post('ashramFileUpload', array('as' => 'custom.fileUpload', 'uses' => 'AshramUploadController@fileUpload'));
Route::post('headOfficeFileUpload', array('as' => 'custom.HeadOfficefileUpload', 'uses' => 'AshramUploadController@fileUpload'));

// Route::post('customFileUpload', array('as' => 'custom.fileUpload', 'uses' => 'FileUploadController@fileUpload'));
Route::post('customFileUnlink', array('as' => 'custom.fileUnlink', 'uses' => 'FileUploadController@fileUnlink'));
Route::get('multipleFileUnlink', array('as' => 'custom.multipleFileUnlink', 'uses' => 'FileUploadController@multipleFileUnlink'));
// Front end //
Route::group(['namespace' => 'Web'], function (){
    Route::get('/', array('as'=>'home', 'uses'=>'FrontEndController@home'));
    Route::get('/company-history', array('as'=>'companyHistory', 'uses'=>'FrontEndController@companyHistory'));
    Route::get('/facilities', array('as'=>'facilities', 'uses'=>'FrontEndController@facilities'));
    Route::get('/services', array('as'=>'services', 'uses'=>'FrontEndController@services'));
    Route::get('/missionVision', array('as'=>'missionVision', 'uses'=>'FrontEndController@missionVision'));
    Route::get('/organizationChart', array('as'=>'organizationChart', 'uses'=>'FrontEndController@organizationChart'));
    Route::get('/ourClients', array('as'=>'ourClients', 'uses'=>'FrontEndController@ourClients'));
    Route::get('/contact', array('as'=>'contact', 'uses'=>'FrontEndController@contact'));
    Route::post('/contactUsAction', array('as'=>'contactUsAction', 'uses'=>'FrontEndController@contactUsAction'));
    Route::get('/galleryAlbum', array('as'=>'galleryAlbum', 'uses'=>'FrontEndController@galleryAlbum'));
    Route::get('/galleryPhotos/{gallery_id}', array('as'=>'galleryPhotos', 'uses'=>'FrontEndController@galleryPhotos'));
    Route::get('/jobOpening', array('as'=>'jobOpening', 'uses'=>'FrontEndController@jobOpening'));
    Route::get('/viewAjaxJobList', array('as'=>'viewAjaxJobList', 'uses'=>'FrontEndController@viewAjaxJobList'));
    Route::get('/jobDetails/{job_id}', array('as'=>'jobDetails', 'uses'=>'FrontEndController@jobDetails'));
    Route::get('/license', array('as'=>'license', 'uses'=>'FrontEndController@license'));
    Route::get('/viewSingleLicense', array('as'=>'viewSingleLicense', 'uses'=>'FrontEndController@viewSingleLicense'));
    Route::get('/licenseDetails/{license_id}', array('as'=>'licenseDetails', 'uses'=>'FrontEndController@licenseDetails'));
    Route::get('/allJobCategory', array('as'=>'allJobCategory', 'uses'=>'FrontEndController@allJobCategory'));
    Route::get('/termsAndConditions', array('as'=>'termsAndConditions', 'uses'=>'FrontEndController@termsAndConditions'));
    Route::get('/noticeBoard', array('as'=>'noticeBoard', 'uses'=>'FrontEndController@noticeBoard'));
    Route::get('/viewAjaxNotice', array('as'=>'viewAjaxNotice', 'uses'=>'FrontEndController@viewAjaxNotice'));
    
    // EMAIL VERIFICATION
    Route::get('email_verification', array('as'=>'emailVerification', 'uses'=>'MasterController@emailVerification'));
	Route::post('email_verification_action', array('as'=>'emailVerificationAction', 'uses'=>'MasterController@emailVerificationAction'));

    Route::get('login', array('as'=>'login', 'uses'=>'MasterController@getLogin'));
    Route::post('login', array('as'=>'login', 'uses' => 'MasterController@postLogin'));
    Route::get('logout', array('as'=>'logout', 'uses' => 'MasterController@logout'));
    Route::get('forget-password', array('as'=>'forgetPassword', 'uses' => 'MasterController@forgetPassword'));
    Route::post('forget-password', array('as'=>'forgetPasswordAction', 'uses' => 'MasterController@forgetPasswordAction'));
    Route::get('forgot-email-verification', array('as'=>'forgetEmailVerification', 'uses' => 'MasterController@forgotEmailVerification'));
    Route::post('forgot-email-verification-action', array('as'=>'forgotEmailVerificationAction', 'uses' => 'MasterController@forgotEmailVerificationAction'));
    Route::get('confirmation', array('as'=>'confirmation', 'uses'=>'MasterController@confirmation'));


    Route::get('profile/{id}', array('as'=>'profile', 'uses' => 'MasterController@profile'));
    Route::put('profileUpdate', array('as'=>'profileUpdate', 'uses' => 'MasterController@profileUpdate'));
    Route::get('cropImagePage/{id}', array('as'=>'cropImagePage', 'uses' => 'MasterController@cropImagePage'));
    Route::post('saveImage', array('as'=>'saveImage', 'uses' => 'MasterController@saveImage'));
    Route::post('saveProfile', array('as'=>'saveProfile', 'uses' => 'MasterController@saveProfile'));
});


