<?php

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



Route::get('/', function () {
  return redirect('/login');
});
Auth::routes();
Route::group(['middleware' => 'auth'], function() {
  Route::get('/home', 'HomeController@index')->name('home');
  Route::resource('users', 'UserController');
  Route::resource('userServices', 'UserServiceController');
  Route::resource('devices', 'DeviceController');
  
  Route::resource('agencies', 'AgencyController');
  Route::resource('agents', 'AgentController');
  
  Route::resource('viewings', 'ViewingController');
  Route::resource('viewingRequests', 'ViewingRequestController');
  
  Route::resource('properties', 'PropertyController');
  Route::resource('likes', 'LikeController');
  
  Route::resource('tenancies', 'TenancyController');
  Route::get('tenancies/{id}/timeshift', 'TenancyController@timeShiftTenancy')->name('tenancies.timeshift');
  Route::get('tenancies/{id}/payout', 'TransactionController@payout')->name('tenancies.payout');
  Route::resource('offers', 'OfferController');
  
  Route::resource('messages', 'MessageController');
  Route::resource('threads', 'ThreadController');
  Route::resource('participants', 'ParticipantController');
  Route::get('messages/client/show', 'MessageController@client')->name('messages.client');
  
  Route::resource('propertyPros', 'PropertyProController');
  Route::resource('propertyServices', 'PropertyServiceController');

  Route::resource('documents', 'DocumentController');
  Route::get('documents/{document}/document/{type}', 'DocumentController@showDocument')->name('documents.get.document');
  Route::resource('images', 'ImageController');
  
  
  Route::resource('transactions', 'TransactionController');
  Route::get('transactions/{id}/dividen', 'TransactionController@performDividen')->name('transactions.dividen');
  Route::resource('payins', 'PayinController');
  Route::resource('payouts', 'PayoutController');
  
  Route::resource('premiumListings', 'PremiumListingController');
  
  Route::resource('references', 'ReferenceController');
  Route::resource('reviews', 'ReviewController');
  Route::resource('reports', 'ReportController');
  Route::resource('feedback', 'FeedbackController');
  Route::resource('scores', 'ScoreController');
  Route::resource('events', 'EventController');
  
  Route::resource('services', 'ServiceController');
  Route::resource('serviceTypes', 'ServiceTypeController');
  Route::resource('serviceFeeTypes', 'ServiceFeeTypeController');
  
  Route::resource('paymentMethods', 'PaymentMethodController');
  Route::resource('scoreTypes', 'ScoreTypeController');
  Route::resource('premiumListings', 'PremiumListingController');
  Route::resource('propertyRoomTypes', 'PropertyRoomTypeController');
  Route::resource('bathroomTypes', 'BathroomTypeController');
  Route::resource('documentTypes', 'DocumentTypeController');
  Route::resource('roles', 'RoleController');
  Route::resource('lettingTypes', 'LettingTypeController');
  Route::resource('extras', 'ExtraController');
  Route::resource('statistics', 'StatisticController');
  
  Route::resource('settings', 'SettingController', ['only' => ['edit', 'update']]);
  
  Route::resource('histories', 'HistoryController');
});





