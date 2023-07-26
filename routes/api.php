<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\RegistrationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\API\V1\VerifyEmailController;
use App\Http\Controllers\API\V1\RoleController;
use App\Http\Controllers\API\V1\ModuleController;
use App\Http\Controllers\API\V1\TimezonesController;
use App\Http\Controllers\API\V1\DateFormatsController;
use App\Http\Controllers\API\V1\CountriesController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\ClientController;
use App\Http\Controllers\API\V1\LanguageController;

use App\Http\Controllers\API\V1\ContentAdvisoryController;
use App\Http\Controllers\API\V1\BitframeController;
use App\Http\Controllers\API\V1\GenreController;
use App\Http\Controllers\API\V1\SearchSettingController;
use App\Http\Controllers\API\V1\ViewerRatingController;
use App\Http\Controllers\API\V1\PlatformController;

use App\Http\Controllers\API\V1\ChangePasswordController;
use App\Http\Controllers\API\V1\AuditableLogController;
use App\Http\Controllers\API\V1\TwoFactorAuthenticatableController;
use App\Http\Controllers\API\V1\ContentAvailabilityController;
use App\Http\Controllers\API\V1\BroadcasterController;
use App\Http\Controllers\API\V1\ClientSettingsController;
use App\Http\Controllers\API\V1\ClientSmtpSettingsController;
use App\Http\Controllers\API\V1\ClientDeviceRestrictionController;
use App\Http\Controllers\API\V1\S3SettingsController;
use App\Http\Controllers\API\V1\AdsManagementController;
use App\Http\Controllers\API\V1\AnalyticsManagementController;
use App\Http\Controllers\API\V1\AssetTypesController;
use App\Http\Controllers\API\V1\StreamingSettingsController;
use App\Http\Controllers\API\V1\EmailTemplatesController;
use App\Http\Controllers\API\V1\PaymentModesController;
use App\Http\Controllers\API\V1\GroupAndPackageManagementController;
use App\Http\Controllers\API\V1\CityController;
use App\Http\Controllers\API\V1\StateController;
use App\Http\Controllers\API\V1\BankDetailsController;
use App\Http\Controllers\API\V1\SmsSettingController;
//use App\Http\Controllers\API\V1\AwsStorageSettingController;
use App\Http\Controllers\API\V1\WowzaStorageSettingController;
use App\Http\Controllers\API\V1\CloudinaryImageSettingController;
use App\Http\Controllers\API\V1\AwsImageSettingController;
use App\Http\Controllers\API\V1\StorageSettingController;
use App\Http\Controllers\API\V1\ImageSettingsController;
use App\Http\Controllers\API\V1\TranscodingSettingsController;
use App\Http\Controllers\API\V1\DepartmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     return $request->user();
    // });
    
    
Route::group([
    'prefix' => 'v1',
], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('two-factor-authenticate', [TwoFactorAuthenticatableController::class, 'TwoFactorAuthenticate']);
    Route::post('resend-two-factor-auth-code', [TwoFactorAuthenticatableController::class, 'resendotp']);
    Route::post('register', [RegistrationController::class, 'register']);
    Route::post('password/forgot', [RegistrationController::class, 'forgotPassword'])->name('password.request');
    Route::post('password/reset', [RegistrationController::class, 'resetPassword'])->name('password.reset');
});

//Email Verification
Route::get('/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->name('verification.verify')->middleware(['throttle:6,1']);
 
//Resend link to verify email
Route::get('/email/verify/resend', [VerifyEmailController::class, 'resend'])->middleware(['throttle:6,1'])->name('verification.send');

//Routes with authorization
Route::group([
    'prefix' => 'v1',
    'as' => 'api.',
    'middleware' => ['auth:api']
], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::get('user/list', [UserController::class, 'get_user']);
    Route::resource('role', RoleController::class, ['except' => ['create', 'edit']]);
    Route::resource('module', ModuleController::class, ['except' => ['create', 'edit']]);
    Route::resource('timezones', TimezonesController::class, ['except' => ['destroy', 'create', 'edit']]);
    Route::resource('date-formats', DateFormatsController::class);
    Route::resource('countries', CountriesController::class, ['except' => ['destroy']]);
    Route::get('modules/get-parents', [ModuleController::class, 'parentModules']);
    Route::get('modules/get-child/{id}', [ModuleController::class, 'childModules']);
    
    Route::resource('user', UserController::class, ['except' => ['create', 'edit']]);
    Route::get('user/get-permissions/{id}', [UserController::class, 'getUserPermissions']);
    Route::post('user/save-permissions', [UserController::class, 'saveUserPermissions']);
    Route::get('user/get-role-permissions/{role_id}', [UserController::class, 'getRoleBasedPermissions']);
    Route::post('user/create-admin', [UserController::class, 'createAdminUser']);
    Route::post('user/update-profile/{id}', [UserController::class, 'updateProfile']);
    Route::post('user/update-profile-picture/{id}', [UserController::class, 'updateProfilePicture']);
    Route::post('user/update-billing/', [UserController::class, 'updateBilling']);

    Route::get('common/manager-list', [UserController::class, 'getUserManagers']);
    Route::get('common/communication-manager-list/{id}', [UserController::class, 'getUserCommunicationManagers']);
   
    Route::get('client/get-client-user/{id}', [ClientController::class, 'getClinetUserList']);
    Route::resource('client', ClientController::class, ['except' => ['create', 'edit']]);
    Route::post('client/create-client', [ClientController::class, 'createClient']);
    Route::put('client/update-profile/{id}', [ClientController::class, 'updateProfile']);
    Route::post('client/update-profile-picture/{id}', [ClientController::class, 'updateProfilePicture']);
    Route::get('client/get-managers/{userId}', [ClientController::class, 'getClientManagerList']);
    Route::delete('client/delete-profile-picture/{id}', [ClientController::class, 'deleteProfilePicture']);
    Route::post('client/create-client-user', [ClientController::class, 'createClientUser']);
    
    Route::resource('bank-details', BankDetailsController::class, ['except' => ['create', 'edit']]);
    Route::get('bank-details/get-user-bank-details/{id}', [BankDetailsController::class, 'getUserBankDetails']);
    
    Route::get('language/list', [LanguageController::class, 'index']);  
    Route::post('language/create', [LanguageController::class, 'store']);
    Route::get('language/details/{id}', [LanguageController::class, 'details']);
    Route::put('language/edit/{id}', [LanguageController::class, 'update']);
    Route::delete('language/delete/{id}', [LanguageController::class, 'destroy']);

    Route::get('content-advisory/list', [ContentAdvisoryController::class, 'index']);
    Route::post('content-advisory/create', [ContentAdvisoryController::class, 'store']);
    Route::get('content-advisory/details/{id}', [ContentAdvisoryController::class, 'details']);
    Route::put('content-advisory/edit/{id}', [ContentAdvisoryController::class, 'update']);
    Route::delete('content-advisory/delete/{id}', [ContentAdvisoryController::class, 'destroy']);

    Route::get('bitframe/list', [BitframeController::class, 'index']);
    Route::post('bitframe/create', [BitframeController::class, 'store']);
    Route::get('bitframe/details/{id}', [BitframeController::class, 'details']);
    Route::put('bitframe/edit/{id}', [BitframeController::class, 'update']);
    Route::delete('bitframe/delete/{id}', [BitframeController::class, 'destroy']);
    Route::get('bitframe/bitframetype', [BitframeController::class, 'bitframetype']);

    Route::get('genre/list', [GenreController::class, 'index']);
    Route::post('genre/create', [GenreController::class, 'store']);
    Route::get('genre/details/{id}', [GenreController::class, 'details']);
    Route::put('genre/edit/{id}', [GenreController::class, 'update']);
    Route::delete('genre/delete/{id}', [GenreController::class, 'destroy']);

    Route::get('viewer-rating/list', [ViewerRatingController::class, 'index']);
    Route::post('viewer-rating/create', [ViewerRatingController::class, 'store']);
    Route::get('viewer-rating/details/{id}', [ViewerRatingController::class, 'details']);
    Route::put('viewer-rating/edit/{id}', [ViewerRatingController::class, 'update']);
    
    Route::delete('viewer-rating/delete/{id}', [ViewerRatingController::class, 'destroy']);

    Route::get('platform/list', [PlatformController::class, 'index']);
    Route::post('platform/create', [PlatformController::class, 'store']);
    Route::get('platform/details/{id}', [PlatformController::class, 'details']);
    Route::put('platform/edit/{id}', [PlatformController::class, 'update']);
    Route::delete('platform/delete/{id}', [PlatformController::class, 'destroy']);

    Route::post('password/change-password', [ChangePasswordController::class, 'changepassword']);
    
    Route::get('audit/log', [AuditableLogController::class, 'getAuditableLog']);
    Route::get('audit/logs/details/{id}', [AuditableLogController::class, 'details']);
    
    Route::post('two-factor-enable-disable', [TwoFactorAuthenticatableController::class, 'enable_disable_two_fector_auth']);//enable and dissable
    Route::post('two-factor-enable-disable-otp-verify', [TwoFactorAuthenticatableController::class, 'two_factor_otp_verifiy']);//verifiy otp

    Route::get('content-availability/list', [ContentAvailabilityController::class, 'index']);
    Route::post('content-availability/create', [ContentAvailabilityController::class, 'store']);
    Route::get('content-availability/details/{id}', [ContentAvailabilityController::class, 'details']);
    Route::put('content-availability/edit/{id}', [ContentAvailabilityController::class, 'update']);
    Route::delete('content-availability/delete/{id}', [ContentAvailabilityController::class, 'destroy']);

    Route::get('broadcaster/list', [BroadcasterController::class, 'index']);
    Route::post('broadcaster/create', [BroadcasterController::class, 'store']);
    Route::get('broadcaster/details/{id}', [BroadcasterController::class, 'details']);
    Route::put('broadcaster/edit/{id}', [BroadcasterController::class, 'update']);
    Route::delete('broadcaster/delete/{id}', [BroadcasterController::class, 'destroy']);

    Route::resource('client-settings', ClientSettingsController::class, ['except' => ['create', 'edit']]);
    Route::get('client-settings/get-user-settings/{user_id}', [ClientSettingsController::class, 'getUserSettings']);
    Route::get('adsmanagement/list', [AdsManagementController::class, 'index']);
    Route::post('adsmanagement/create', [AdsManagementController::class, 'store']);
    Route::get('adsmanagement/details/{id}', [AdsManagementController::class, 'details']);
    Route::put('adsmanagement/edit/{id}', [AdsManagementController::class, 'update']);
    Route::delete('adsmanagement/delete/{id}', [AdsManagementController::class, 'destroy']);

    Route::get('analytics-management/list', [AnalyticsManagementController::class, 'index']);
    Route::post('analytics-management/create', [AnalyticsManagementController::class, 'store']);
    Route::get('analytics-management/details/{id}', [AnalyticsManagementController::class, 'details']);
    Route::put('analytics-management/edit/{id}', [AnalyticsManagementController::class, 'update']);
    Route::delete('analytics-management/delete/{id}', [AnalyticsManagementController::class, 'destroy']);

    Route::get('asset-type/list', [AssetTypesController::class, 'index']);
    Route::post('asset-type/create', [AssetTypesController::class, 'store']);
    Route::get('asset-type/details/{id}', [AssetTypesController::class, 'details']);
    Route::put('asset-type/edit/{id}', [AssetTypesController::class, 'update']);
    Route::delete('asset-type/delete/{id}', [AssetTypesController::class, 'destroy']);

    Route::get('s3settings/list', [S3SettingsController::class, 'index']);
    Route::post('s3settings/create', [S3SettingsController::class, 'store']);
    Route::get('s3settings/details/{id}', [S3SettingsController::class, 'details']);
    Route::put('s3settings/edit/{id}', [S3SettingsController::class, 'update']);
    Route::delete('s3settings/delete/{id}', [S3SettingsController::class, 'destroy']);

    Route::get('streaming-setting/list', [StreamingSettingsController::class, 'index']);
    Route::post('streaming-setting/create', [StreamingSettingsController::class, 'store']);
    Route::get('streaming-setting/details/{id}', [StreamingSettingsController::class, 'details']);
    Route::put('streaming-setting/edit/{id}', [S3SettingsController::class, 'update']);
    Route::delete('streaming-setting/delete/{id}', [StreamingSettingsController::class, 'destroy']);

    Route::get('payment-mode/list', [PaymentModesController::class, 'index']);
    Route::post('payment-mode/create', [PaymentModesController::class, 'store']);
    Route::get('payment-mode/details/{id}', [PaymentModesController::class, 'details']);
    Route::put('payment-mode/edit/{id}', [PaymentModesController::class, 'update']);
    Route::delete('payment-mode/delete/{id}', [PaymentModesController::class, 'destroy']);

    Route::get('group-packege/list', [GroupAndPackageManagementController::class, 'index']);
    Route::post('group-packege/create', [GroupAndPackageManagementController::class, 'store']);
    Route::get('group-packege/details/{id}', [GroupAndPackageManagementController::class, 'details']);
    Route::put('group-packege/edit/{id}', [GroupAndPackageManagementController::class, 'update']);
    Route::delete('group-packege/delete/{id}', [GroupAndPackageManagementController::class, 'destroy']);

    Route::get('state/list', [StateController::class, 'index']);
    Route::post('state/create', [StateController::class, 'store']);
    Route::get('state/details/{id}', [StateController::class, 'details']);
    Route::put('state/edit/{id}', [StateController::class, 'update']);
    //Route::delete('state/delete/{id}', [StateController::class, 'destroy']);
    Route::post('state/list-by-country-id/', [StateController::class, 'getStateListByCountryId']);

    Route::get('city/list', [CityController::class, 'index']);
    Route::post('city/create', [CityController::class, 'store']);
    Route::get('city/details/{id}', [CityController::class, 'details']);
    Route::put('city/edit/{id}', [CityController::class, 'update']);
    Route::delete('city/delete/{id}', [CityController::class, 'destroy']);
    Route::post('city/list-by-state-id/', [CityController::class, 'getCityListByStateId']);

    //Advance Setting
    Route::post('smtp-setting/details/', [ClientSmtpSettingsController::class, 'show']);
    Route::put('smtp-setting/edit/', [ClientSmtpSettingsController::class, 'update']);

    Route::post('device-restrictions/details/', [ClientDeviceRestrictionController::class, 'show']);
    Route::put('device-restrictions/edit/', [ClientDeviceRestrictionController::class, 'update']);
    
    Route::get('storage-setting/details/{id}', [StorageSettingController::class, 'details']);
    Route::put('storage-setting/edit/', [StorageSettingController::class, 'update']);
    
    Route::post('cloudinary-image-setting/details/', [CloudinaryImageSettingController::class, 'show']);
    Route::put('cloudinary-image-setting/edit/', [CloudinaryImageSettingController::class, 'update']);

    Route::post('aws-image-setting/details/', [AwsImageSettingController::class, 'show']);
    Route::put('aws-image-setting/edit/', [AwsImageSettingController::class, 'update']);
    Route::get('aws-image-setting/imagetype', [AwsImageSettingController::class, 'ImageStorageType']);

    Route::post('email-template/details/', [EmailTemplatesController::class, 'show']);
    Route::put('email-template/edit/', [EmailTemplatesController::class, 'update']);

    Route::post('sms-setting/details/', [SmsSettingController::class, 'show']);
    Route::put('sms-setting/edit/', [SmsSettingController::class, 'update']);

    Route::get('search-settings/details/{id}', [SearchSettingController::class, 'details']);
    Route::put('search-settings/edit/', [SearchSettingController::class, 'update']);
   // Route::get('search-setting/searchtype', [SearchSettingController::class, 'searchType']);

    Route::get('image-settings/details/{id}', [ImageSettingsController::class, 'show']);
    Route::put('image-settings/edit/', [ImageSettingsController::class, 'update']);

    Route::get('transcoding-settings/details/{id}', [TranscodingSettingsController::class, 'show']);
    Route::put('transcoding-settings/edit/', [TranscodingSettingsController::class, 'update']);
    Route::get('department-list', [DepartmentController::class, 'index']);

});
