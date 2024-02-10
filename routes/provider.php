<?php
//------ START PROVIDER -------
Route::post('customFileUpload', array('as' => 'custom.fileUpload', 'uses' => 'FileUploadController@fileUpload'));
Route::post('galleryImageStore/{photo_gallery_id}', array('as' => 'galleryImageStore', 'uses' => 'FileUploadController@galleryImageStore'));
Route::post('facilitiesGalleryImageAction/{photo_gallery_id}', array('as' => 'facilitiesGalleryImageAction', 'uses' => 'FileUploadController@facilitiesGalleryImageAction'));
Route::post('trainingFacilitiesGalleryImageAction/{photo_gallery_id}', array('as' => 'trainingFacilitiesGalleryImageAction', 'uses' => 'FileUploadController@trainingFacilitiesGalleryImageAction'));

Route::group(['namespace' => 'Provider', 'prefix' => 'provider', 'as'=>'provider.'], function (){
    //Login & Logout
    Route::get('login', array('as'=>'login', 'uses'=>'MasterController@getLogin'));
    Route::post('login', array('as'=>'login', 'uses' => 'MasterController@postLogin'));
    Route::get('logout', array('as'=>'logout', 'uses' => 'MasterController@logout'));

    Route::get('pro_email_verification', array('uses'=>'MasterController@email_verification'));
    Route::post('pro_email_verification', array('uses'=>'MasterController@email_verification_action'));
    Route::get('confirmation', array('as'=>'confirmation', 'uses'=>'MasterController@confirmation'));
    Route::get('unauthorized_token', array('uses'=>'MasterController@unauthorized_token'));
    Route::get('account_verified', array('uses'=>'MasterController@account_verified'));

    //Forgot Password
    Route::get('forgotPassword', array('as'=>'forgotPassword', 'uses'=>'MasterController@forgotPassword'));
    Route::post('forgotPasswordAc', array('as'=>'forgotPasswordAc', 'uses'=>'MasterController@forgotPasswordAc'));
    Route::get('forgot_email_verification', array('uses'=>'MasterController@forgot_email_verification'));
    Route::post('forgot_email_verification', array('uses'=>'MasterController@forgot_email_verification_action'));

    Route::group(['middleware' => 'providerAuth'], function (){
        Route::get('/', ['as'=>'apps', 'uses' => 'MasterController@apps']);

        Route::get('ownerProfile/{id}/{name}', array('as'=>'ownerProfile', 'uses'=>'ProfileController@ownerProfile'));

        //----------- START ADMIN PANEL -----------
        Route::group(['prefix' => 'admin', 'as'=>'admin.'], function (){
            Route::get('/', array('uses'=>'MasterController@admin'));
            Route::get('welcome', array('uses'=>'MasterController@admin_home'));
            Route::get('javascript', array('uses'=>'AdapterController@javascript_admin'));
            //CHANGE PASSWORD
            Route::put('changeTimeZone', array('as' => 'changeTimeZone', 'uses' => 'ProfileController@changeTimeZone'));

            //PROFILE UPDATE
            Route::get('user-profile', array('as'=>'userProfile', 'uses'=>'ProfileController@userProfile'));
            Route::put('user-profile', array('as' => 'userProfileUpdate', 'uses' => 'ProfileController@userProfileUpdate'));
            Route::post('changePassword', array('as' => 'changePassword', 'uses' => 'ProfileController@changePassword'));
            
            Route::group(['namespace' => 'Admin', 'middleware' => 'providerUserAccess'], function (){
                Route::get('dashboard', array('as'=>'dashboard', 'uses'=>'DashboardController@index'));
                //Menu sorting
                Route::get('menuSorting', array('as' => 'menuSorting', 'uses' => 'MenuController@menuSorting'));
                Route::get('menuSortingMenuList', array('as' => 'menuSortingMenuList', 'access' => ['menuSorting'], 'uses' => 'MenuController@menuSortingMenuList'));
                Route::post('menuSorting', array('as' => 'menuSorting', 'uses' => 'MenuController@menuSortingAction'));

                //Supply Chain
                Route::get('supply-chain', array('as' => 'supply-chain', 'uses' => 'ChainController@chainListData'));
                Route::get('updateChain', array('as' => 'updateChain', 'access' => ['supply-chain'], 'uses' => 'ChainController@updateChain'));
                Route::put('updateChainAction', array('as' => 'updateChainAction', 'access' => ['supply-chain'], 'uses' => 'ChainController@updateChainAction'));
                Route::post('updateChaindestroy', array('as' => 'updateChaindestroy', 'access' => ['supply-chain'], 'uses' => 'ChainController@updateChaindestroy'));

                ///test
                Route::get('employeeAccessMenuView', array('as' => 'employeeAccessMenuView', 'access' => ['employeeAccess'], 'uses' => 'EmployeeAccessController@employeeAccessMenuView'));

                Route::get('compileEmployeeOrganogram', array('as' => 'compileEmployeeOrganogram', 'uses' => 'EmployeeController@compileEmployeeOrganogram'));
                Route::post('compileEmployeeOrganogram', array('as' => 'compileEmployeeOrganogram', 'uses' => 'EmployeeController@compileEmployeeOrganogramAction'));

                Route::get('employeeAccess', array('as' => 'employeeAccess', 'access' => ['employeeAccess'], 'uses' => 'EmployeeAccessController@employeeAccess'));
                Route::post('employeeAccess', array('as' => 'employeeAccess', 'access' => ['employeeAccess'], 'uses' => 'EmployeeAccessController@employeeAccessAction'));

                Route::get('employeeAccessView', array('as' => 'employeeAccessView', 'access' => ['employeeAccess'], 'uses' => 'EmployeeAccessController@employeeAccessView'));
                Route::get('employeeListData', array('access' => ['resource|employee.index'], 'uses' => 'EmployeeController@employeeListData'));
  
                Route::name('provider.admin.')->group(function(){
                    Route::resource('employee', 'EmployeeController');
                    
                    Route::get('employeeIdView', array('access' => ['resource|employee.create'], 'uses' => 'EmployeeController@employeeIdView'));
                    Route::get('employeeEmailResend', array('as' => 'employeeEmailResend', 'access' => ['resource|employee.index'], 'uses' => 'EmployeeController@employeeEmailResend'));

                    //Service Category
                    Route::resource('serviceCategory', 'ServiceCategoryController');
                    Route::get('serviceCategoryListData', array('access' => ['resource|serviceCategory.index'], 'uses' => 'ServiceCategoryController@serviceCategoryListData'));
                    //planner
                    Route::get('slectActionplanner', array('access' => ['resource|serviceCategory.index'], 'as' => 'slectActionplanner', 'uses' => 'ServiceCategoryController@actionplanner'));
                    Route::post('slectActionplannerAction', array('access' => ['resource|serviceCategory.index'], 'as' => 'slectActionplannerAction', 'uses' => 'ServiceCategoryController@plannerAction'));
                    
                });
            });
        });
        //----------- END ADMIN PANEL -----------
        
        //----------- START APPROVAL SYSTEM -----------
        Route::group(['prefix' => 'eastWest', 'as'=>'eastWest.'], function (){
            Route::get('/', array('uses'=>'MasterController@eastWest'));
            Route::get('welcome', array('uses'=>'MasterController@eastWest_home'));
            Route::get('javascript', array('uses'=>'AdapterController@javascript_eastWest'));

            //CHANGE PASSWORD
            Route::put('changeTimeZone', array('as' => 'changeTimeZone', 'uses' => 'ProfileController@changeTimeZone'));

            //PROFILE UPDATE
            Route::get('user-profile', array('as'=>'userProfile', 'uses'=>'ProfileController@userProfile'));
            Route::put('user-profile-action', array('as' => 'userProfileUpdate', 'uses' => 'ProfileController@userProfileUpdate'));
            Route::post('changePassword', array('as' => 'changePassword', 'uses' => 'ProfileController@changePassword'));
            
            Route::group(['namespace' => 'Tubingen', 'middleware' => 'providerUserAccess'], function (){
                // Route::get('home', array('as'=>'home', 'uses'=>'HomeController@index'));
                Route::get('dashboard', array('as'=>'dashboard', 'uses'=>'DashboardController@index'));
                
                Route::get('appliedClientList', array('as' => 'appliedClientList', 'uses' => 'AppliedClientController@index'));
                Route::get('appliedClientListData', array('access' => ['appliedClientList'], 'as' => 'appliedClientListData', 'uses' => 'AppliedClientController@appliedClientListData'));
                Route::get('requestedClientApproveStatus', array('access' => ['appliedClientList'], 'as' => 'requestedClientApproveStatus', 'uses' => 'AppliedClientController@requestedClientApproveStatus'));
                Route::put('requestApprovedStatusAction', array('access' => ['appliedClientList'], 'as' => 'requestApprovedStatusAction', 'uses' => 'AppliedClientController@requestApprovedStatusAction'));

                //Resource route under this group
                Route::name('provider.eastWest.')->group(function(){ 
                    //BANNER
                    Route::resource('banner', 'BannerController');
                    Route::get('bannerListData', array('as' => 'bannerListData','access' => ['resource|banner.index'], 'uses' => 'BannerController@bannerListData'));

                    //COUNTER
                    Route::resource('counter', 'CounterController');
                    Route::get('counterListData', array('as' => 'counterListData','access' => ['resource|counter.index'], 'uses' => 'CounterController@counterListData'));

                    //ABOUT US
                    Route::resource('aboutUs', 'AboutUsController');
                    Route::get('aboutUsListData', array('as' => 'aboutUsListData','access' => ['resource|aboutUs.index'], 'uses' => 'AboutUsController@aboutUsListData'));

                    // MANAGEMENT TEAM
                    Route::resource('managementTeam', 'ManagementTeamController');
                    Route::get('managementTeamListData', array('as' => 'managementTeamListData','access' => ['resource|managementTeam.index'], 'uses' => 'ManagementTeamController@managementTeamListData'));

                    Route::get('teamSorting', array('as' => 'teamSorting', 'access' => ['resource|managementTeam.index'], 'uses' => 'ManagementTeamController@teamSorting'));
                    Route::put('teamSortingAction', array('as' => 'teamSortingAction', 'access' => ['resource|managementTeam.index'], 'uses' => 'ManagementTeamController@teamSortingAction'));
                    
                    // OPERATIONAL TEAM
                    Route::resource('operationalTeam', 'OperationalTeamController');
                    Route::get('operationalTeamListData', array('as' => 'operationalTeamListData','access' => ['resource|operationalTeam.index'], 'uses' => 'OperationalTeamController@operationalTeamListData'));
                    
                    Route::get('operationalTeamSorting', array('as' => 'operationalTeamSorting', 'access' => ['resource|managementTeam.index'], 'uses' => 'OperationalTeamController@operationalTeamSorting'));
                    Route::put('operationalTeamSortingAction', array('as' => 'operationalTeamSortingAction', 'access' => ['resource|managementTeam.index'], 'uses' => 'OperationalTeamController@operationalTeamSortingAction'));

                    // Our Customers
                    Route::resource('ourCustomers', 'OurCustomerController');
                    Route::get('ourCustomersListData', array('as' => 'ourCustomersListData','access' => ['resource|ourCustomers.index'], 'uses' => 'OurCustomerController@ourCustomersListData'));

                    Route::get('countrySorting', array('as' => 'countrySorting', 'access' => ['resource|ourCustomers.index'], 'uses' => 'OurCustomerController@countrySorting'));
                    Route::put('countrySortingAction', array('as' => 'countrySortingAction', 'access' => ['resource|ourCustomers.index'], 'uses' => 'OurCustomerController@countrySortingAction'));
                    Route::get('companySorting', array('as' => 'companySorting', 'access' => ['resource|ourCustomers.index'], 'uses' => 'OurCustomerController@companySorting'));
                    Route::get('countryWiseCompany', array('as' => 'countryWiseCompany', 'access' => ['resource|ourCustomers.index'], 'uses' => 'OurCustomerController@countryWiseCompany'));
                    Route::put('companySortingAction', array('as' => 'companySortingAction', 'access' => ['resource|ourCustomers.index'], 'uses' => 'OurCustomerController@companySortingAction'));
                    
                    // JOB
                    //JOB CATEGORY
                    Route::resource('jobCategory', 'JobCategoryController');
                    Route::get('jobCategoryListData', array('as' => 'jobCategoryListData','access' => ['resource|jobCategory.index'], 'uses' => 'JobCategoryController@jobCategoryListData'));
                    //JOB OPENING
                    Route::resource('jobOpening', 'JobOpeningController');
                    Route::get('jobOpeningListData', array('as' => 'jobOpeningListData','access' => ['resource|jobOpening.index'], 'uses' => 'JobOpeningController@jobOpeningListData'));
                    Route::get('setInterview', array('as' => 'setInterview','access' => ['resource|jobOpening.index'], 'uses' => 'JobOpeningController@setInterview'));
                    Route::post('setInterviewAction', array('as' => 'setInterviewAction','access' => ['resource|jobOpening.index'], 'uses' => 'JobOpeningController@setInterviewAction'));

                    //LICENSE
                    Route::resource('license', 'LicenseController');
                    Route::get('licenseListData', array('as' => 'licenseListData','access' => ['resource|license.index'], 'uses' => 'LicenseController@licenseListData'));
                    
                    // Contact Us
                    Route::resource('contactUs', 'ContactUsController');

                    // Company History
                    Route::resource('companyHistory', 'CompanyHistoryController');
                    Route::get('companyHistoryListData', array('as' => 'companyHistoryListData','access' => ['resource|companyHistory.index'], 'uses' => 'CompanyHistoryController@companyHistoryListData'));

                    //NEWS CATEGORY
                    Route::resource('noticeCategory', 'NoticeCategoryController');
                    Route::get('noticeCategoryListData', array('as' => 'noticeCategoryListData','access' => ['resource|noticeCategory.index'], 'uses' => 'NoticeCategoryController@noticeCategoryListData'));
                    Route::get('noticeCategorySorting', array('as' => 'noticeCategorySorting','access' => ['resource|noticeCategory.index'], 'uses' => 'NoticeCategoryController@noticeCategorySorting'));
                    Route::put('noticeCategorySortingAction', array('as' => 'noticeCategorySortingAction', 'access' => ['resource|noticeCategory.index'], 'uses' => 'NoticeCategoryController@noticeCategorySortingAction'));

                    //NOTICE BOARD
                    Route::resource('noticeBoard', 'NoticeBoardController');
                    Route::get('noticeBoardListData', array('as' => 'noticeBoardListData','access' => ['resource|noticeBoard.index'], 'uses' => 'NoticeBoardController@noticeBoardListData'));
                    //NEWS EVENT
                    Route::resource('newsEvent', 'NewsEventController');
                    Route::get('newsEventListData', array('as' => 'newsEventListData','access' => ['resource|newsEvent.index'], 'uses' => 'NewsEventController@newsEventListData'));
                    
                    // SOCIAL LINK
                    Route::resource('socialLink', 'SocialLinkController');
                    Route::get('socialLinkListData', array('as' => 'socialLinkListData','access' => ['resource|socialLink.index'], 'uses' => 'SocialLinkController@socialLinkListData'));
                    
                    // TERMS AND CONDITION
                    Route::resource('termsAndCondition', 'TermsAndConditionController');

                    // Company Policy
                    Route::resource('companyPolicy', 'CompanyPolicyController');
                    Route::get('companyPolicyListData', array('as' => 'companyPolicyListData','access' => ['resource|companyPolicy.index'], 'uses' => 'CompanyPolicyController@companyPolicyListData'));

                    //Gallery 
                    Route::resource('gallery', 'GalleryController');
                    Route::get('galleryListData', array('access' => ['resource|gallery.index'], 'uses' => 'GalleryController@galleryListData'));
                    Route::get('gallerySorting', array('access' => ['resource|gallery.index'], 'uses' => 'GalleryController@gallerySortingListData'));

                    // Facilities 
                    Route::resource('headOffice', 'HeadOfficeFacilityController');
                    Route::get('headOfficeListData', array('as' => 'headOfficeListData','access' => ['resource|headOffice.index'], 'uses' => 'HeadOfficeFacilityController@headOfficeListData'));
                    
                    // Facilities 
                    Route::resource('trainingFacilities', 'TrainingFacilitiesController');
                    Route::get('trainingFacilitiesListData', array('as' => 'trainingFacilitiesListData','access' => ['resource|trainingFacilities.index'], 'uses' => 'TrainingFacilitiesController@trainingFacilitiesListData'));

                });

                Route::post('updateSortLinkSelect', array('as' => 'updateSortLinkSelect', 'access' => ['resource|socialLink.index'], 'uses' => 'SocialLinkController@updateSortLinkSelect'));

                Route::post('galleryAlbumSorting',array('as' => 'galleryAlbumSorting', 'access' => ['resource|gallery.index'], 'uses' => 'GalleryController@galleryAlbumSortingAction'));

                //Gallery Image
                Route::get('galleryImage', array('as' => 'galleryImage', 'access' => ['resource|gallery.index'], 'uses' => 'GalleryController@galleryImage'));
                Route::get('galleryImageListData', array('access' => ['resource|gallery.index'], 'uses' => 'GalleryController@galleryImageListData'));
                Route::delete('galleryImage/{image_id}', array('access' => ['resource|gallery.index'], 'uses' => 'GalleryController@galleryImageDestroy'));

                Route::post('gallerySorting',array('as' => 'gallerySortingAction', 'access' => ['resource|gallery.index'], 'uses' => 'GalleryController@gallerySortingAction'));
                
                
                //Facility Gallery Image
                Route::get('facilitiesGalleryImage', array('as' => 'facilitiesGalleryImage', 'access' => ['resource|headOffice.index'], 'uses' => 'FacilityGalleryController@facilitiesGalleryImage'));
                Route::get('facilityGalleryImageListData', array('access' => ['resource|headOffice.index'], 'uses' => 'FacilityGalleryController@facilityGalleryImageListData'));
                Route::delete('facilitiesGalleryImageDestroy/{image_id}', array('access' => ['resource|headOffice.index'], 'uses' => 'FacilityGalleryController@facilitiesGalleryImageDestroy'));

                //Training Facility Gallery Image
                Route::get('trainingFacilitiesGalleryImage', array('as' => 'trainingFacilitiesGalleryImage', 'access' => ['resource|trainingFacilities.index'], 'uses' => 'TrainingFacilityGalleryController@trainingFacilitiesGalleryImage'));
                Route::get('trainingFacilitiesGalleryImageListData', array('access' => ['resource|trainingFacilities.index'], 'uses' => 'TrainingFacilityGalleryController@trainingFacilitiesGalleryImageListData'));
                Route::delete('trainingFacilitiesGalleryImageDestroy/{image_id}', array('access' => ['resource|trainingFacilities.index'], 'uses' => 'TrainingFacilityGalleryController@trainingFacilitiesGalleryImageDestroy'));
                
            });

            
            
        });
        //----------- END APPROVAL SYSTEM -----------
    });
});
//------ END PROVIDER -------