<?php
/*
|--------------------------------------------------------------------------
| Software Admin Panel
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'softAdmin', 'as'=>'softAdmin.'], function (){
    Route::get('javascript', array('as'=>'setUrl', 'uses'=>'AdapterController@javascript_softAdmin'));

    Route::group(['namespace' => 'SoftAdmin'], function (){
        Route::get('/', ['as'=>'master', function (){  return redirect()->route('softAdmin.content'); }]);
        Route::get('login', ['as'=>'login', 'uses'=>'MasterController@getLogin']);
        Route::post('login', ['as'=>'login', 'uses'=>'MasterController@postLogin']);
        Route::get('logout', ['as'=>'logout', 'uses'=>'MasterController@logout']);
        Route::group(['middleware' => 'adminAuth'], function (){
            Route::get('content', ['as' => 'content', 'uses' => 'MasterController@index']);
            Route::group(['middleware' => 'adminAccess'], function () {
                Route::get('dashboard', array('as'=>'dashboard', 'uses'=>'DashboardController@index'));

                Route::resource('admin', 'AdminController');
                Route::get('adminListData', array('access' => ['resource|admin.index'], 'uses' => 'AdminController@adminListData'));

                Route::get('adminAccess', array('as' => 'adminAccess', 'uses' => 'AdminAccessController@adminAccess'));
                Route::post('adminAccess', array('as' => 'adminAccess', 'access' => ['adminAccess'], 'uses' => 'AdminAccessController@adminAccessAction'));
                Route::get('adminAccessView', array('as' => 'adminAccessView', 'access' => ['adminAccess'], 'uses' => 'AdminAccessController@adminAccessView'));

                Route::resource('projectRegistration', 'ProjectController');
                Route::get('projectList', array('access' => ['resource|projectRegistration.index'], 'uses' => 'ProjectController@projectList'));

                Route::get('projectIdView', array('access' => ['resource|projectRegistration.index'], 'uses' => 'ProjectController@projectIdView'));
                Route::get('projectRenew', array('as' => 'projectRenew', 'access' => ['resource|projectRegistration.index'], 'uses' => 'ProjectController@projectRenew'));
                Route::put('projectRenewAc', array('as' => 'projectRenewAc', 'access' => ['resource|projectRegistration.index'], 'uses' => 'ProjectController@projectRenewAc'));

                Route::get('projectAccess', array('as' => 'projectAccess', 'uses' => 'ProjectAccessController@projectAccess'));
                Route::post('projectAccess', array('as' => 'projectAccess', 'access' => ['projectAccess'], 'uses' => 'ProjectAccessController@projectAccessAction'));
                Route::get('projectAccessMenuView', array('as' => 'projectAccessMenuView', 'access' => ['projectAccess'], 'uses' => 'ProjectAccessController@projectAccessMenuView'));
                Route::get('projectAccessView', array('as' => 'projectAccessView', 'access' => ['projectAccess'], 'uses' => 'ProjectAccessController@projectAccessView'));
                Route::get('projectIdView', array('access' => ['resource|projectRegistration.create'], 'uses' => 'ProjectController@projectIdView'));

                Route::get('projectNameById', array('as' => 'projectNameById', 'access' => ['resource|user.create', 'resource|user.edit'], 'uses' => 'ProjectController@projectNameById'));
                Route::get('projectUserById', array('as' => 'projectUserById', 'access' => ['resource|user.create', 'resource|user.edit'], 'uses' => 'ProjectController@projectUserById'));
                Route::get('projectDesignationById', array('as' => 'projectDesignationById', 'access' => ['resource|user.create', 'resource|user.edit'], 'uses' => 'ProjectController@projectDesignationById'));
                Route::get('projectAreaById', array('as' => 'projectAreaById', 'access' => ['resource|user.create', 'resource|user.edit'], 'uses' => 'ProjectController@projectAreaById'));

                Route::resource('user', 'UserController');
                Route::get('userList', array('as' => 'userList', 'access' => ['resource|user.index'], 'uses' => 'UserController@userList'));
                Route::get('userLogin', array('as' => 'userLogin', 'uses' => 'UserController@userLogin'));

                Route::get('userEmailResend', array('as' => 'userEmailResend', 'access' => ['resource|user.index'], 'uses' => 'UserController@userEmailResend'));

                Route::resource('designation', 'DesignationController');
                Route::get('designationListData', array('access' => ['resource|designation.index'], 'uses' => 'DesignationController@designationListData'));

                Route::resource('jobArea', 'JobAreaController');
                Route::get('jobAreaListData', array('access' => ['resource|jobArea.index'], 'uses' => 'JobAreaController@jobAreaListData'));

                //Mail Configaration
                Route::get('projectMailConfiguration', array('access' => ['resource|projectRegistration.index'], 'as' => 'projectMailConfiguration', 'uses' => 'ProjectMailConfigurationController@create'));
                Route::put('projectMailConfigurationAc', array('access' => ['resource|projectRegistration.index'], 'as' => 'projectMailConfigurations', 'uses' => 'ProjectMailConfigurationController@store'));
                Route::get('projectMailConfigurationTest', array('access' => ['resource|projectRegistration.index'], 'uses' => 'ProjectMailConfigurationController@configarationTest'));

                //Mail Configaration
                Route::get('crmMailConfigaration', array('as' => 'crmMailConfigaration', 'uses' => 'CrmMailConfigarationController@create'));
                Route::put('crmMailConfigarationAc', array('access' => ['crmMailConfigaration'], 'as' => 'crmMailConfigarations', 'uses' => 'CrmMailConfigarationController@store'));
                Route::get('crmMailConfigarationTest', array('access' => ['crmMailConfigaration'], 'uses' => 'CrmMailConfigarationController@configarationTest'));

                Route::get('userAccess', array('as' => 'userAccess', 'uses' => 'UserAccessController@userAccess'));
                Route::post('userAccess', array('as' => 'userAccess', 'access' => ['userAccess'], 'uses' => 'UserAccessController@userAccessAction'));
                Route::get('userAccessMenuView', array('as' => 'userAccessMenuView', 'access' => ['userAccess'], 'uses' => 'UserAccessController@userAccessMenuView'));
                Route::get('userAccessView', array('as' => 'userAccessView', 'access' => ['userAccess'], 'uses' => 'UserAccessController@userAccessView'));

                Route::resource('adminMenu', 'AdminMenuController');
                Route::get('adminMenuList', array('access' => ['resource|adminMenu.index'], 'uses' => 'AdminMenuController@adminMenuList'));

                Route::get('adminMenuSorting', array('as' => 'adminMenuSorting', 'uses' => 'AdminMenuController@adminMenuSorting'));
                Route::post('adminMenuSorting', array('as' => 'adminMenuSorting', 'access' => ['adminMenuSorting'], 'uses' => 'AdminMenuController@adminMenuSortingAction'));

                Route::resource('adminInternalLink', 'AdminInternalLinkController');
                Route::get('adminInternalLinkList', array('access' => ['resource|adminInternalLink.index'], 'uses' => 'AdminInternalLinkController@adminInternalLinkList'));

                Route::resource('softwareMenu', 'SoftwareMenuController');
                Route::get('softwareMenuList', array('access' => ['resource|softwareMenu.index'], 'uses' => 'SoftwareMenuController@softwareMenuList'));

                Route::get('softwareMenuSorting', array('as' => 'softwareMenuSorting', 'uses' => 'SoftwareMenuController@softwareMenuSorting'));
                Route::get('softwareMenuSortingMenuList', array('as' => 'softwareMenuSortingMenuList', 'access' => ['softwareMenuSorting'], 'uses' => 'SoftwareMenuController@softwareMenuSortingMenuList'));
                Route::post('softwareMenuSorting', array('as' => 'softwareMenuSorting', 'access' => ['softwareMenuSorting'], 'uses' => 'SoftwareMenuController@softwareMenuSortingAction'));

                Route::resource('softwareModule', 'SoftwareModuleController');
                Route::get('softwareModuleList', array('access' => ['resource|softwareModule.index'], 'uses' => 'SoftwareModuleController@softwareModuleList'));

                Route::resource('softwareInternalLink', 'SoftwareInternalLinkController');
                Route::get('softwareInternalLinkList', array('access' => ['resource|softwareInternalLink.index'], 'uses' => 'SoftwareInternalLinkController@softwareInternalLinkList'));

                Route::get('softwareLinkMenu', array('as' => 'softwareLinkMenu', 'access' => ['resource|softwareInternalLink.create'], 'uses' => 'SoftwareInternalLinkController@softwareLinkMenu'));
            });
        });
    });
});

/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {  return redirect()->route('apps'); });
//Clear configurations:
Route::get('/config-clear', function () {
    $status = Artisan::call('config:clear');
    return '<h1>Configurations cleared</h1>';
});

//Clear cache:
Route::get('/cache-clear', function () {
    $status = Artisan::call('cache:clear');
    return '<h1>Cache cleared</h1>';
});

//Clear configuration cache:
Route::get('/config-cache', function () {
    $status = Artisan::call('config:clear');
    return '<h1>Configurations cache cleared</h1>';
});

Route::get('/view-cache', function () {
    $status = Artisan::call('view:clear');
    return '<h1>Configurations view cleared</h1>';
});

//For Url
Route::get('javascript', array('as'=>'setUrl', 'uses'=>'AdapterController@javascript'));
//Notification
Route::get('close-notification', array('as' => 'closeNotification', 'uses' => 'MasterController@crmCloseNotification'));
//File Upload //
Route::post('customFileUpload', array('as' => 'custom.fileUpload', 'uses' => 'FileUploadController@fileUpload'));
Route::post('customFileUnlink', array('as' => 'custom.fileUnlink', 'uses' => 'FileUploadController@fileUnlink'));
Route::get('multipleFileUnlink', array('as' => 'custom.multipleFileUnlink', 'uses' => 'FileUploadController@multipleFileUnlink'));

//Login & Logout
Route::get('login', array('as'=>'login', 'uses'=>'MasterController@getLogin'));
Route::post('login', array('as'=>'login', 'uses' => 'MasterController@postLogin'));
Route::get('logout', array('as'=>'logout', 'uses' => 'MasterController@logout'));

Route::post('login/external', array('uses' => 'MasterController@postLoginExternal'));

Route::get('email_verification', array('uses'=>'MasterController@email_verification'));
Route::post('email_verification', array('uses'=>'MasterController@email_verification_action'));
Route::get('confirmation', array('as'=>'confirmation', 'uses'=>'MasterController@confirmation'));
Route::get('unauthorized_token', array('uses'=>'MasterController@unauthorized_token'));
Route::get('account_verified', array('uses'=>'MasterController@account_verified'));

//Forgot Password
Route::get('forgot-password', array('as'=>'forgotPassword', 'uses'=>'MasterController@forgotPassword'));
Route::post('forgot-password', array('as'=>'forgotPasswordAction', 'uses'=>'MasterController@forgotPasswordAction'));
Route::get('password-req-verification', array('as'=>'passwordReqVerification', 'uses'=>'MasterController@passwordReqVerification'));
Route::get('reset-password', array('as'=>'resetPassword', 'uses'=>'MasterController@resetPassword'));
Route::post('reset-password', array('as'=>'resetPasswordAction', 'uses'=>'MasterController@resetPasswordAction'));

Route::get('passwordUpdate', array('as'=>'passwordUpdate', 'uses'=>'MasterController@passwordUpdate'));

//After Login Master Page
Route::group(['middleware' => 'userAuth'], function() {
    //Master Page
    Route::get('apps', array('as'=>'apps', 'uses'=>'MasterController@apps'));
	//change timezone
    Route::put('changeTimeZone', array('as' => 'changeTimeZone', 'uses' => 'ProfileController@changeTimeZone'));

    //User Administrator
    Route::group(['prefix' => 'admin', 'as'=>'admin.'], function (){
        Route::any('/', ['as'=>'master', 'uses' => 'MasterController@administrator']);
        Route::get('javascript', array('uses'=>'AdapterController@javascript_administrator'));

        Route::group(['namespace' => 'Admin'], function () {
            Route::get('renew-subscription', array('as' => 'renewSubscription', 'uses' => 'SubscribeController@packagePlan'));
            Route::post('payment-confirm', array('as' => 'payment_confirm', 'uses' => 'SubscribeController@paymentConfirm'));
		});
		Route::get('user-profile', array('as'=>'userProfile', 'uses'=>'ProfileController@userProfile'));
		Route::put('userProfile', array('as' => 'userProfileUpdate', 'uses' => 'ProfileController@userProfileUpdate'));
		Route::post('changePassword', array('as' => 'changePassword', 'uses' => 'ProfileController@changePassword'));

        // User Guide
        Route::get('user-guide', array('as'=>'userGuide', 'uses'=>'UserGuideController@index'));
        Route::get('termsOfUse', array('as' => 'termsOfUse', 'uses' => 'TermsConditionController@terms_condition'));
        Route::get('privacyPolice', array('as' => 'privacyPolice', 'uses' => 'PrivacyPoliceController@privacy_police'));

        Route::group(['middleware' => 'projectExpiration'], function (){
            Route::get('home', array('uses'=>'MasterController@administrator_home'));

            Route::group(['namespace' => 'Admin'], function () {
                Route::get('database-backup', array('as'=>'databaseBackup', 'uses'=>'DatabaseBackupController@databaseBackup'));
        		Route::post('database-backup', array('as'=>'databaseBackup', 'uses'=>'DatabaseBackupController@databaseBackupAction'));

                Route::group(['middleware' => 'userAccess'], function (){
                    Route::get('dashboard', array('as'=>'dashboard', 'uses'=>'DashboardController@index'));
                    Route::resource('employee', 'EmployeeController');
                    Route::get('employeeListData', array('access' => ['resource|employee.index'], 'uses' => 'EmployeeController@employeeListData'));
                    Route::get('employeeIdView', array('access' => ['resource|employee.create'], 'uses' => 'EmployeeController@employeeIdView'));
                    Route::get('employeeEmailResend', array('as' => 'employeeEmailResend', 'access' => ['resource|employee.index'], 'uses' => 'EmployeeController@employeeEmailResend'));

                    Route::get('employeeAccess', array('as' => 'employeeAccess', 'uses' => 'EmployeeAccessController@employeeAccess'));
                    Route::post('employeeAccess', array('as' => 'employeeAccess', 'access' => ['employeeAccess'], 'uses' => 'EmployeeAccessController@employeeAccessAction'));
                    Route::get('employeeAccessMenuView', array('as' => 'employeeAccessMenuView', 'access' => ['employeeAccess'], 'uses' => 'EmployeeAccessController@employeeAccessMenuView'));
                    Route::get('employeeAccessView', array('as' => 'employeeAccessView', 'access' => ['employeeAccess'], 'uses' => 'EmployeeAccessController@employeeAccessView'));

                    Route::get('menuSorting', array('as' => 'menuSorting', 'uses' => 'MenuController@menuSorting'));
                    Route::get('menuSortingMenuList', array('as' => 'menuSortingMenuList', 'access' => ['menuSorting'], 'uses' => 'MenuController@menuSortingMenuList'));
                    Route::post('menuSorting', array('as' => 'menuSorting', 'uses' => 'MenuController@menuSortingAction'));

                    //Advance Search
                    Route::get('employeeSearch', array('access' => ['resource|salesTarget.create', 'resource|salesTarget.edit'], 'uses'=>'AdvanceSearchController@employeeSearch'));

                    //Employee Designation
                    Route::resource('employeeDesignation', 'DesignationController');
                    Route::get('designationListData', array('access' => ['resource|employeeDesignation.index'], 'uses' => 'DesignationController@designationListData'));
                    Route::get('designationAdd', array('access' => ['resource|employeeDesignation.create'], 'uses' => 'DesignationController@add'));

                    //Job Area
                    Route::resource('area', 'AreaController');
                    Route::get('areaListData', array('access' => ['resource|area.index'], 'uses' => 'AreaController@areaListData'));
                    Route::get('areaAdd', array('access' => ['resource|area.create'], 'uses' => 'AreaController@add'));

                    //Trades
                Route::resource('trades', 'TradesController');
                Route::get('tradesListData', array('access' => ['resource|trades.index'], 'uses' => 'TradesController@tradesListData'));
                Route::get('getTradesByProject', array('access' => ['resource|trades.index', 'resource|candidate-info.create'], 'as'=> 'getTradesByProject', 'uses' => 'TradesController@getTradesByProject'));
                Route::get('tradeAdd', array('access' => ['resource|trades.index', 'resource|candidate-info.create', 'resource|project-registration.create'], 'as'=> 'tradeAdd', 'uses' => 'TradesController@add'));
                });
            });
        });
    });

    //Eastwest Human Resource
    Route::group(['prefix' => 'eastWest', 'as'=>'ew.'], function (){
        Route::get('/', ['as'=>'master', 'uses' => 'MasterController@eastWest']);
        Route::get('javascript', array('uses'=>'AdapterController@javascript_ew'));
        Route::get('welcome', array('uses'=>'MasterController@ew_home'));

        Route::get('user-profile', array('as'=>'userProfile', 'uses'=>'ProfileController@userProfile'));
        Route::put('userProfile', array('as' => 'userProfileUpdate', 'uses' => 'ProfileController@userProfileUpdate'));
        Route::post('changePassword', array('as' => 'changePassword', 'uses' => 'ProfileController@changePassword'));

        Route::get('ownerProfile/{id}/{name}', array('as'=>'ownerProfile', 'uses'=>'ProfileController@ownerProfile'));
        // User Guide
        Route::get('user-guide', array('as'=>'userGuide', 'uses'=>'UserGuideController@index'));

        Route::group(['namespace' => 'EW', 'middleware' => 'userAccess'], function (){
            //Home =================================
            // Dashboard
            Route::get('dashboard', array('as'=>'dashboard', 'uses'=>'DashboardController@index'));

            Route::get('on-going-projects', array('as'=>'onGoingProjects', 'access' =>['dashboard'], 'uses'=>'DashboardController@onGoingProjects'));
            Route::get('total-candidates', array('as'=>'totalCandidates', 'access' =>['dashboard'], 'uses'=>'DashboardController@totalCandidates'));
            Route::get('total-references', array('as'=>'totalReferences', 'access' =>['dashboard'], 'uses'=>'DashboardController@totalReferences'));
            Route::get('total-trades', array('as'=>'totalTrades', 'access' =>['dashboard'], 'uses'=>'DashboardController@totalTrades'));

            //projects
            Route::resource('project-registration', 'ProjectController');
            Route::get('projectListData', array('access' => ['resource|project-registration.index'], 'uses' => 'ProjectController@projectListData'));

            //Collectable Account
            Route::resource('collectable-account', 'CollectableAccountController');
            Route::get('collectableAccountListData', array('access' => ['resource|collectable-account.index'], 'uses' => 'CollectableAccountController@collectableAccountListData'));

            //Project wise account's heads
            Route::get('candidate-collectable-account-heads', array('access' => ['resource|collectable-account.index', 'resource|amount-received.index', 'resource|amount-refund.index', 'resource|amount-transfer.index', 'resource|amount-less.index', 'trades.create'], 'as' => 'candidateCollectableAccountHeads', 'uses' => 'CollectableAccountController@candidateCollectableAccountHeads'));

            //Collectable Selection
            Route::resource('collectable-selection', 'CollectableSelectionController');
            Route::get('collectableSelectionListData', array('access' => ['resource|collectable-selection.index'], 'uses' => 'CollectableSelectionController@collectableSelectionListData'));

            //Project Collectable Selection
            Route::get('getProjectCollectableSelection', array('access' => ['resource|trades.index', 'resource|candidate-info.create'], 'as'=> 'getProjectCollectableSelection', 'uses' => 'TradesController@getProjectCollectableSelection'));

            //Collectable Add
            Route::get('collectable-add', array('as'=>'collectableAdd', 'uses' => 'CollectableAddController@index'));
            Route::post('collectable-add', array('as'=>'collectableAdd', 'uses' => 'CollectableAddController@store'));

            //Trades
            Route::resource('trades', 'TradesController');
            Route::get('tradesListData', array('access' => ['resource|trades.index'], 'uses' => 'TradesController@tradesListData'));
            Route::get('getTradesByProject', array('access' => ['resource|trades.index', 'resource|candidate-info.create'], 'as'=> 'getTradesByProject', 'uses' => 'TradesController@getTradesByProject'));
            Route::get('tradeAdd', array('access' => ['resource|trades.index', 'resource|candidate-info.create', 'resource|project-registration.create'], 'as'=> 'tradeAdd', 'uses' => 'TradesController@add'));

            //References
            Route::resource('reference', 'ReferenceController');
            Route::get('referenceListData', array('access' => ['resource|reference.index'], 'uses' => 'ReferenceController@referenceListData'));

            //Project Wise References
            Route::get('projectReferences', array('access' => ['candidateFlightReport'], 'as' => 'projectReferences', 'uses' => 'ReferenceController@projectReferences'));

            //Candidate Info

            Route::resource('candidate-info', 'CandidateInfoController');

            Route::get('candidate-info-list/{projectId}', array('as' => 'candidate-info-list','access' => ['resource|candidate-info.index'], 'uses' => 'CandidateInfoController@candidateInfo'));

            Route::get('candidateProjectInfoData', array('access' => ['resource|candidate-info.index'], 'uses' => 'CandidateInfoController@candidateProjectInfoData'));

            Route::get('candidateInfoListData', array('access' => ['resource|candidate-info.index'], 'uses' => 'CandidateInfoController@candidateInfoListData'));
            
            Route::get('create-candidate/{projectId}/{candidateId}', array('access' => ['resource|candidate-info.index'], 'uses' => 'CandidateInfoController@createCandidate'));

            Route::post('create-candidate/{projectId}/{candidateId}', array('access' => ['resource|candidate-info.index'], 'uses' => 'CandidateInfoController@createCandidateStore'));

            Route::get('candidate-sort-details', array('access' => ['resource|candidate-info.index', 'resource|amount-received.index', 'resource|amount-refund.index', 'resource|amount-transfer.index', 'resource|amount-less.index', 'collectableAdd', 'candidateLedgerBook'], 'as' => 'candidate-sort-details',  'uses' => 'CandidateInfoController@candidateSortDetails'));
            Route::get('candidate-account-head-summary', array('access' => ['resource|amount-received.index', 'resource|amount-refund.index', 'resource|amount-transfer.index', 'resource|amount-less.index', 'collectableAdd'], 'as' => 'candidate-account-head-summary',  'uses' => 'CandidateInfoController@candidateAccountHeadSummary'));




            //Aviation Info
            Route::resource('aviation-info', 'AviationController');
            Route::get('aviationInfoListData', array('access' => ['resource|aviation-info.index'], 'uses' => 'AviationController@aviationInfoListData'));
            Route::get('accountCode', array('as' => 'accountCode', 'access' => ['resource|aviation-info.index'], 'uses' => 'AviationController@accountCode'));
            

            //Aviation Bill
            Route::resource('ticket-bill', 'TicketingBillController');
            Route::get('ticketBillListData', array('access' => ['resource|ticket-bill.index'], 'uses' => 'TicketingBillController@ticketBillListData'));
            Route::get('ticketBill', array('access' => ['resource|ticket-bill.create'], 'uses' => 'TicketingBillController@create'));
            Route::get('projectWiseCandidates', array('as'=>'projectWiseCandidates','access' => ['resource|ticket-bill.create'], 'uses' => 'TicketingBillController@projectWiseCandidates'));
            //Aviation Ticket bill transfer
            Route::get('ticket-bill-transfer-modal', array('as' => 'ticketBillTransferModal', 'access' => ['resource|ticket-bill.edit'], 'uses' => 'TicketingBillController@ticketBillTransferModal'));
            Route::post('ticket-bill-transfer-modal', array('as' => 'ticketBillTransfer', 'access' => ['resource|ticket-bill.edit'], 'uses' => 'TicketingBillController@ticketBillTransfer'));
            //Aviation Ticket bill send
            Route::post('ticket-bill-send', array('as' => 'ticketBillSend', 'access' => ['resource|ticket-bill.edit'], 'uses' => 'TicketingBillController@ticketBillSend'));

            //Aviation Ticket bill Paid status
            Route::post('ticket-bill-paid-status', array('as' => 'ticketBillPaidStatus', 'access' => ['resource|ticket-bill.edit'], 'uses' => 'TicketingBillController@ticketBillPaidStatus'));

            //Aviation Bill pdf
            Route::get('ticket-bill-report', array('access' => ['resource|ticket-bill.show'], 'as' => 'ticketBillReport', 'uses' => 'TicketingBillController@ticketBillReport'));
            Route::get('ticket-bill-pdf', array('access' => ['resource|ticket-bill.show'], 'as' => 'ticketBillPDF', 'uses' => 'TicketingBillController@ticketBillPDF'));

            //Aviation Bill Payment
            Route::resource('aviation-payment', 'TicketBillPaymentController');
            Route::get('aviationPayment', array('access' => ['resource|aviation-payment.index'], 'uses' => 'TicketBillPaymentController@aviationPayment'));
            Route::get('aviationPayment', array('as' => 'projectAviations', 'access' => ['resource|aviation-payment.index'], 'uses' => 'TicketBillPaymentController@projectAviations'));
            Route::get('aviationPaymentDetails', array('as' => 'aviation-sort-details', 'access' => ['resource|aviation-payment.index'], 'uses' => 'TicketBillPaymentController@aviationSortDetails'));

            // Route::get('aviationPayment', array('access' => ['resource|aviation-payment.index'], 'as' => 'aviation-sort-details',  'uses' => 'AviationBillPaymentController@aviationSortDetails'));
            // Route::get('accountCode', array('as' => 'accountCode', 'access' => ['resource|aviation-info.index'], 'uses' => 'AviationController@accountCode'));


            // Route::post('providerEventList/Create', array('access' => ['resource|providerEventList.create'], 'uses' => 'EventController@store'));

            // Route::get('candidate-sort-details', array('access' => ['resource|candidate-info.index', 'resource|amount-received.index', 'resource|amount-refund.index', 'resource|amount-transfer.index', 'resource|amount-less.index', 'collectableAdd', 'candidateLedgerBook'], 'as' => 'candidate-sort-details',  'uses' => 'CandidateInfoController@candidateSortDetails'));
            // Route::get('candidate-account-head-summary', array('access' => ['resource|amount-received.index', 'resource|amount-refund.index', 'resource|amount-transfer.index', 'resource|amount-less.index', 'collectableAdd'], 'as' => 'candidate-account-head-summary',  'uses' => 'CandidateInfoController@candidateAccountHeadSummary'));




            //For Project wise candidate list
            Route::get('project_candidates', array('access' => ['resource|candidate-info.index', 'resource|amount-received.index', 'resource|amount-refund.index', 'resource|amount-transfer.index', 'resource|amount-less.index', 'collectableAdd', 'resource|flight-entry.create', 'resource|flight-entry.update', 'candidateLedgerBook', 'trades.create'], 'as' => 'projectCandidates',  'uses' => 'CandidateInfoController@projectCandidates'));
            Route::get('project_candidates_multiple_select', array('access' => ['amountStatusReport'], 'as' => 'projectCandidatesMultipleSelect',  'uses' => 'CandidateInfoController@projectCandidatesMultipleSelect'));

            //For Project wise Account Code
            Route::get('project-account-code', array('access' => ['resource|payment-voucher.index', 'resource|bank-payment-voucher.index'], 'as' => 'projectAccountCode',  'uses' => 'AccountController@projectAccountCode'));

            //Amount Received
            Route::resource('amount-received', 'AmountReceivedController');
            Route::post('amount-received', array('access' => ['resource|amount-received.index'], 'uses' => 'AmountReceivedController@store'));

            //Amount Less
            Route::resource('amount-less', 'AmountLessController');
            Route::post('amount-less', array('access' => ['resource|amount-less.index'], 'uses' => 'AmountLessController@store'));

            //Flight-entry
            Route::resource('flight-entry', 'FlightEntryController');
            Route::get('flightEntryListData', array('access' => ['resource|flight-entry.index'], 'uses' => 'FlightEntryController@flightEntryListData'));

            //Chart-of-accounts
            Route::resource('chart-of-accounts', 'AccountController');
            Route::get('accountListData', array('access' => ['resource|chart-of-accounts.index'], 'uses' => 'AccountController@accountListData'));
            Route::get('account-search', array('access' => ['resource|chart-of-accounts.index', 'resource|amount-received.index'], 'uses'=>'AdvanceSearchController@accountSearch'));

            //Account Configuration
            Route::get('account_configuration', array('as' => 'account_configuration', 'uses' => 'AccountConfigurationController@index'));
            Route::put('account_configuration', array('as' => 'account_configuration', 'uses' => 'AccountConfigurationController@store'));

             //payment-voucher
            Route::resource('payment-voucher', 'PaymentVoucherController');
            Route::post('payment-voucher', array('access' => ['resource|payment-voucher.index'], 'uses' => 'PaymentVoucherController@store'));
             //received-voucher
            Route::resource('received-voucher', 'ReceivedVoucherController');
            Route::post('received-voucher', array('access' => ['resource|received-voucher.index'], 'uses' => 'ReceivedVoucherController@store'));
            //bank-payment-voucher
            Route::resource('bank-payment-voucher', 'BankPaymentVoucherController');
            Route::post('bank-payment-voucher', array('access' => ['resource|bank-payment-voucher.index'], 'uses' => 'BankPaymentVoucherController@store'));
            //bank-received-voucher
            Route::resource('bank-received-voucher', 'BankReceivedVoucherController');
            Route::post('bank-received-voucher', array('access' => ['resource|bank-received-voucher.index'], 'uses' => 'BankReceivedVoucherController@store'));
            //journal-voucher
            Route::resource('journal-voucher', 'JournalVoucherController');
            Route::post('journal-voucher', array('access' => ['resource|journal-voucher.index'], 'uses' => 'JournalVoucherController@store'));
            //journal-voucher
            Route::get('print-voucher', array('as' => 'printVoucher', 'uses' => 'PrintVoucherController@index'));
            Route::get('print-voucher-report', array('access' => ['printVoucher'], 'as' => 'printVoucherReport', 'uses' => 'PrintVoucherController@printVoucherReport'));
            Route::get('print-voucher-report-pdf', array('access' => ['printVoucher'], 'as' => 'printVoucherReportPDF', 'uses' => 'PrintVoucherController@printVoucherReportPDF'));

            //Edit Transaction
            Route::get('edit-transact', array('as' => 'editTransact', 'uses' => 'EditTransactController@index'));
            Route::get('edit-transact-view', array('access' => ['editTransact'], 'as' => 'editTransactView', 'uses' => 'EditTransactController@editTransactView'));
            Route::put('edit-transact-view', array('access' => ['editTransact'], 'as' => 'editTransactView', 'uses' => 'EditTransactController@editTransactUpdate'));

            //Delete Transaction
            Route::get('delete-transact', array('as' => 'deleteTransact', 'uses' => 'DeleteTransactController@index'));
            Route::get('delete-transact-view', array('access' => ['deleteTransact'], 'as' => 'deleteTransactView', 'uses' => 'DeleteTransactController@deleteTransactView'));
            Route::delete('delete-transact/{id}', array('access' => ['deleteTransact'], 'as' => 'deleteTransactDestroy', 'uses' => 'DeleteTransactController@deleteTransactDestroy'));

            //Amount Refund
            Route::resource('amount-refund', 'AmountRefundController');

            Route::post('amount-refund', array('access' => ['resource|amount-refund.index'], 'uses' => 'AmountRefundController@store'));

            //Amount Transfer
            Route::resource('amount-transfer', 'AmountTransferController');
            Route::post('amount-transfer', array('access' => ['resource|amount-transfer.index'], 'uses' => 'AmountTransferController@store'));

            //REPORTS--------
            //Condidate Report
            Route::get('candidate-report', array('as' => 'candidateReport', 'uses' => 'CandidateReportController@candidateReport'));
            //Candidate Report view
            Route::get('candidate-report-view', array('access' => ['candidateReport'], 'as' => 'candidateReportView', 'uses' => 'CandidateReportController@candidateReportView'));
            //candidate Report Pdf
            Route::get('candidate-list-pdf', array('access' => ['candidateReport'], 'as' => 'candidateListPdf', 'uses' => 'CandidateReportController@candidateListPdf'));

            //Candidate Flight Report
            Route::get('candidate-flight-report', array('as' => 'candidateFlightReport', 'uses' => 'CandidateReportController@candidateFlightReport'));
            Route::get('candidate-flight-report-data', array('access' => ['candidateFlightReport'], 'as' => 'candidateFlightReportData', 'uses' => 'CandidateReportController@candidateFlightReportData'));
            Route::get('candidate-flight-report-pdf', array('access' => ['candidateFlightReport'], 'as' => 'candidateFlightReportPdf', 'uses' => 'CandidateReportController@candidateFlightReportPdf'));

            //Candidate Ledger Book
            Route::get('candidate-ledger-book', array('as' => 'candidateLedgerBook', 'uses' => 'CandidateReportController@candidateLedgerBook'));
            Route::get('candidate-ledger-book-data', array('access' => ['candidateLedgerBook'], 'as' => 'candidateLedgerBookData', 'uses' => 'CandidateReportController@candidateLedgerBookData'));
            Route::get('candidate-ledger-book-pdf', array('access' => ['candidateLedgerBook'], 'as' => 'candidateLedgerBookPdf', 'uses' => 'CandidateReportController@candidateLedgerBookPdf'));

            //Amount Status Report
            Route::get('amount-status-report', array('as' => 'amountStatusReport', 'uses' => 'CandidateReportController@amountStatusReport'));
            Route::get('amount-status-report-data', array('access' => ['amountStatusReport'], 'as' => 'amountStatusReportData', 'uses' => 'CandidateReportController@amountStatusReportData'));
            Route::get('amount-status-report-pdf', array('access' => ['amountStatusReport'], 'as' => 'amountStatusReportDataPdf', 'uses' => 'CandidateReportController@amountStatusReportDataPdf'));

            //Amount Less Report
            Route::get('amount-less-report', array('as' => 'amountLessReport', 'uses' => 'CandidateReportController@amountLessReport'));
            Route::get('amount-less-report-data', array('access' => ['amountLessReport'], 'as' => 'amountLessReportData', 'uses' => 'CandidateReportController@amountLessReportData'));
            Route::get('amount-less-report-pdf', array('access' => ['amountLessReport'], 'as' => 'amountLessReportDataPdf', 'uses' => 'CandidateReportController@amountLessReportDataPdf'));

            //Reference/Agent Ledger Report
            Route::get('reference-ledger-report', array('as' => 'referenceLedgerReport', 'uses' => 'CandidateReportController@referenceLedgerReport'));
            Route::get('reference-ledger-report-data', array('access' => ['referenceLedgerReport'], 'as' => 'referenceLedgerReportData', 'uses' => 'CandidateReportController@referenceLedgerReportData'));
            Route::get('reference-ledger-report-pdf', array('access' => ['referenceLedgerReport'], 'as' => 'referenceLedgerReportDataPdf', 'uses' => 'CandidateReportController@referenceLedgerReportDataPdf'));

            //Reference/Agent Report
            Route::get('reference-report', array('as' => 'referenceReport', 'uses' => 'CandidateReportController@referenceReport'));
            Route::get('reference-report-data', array('access' => ['referenceReport'], 'as' => 'referenceReportData', 'uses' => 'CandidateReportController@referenceReportData'));
            Route::get('reference-report-pdf', array('access' => ['referenceReport'], 'as' => 'referenceReportPdf', 'uses' => 'CandidateReportController@referenceReportPdf'));

            //Candidate Advance Report
            Route::get('candidate-advance', array('as' => 'candidateAdvance', 'uses' => 'CandidateReportController@candidateAdvance'));
            Route::get('candidate-advance-data', array('access' => ['candidateAdvance'], 'as' => 'candidateAdvanceData', 'uses' => 'CandidateReportController@candidateAdvanceData'));
            Route::get('candidate-advance-pdf', array('access' => ['candidateAdvance'], 'as' => 'candidateAdvanceDataPdf', 'uses' => 'CandidateReportController@candidateAdvanceDataPdf'));

            //Transaction Posting
            Route::get('transaction-posting', array('as' => 'transactionPosting', 'uses' => 'TransactionPostingController@index'));
            Route::get('transaction-posting-report', array('access' => ['transactionPosting'], 'as' => 'transactionPostingReport', 'uses' => 'TransactionPostingController@transactionPosting'));
            Route::get('transaction-posting-pdf', array('access' => ['transactionPosting'], 'as' => 'transactionPostingPDF', 'uses' => 'TransactionPostingController@transactionPostingPDF'));

            //Company Information
            Route::get('company-profile', array('as' => 'companyProfile', 'uses' => 'CompanyProfileController@index'));
            Route::put('company-profile-up', array('access' => ['companyProfile'], 'as' => 'companyProfileAc', 'uses' => 'CompanyProfileController@update'));

            Route::group(['namespace' => 'AccountReport'], function (){
                //ACCOUNTS REPORTS
                //Trial Balance
                Route::get('trial-balance', array('access' => ['trialBalance'], 'as' => 'trialBalance', 'uses' => 'TrialBalanceController@index'));
                Route::get('trial-balance-report', array('access' => ['trialBalance'], 'as' => 'trialBalanceReport', 'uses' => 'TrialBalanceController@trialBalance'));
                Route::get('trial-balance-pdf', array('access' => ['trialBalance'], 'as' => 'trialBalancePDF', 'uses' => 'TrialBalanceController@trialBalancePDF'));
                //Receipts and Payments
                Route::get('receipts-payments', array('access' => ['receiptsPayments'], 'as' => 'receiptsPayments', 'uses' => 'ReceiptsPaymentsController@index'));
                Route::get('receipts-payments-report', array('access' => ['receiptsPayments'], 'as' => 'receiptsPaymentsReport', 'uses' => 'ReceiptsPaymentsController@receiptsPayments'));
                Route::get('receipts-payments-pdf', array('access' => ['receiptsPayments'], 'as' => 'receiptsPaymentsPDF', 'uses' => 'ReceiptsPaymentsController@receiptsPaymentsPDF'));
                //Ledger Query
                Route::get('ledger-query', array('as' => 'ledgerQuery', 'uses' => 'LedgerQueryController@index'));
                Route::get('ledger-query-report', array('access' => ['ledgerQuery'], 'as' => 'ledgerQueryReport', 'uses' => 'LedgerQueryController@ledgerQuery'));
                Route::get('ledger-query-pdf', array('access' => ['ledgerQuery'], 'as' => 'ledgerQueryPDF', 'uses' => 'LedgerQueryController@ledgerQueryPDF'));

                //Cash Book
                Route::get('cash-book', array('as' => 'cashBook', 'uses' => 'CashBookController@index'));
                Route::get('cash-book-report', array('access' => ['cashBook'], 'as' => 'cashBookReport', 'uses' => 'CashBookController@cashBook'));
                Route::get('cash-book-pdf', array('access' => ['cashBook'], 'as' => 'cashBookPDF', 'uses' => 'CashBookController@cashBookPDF'));

                //Bank Book
                Route::get('bank-book', array('as' => 'bankBook', 'uses' => 'BankBookController@index'));
                Route::get('bank-book-report', array('access' => ['bankBook'], 'as' => 'bankBookReport', 'uses' => 'BankBookController@bankBook'));
                Route::get('bank-book-pdf', array('access' => ['bankBook'], 'as' => 'bankBookPDF', 'uses' => 'BankBookController@bankBookPDF'));

                //Account Payble
                Route::get('accounts-payable', array('as' => 'accountsPayable', 'uses' => 'AccountsPayableController@index'));
                Route::get('accounts-payable-report', array('access' => ['accountsPayable'], 'as' => 'accountsPayableReport', 'uses' => 'AccountsPayableController@accountsPayable'));
                Route::get('accounts-payable-pdf', array('access' => ['accountsPayable'], 'as' => 'accountsPayablePDF', 'uses' => 'AccountsPayableController@accountsPayablePDF'));

                //Account Payble
                Route::get('accounts-receivable', array('as' => 'accountsReceivable', 'uses' => 'AccountsReceivableController@index'));
                Route::get('accounts-receivable-report', array('access' => ['accountsReceivable'], 'as' => 'accountsReceivableReport', 'uses' => 'AccountsReceivableController@accountsReceivable'));
                Route::get('accounts-receivable-pdf', array('access' => ['accountsReceivable'], 'as' => 'accountsReceivablePDF', 'uses' => 'AccountsReceivableController@accountsReceivablePDF'));

                //Collectable Income
                Route::get('collectable-income', array('as' => 'collectableIncome', 'uses' => 'CollectableIncomeController@index'));
                Route::get('collectable-income-report', array('access' => ['collectableIncome'], 'as' => 'collectableIncomeReport', 'uses' => 'CollectableIncomeController@collectableIncome'));
                Route::get('collectable-income-pdf', array('access' => ['collectableIncome'], 'as' => 'collectableIncomePDF', 'uses' => 'CollectableIncomeController@collectableIncomePDF'));

                //Collectable Expense
                Route::get('collectable-expense', array('as' => 'collectableExpense', 'uses' => 'CollectableExpenseController@index'));
                Route::get('collectable-expense-report', array('access' => ['collectableExpense'], 'as' => 'collectableExpenseReport', 'uses' => 'CollectableExpenseController@collectableExpense'));
                Route::get('collectable-expense-pdf', array('access' => ['collectableExpense'], 'as' => 'collectableExpensePDF', 'uses' => 'CollectableExpenseController@collectableExpensePDF'));

                // CASH/BANK POSITION
                Route::get('cash-bank-position', array('as' => 'cash-bank-position', 'access' => ['cash-bank-position'], 'uses' => 'CashBankPositionController@index'));

                Route::get('cash-bank-position-report', array('as' => 'cash-bank-position-report', 'access' => ['cash-bank-position'], 'uses' => 'CashBankPositionController@cashBankPositionReport'));

                Route::get('cash-bank-position-report-pdf', array('as' => 'cash-bank-position-report-pdf', 'access' => ['cash-bank-position'], 'uses' => 'CashBankPositionController@cashBankPositionReportPDF'));
            });

        });
    });

});
 // Route::get('dashboard', array('as'=>'dashboard', 'uses'=>'DashboardController@index'));
