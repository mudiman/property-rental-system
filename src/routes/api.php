<?php

use Illuminate\Http\Request;

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


Route::post('users/register', 'UserAPIController@store')->name('user.register');
Route::get('users/verify/{user}/{verification}', 'UserAPIController@verifyEmailRegistrationCode')->name('user.verify');
Route::get('users/resend_verification/{user}', 'UserAPIController@resendVerificationLink')->name('user.resend.verify');
Route::post('users/email/exists', 'UserAPIController@checkUserExists')->name('user.email.exist');

Route::get('users/send_verification_sms/{user}', 'UserAPIController@sendVerificationSmsCode')->name('user.send.verify.sms');
Route::post('users/verify_sms_code/{user}', 'UserAPIController@verifySMSPasswordCode')->name('user.verify.sms.code');

Route::post('users/send_forgot_password_link', 'UserAPIController@sendForgotPasswordLink')->name('user.send.forgotpassword.link');

Route::group(['middleware' => 'web'], function() {
    Route::get('users/forgot_password_link/{user}/{code}', 'UserAPIController@forgotPasswordLink')->name('user.forgotpassword.link');
    Route::post('users/update_password/{user}/{code}', 'UserAPIController@updateForgotPasswordCode')->name('user.update.password');
});


Route::group(['middleware' => 'auth:api'], function() {
  Route::resource('users', 'UserAPIController', ['only' => ['index', 'show', 'update']]);
  Route::get('users/verify_id/{id}', 'UserAPIController@checkUserIdWithTokenMatch')->name('users.token.match');
  Route::post('users/{id}/logout', 'UserAPIController@logout')->name('users.logout');
  
  Route::resource('agencies', 'AgencyAPIController');
  Route::resource('agents', 'AgentAPIController');
  
  Route::resource('property_pros', 'PropertyProAPIController');
  Route::resource('property_services', 'PropertyServiceAPIController');
  Route::post('property_services/bulk', 'PropertyServiceAPIController@storeBulk')->name('property_services.store.bulk');
  
  Route::resource('likes', 'LikeAPIController');
  
  Route::resource('viewings', 'ViewingAPIController');
  Route::post('viewings/bulk', 'ViewingAPIController@storeBulk')->name('viewings.store.bulk');
  Route::post('viewings/destroy_bulk', 'ViewingAPIController@destroyBulk')->name('viewings.destroy.bulk');
  Route::resource('viewing_requests', 'ViewingRequestAPIController');
  
  Route::resource('properties', 'PropertyAPIController');
  Route::get('properties/{id}/statistics', 'PropertyAPIController@showStatistics')->name('properties.show.statistics');
  Route::resource('references', 'ReferenceAPIController');
  Route::resource('statistics', 'StatisticAPIController');
  
  Route::get('messages/all', 'MessageAPIController@all')->name('messages.all');
  Route::resource('messages', 'MessageAPIController');
  Route::resource('threads', 'ThreadAPIController');
  Route::post('participants/threads', 'ThreadAPIController@participantsThread')->name('threads.participants');
  Route::resource('participants', 'ParticipantAPIController');
  
  Route::resource('reviews', 'ReviewAPIController');
  
  Route::resource('tenancies', 'TenancyAPIController', ['only' => ['index', 'show', 'update']]);
  Route::post('tenancies/{id}/pay', 'TenancyAPIController@pay')->name('tenancies.pay');
  
  Route::resource('offers', 'OfferAPIController');
  Route::get('offers/{id}/security_deposit', 'OfferAPIController@securityDeposit')->name('offers.security_deposit');
  
  Route::resource('documents', 'DocumentAPIController');
  Route::get('documents/{document}/document', 'DocumentAPIController@showDocument');
  Route::get('documents/{document}/document_front', 'DocumentAPIController@showDocumentFront');
  Route::get('documents/{document}/document_back', 'DocumentAPIController@showDocumentBack');
  
  Route::resource('images', 'ImageAPIController');
  
  Route::resource('premium_listings', 'PremiumListingAPIController');
  
  Route::resource('transactions', 'TransactionAPIController', ['only' => ['index', 'show']]);
  Route::resource('payins', 'PayinAPIController');
  Route::resource('payouts', 'PayoutAPIController');
  
  Route::resource('services', 'ServiceAPIController');
  Route::resource('user_services', 'UserServiceAPIController');
  
  Route::resource('events', 'EventAPIController', ['only' => ['index', 'show', 'update']]);
  
  Route::resource('devices', 'DeviceAPIController');
  Route::resource('reports', 'ReportAPIController');
  Route::resource('extras', 'ExtraAPIController');
  Route::resource('feedback', 'FeedbackAPIController');
  Route::resource('scores', 'ScoreAPIController');
  
  Route::get('service_types', 'ServiceTypeAPIController@index');
  Route::get('payment_methods', 'PaymentMethodAPIController@index');
  Route::get('property_room_types', 'PropertyRoomTypeAPIController@index');
  Route::get('bathroom_types', 'BathroomTypeAPIController@index');
  Route::get('roles', 'RoleAPIController@index');
  Route::get('letting_types', 'LettingTypeAPIController@index');
  Route::get('service_fee_types', 'ServiceFeeTypeAPIController@index');
  Route::get('document_types', 'DocumentTypeAPIController@index');
  Route::get('score_types', 'ScoreTypeAPIController@index');

  Route::resource('histories', 'HistoryAPIController', ['only' => ['index', 'show']]);
  Route::resource('settings', 'SettingAPIController', ['only' => ['index', 'show', 'update']]);
});



