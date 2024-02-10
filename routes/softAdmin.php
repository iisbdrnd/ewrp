<?php 

/*
|--------------------------------------------------------------------------
| Software Admin Panel
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'softAdmin', 'as'=>'softAdmin.'], function (){
	Route::get('javascript', array('as'=>'setUrl', 'uses'=>'AdapterController@javascript_softAdmin'));
	
	Route::group(['namespace' => 'softAdmin'], function (){ 
		Route::get('/', ['as'=>'master', function (){  return redirect()->route('softAdmin.content'); }]);
		Route::get('login', 'MasterController@getLogin')->name('login');
        Route::post('login', 'MasterController@postLogin')->name('login');
        Route::get('logout', 'MasterController@logout')->name('logout');

        Route::group(['middleware' => 'softAdminAuth'], function (){
			Route::get('content', ['as' => 'content', 'uses' => 'MasterController@index']);

			Route::group(['middleware' => 'softAdminAccess'], function () {
                Route::get('dashboard','DashboardController@index')->name('dashboard');

                Route::name('softAdmin.')->group(function(){
                    Route::get('activeProjectList', 'DashboardController@activeProjectList')->name('activeProjectList');
                    Route::get('activeProjectsListData', array('as' => 'activeProjectsListData', 'access' => ['resource|DashboardController.index'], 'uses' => 'DashboardController@activeProjectsListData'));
                    //ADMIN
                    Route::resource('admin', 'AdminController');
                    Route::get('adminListData', array('access' => ['resource|admin.index'], 'uses' => 'AdminController@adminListData'));
                    //PROJECT REGISTRATION
                    Route::resource('projectRegistration', 'ProjectController');
                    Route::get('projectList', array('access' => ['resource|projectRegistration.index'], 'uses' => 'ProjectController@projectList'));
                    //USER
                    Route::resource('user', 'UserController');
                    Route::get('userList', array('as' => 'userList', 'access' => ['resource|user.index'], 'uses' => 'UserController@userList'));
                    Route::get('userLogin', array('as' => 'userLogin', 'uses' => 'UserController@userLogin'));
                    //ADMIN MENU
                    Route::resource('adminMenu', 'AdminMenuController');
                    Route::get('adminMenuList', array('access' => ['resource|adminMenu.index'], 'uses' => 'AdminMenuController@adminMenuList'));
                    //ADMIN INTERNAL LINK
                    Route::resource('adminInternalLink', 'AdminInternalLinkController');
                    Route::get('adminInternalLinkList', array('access' => ['resource|adminInternalLink.index'], 'uses' => 'AdminInternalLinkController@adminInternalLinkList'));
                    //SOFTWARE MODULE
                    Route::resource('softwareModule', 'SoftwareModuleController');
                    Route::get('softwareModuleList', array('access' => ['resource|softwareModule.index'],  'uses' => 'SoftwareModuleController@softwareModuleList'));
                    //SOFTWARE MENU
                    Route::resource('softwareMenu', 'SoftwareMenuController');
                    Route::get('softwareMenuList', array('access' => ['resource|softwareMenu.index'], 'uses' => 'SoftwareMenuController@softwareMenuList'));
                    //SOFTWARE INTERNAL LINK
                    Route::resource('softwareInternalLink', 'SoftwareInternalLinkController');
                    Route::get('softwareInternalLinkList', array('access' => ['resource|softwareInternalLink.index'], 'uses' => 'SoftwareInternalLinkController@softwareInternalLinkList'));
                    //PROVIDER DESIGNATION
                    Route::resource('providerDesignation', 'ProviderDesignationController');
                    Route::get('proDesignationListData', array('access' => ['resource|providerDesignation.index'], 'uses' => 'ProviderDesignationController@proDesignationListData'));
                    Route::get('providerDesignationAdd', array('access' => ['resource|providerDesignation.create'], 'uses' => 'ProviderDesignationController@add'));
                    // JOB AREA
                    Route::resource('jobArea', 'JobAreaController');
                    Route::get('jobAreaListData', array('access' => ['resource|jobArea.index'], 'uses' => 'JobAreaController@jobAreaListData'));

                });

                //ADMIN MENU SORTING
                Route::get('adminMenuSorting', array('as' => 'adminMenuSorting', 'uses' => 'AdminMenuController@adminMenuSorting'));
                Route::post('adminMenuSorting', array('as' => 'adminMenuSorting', 'access' => ['adminMenuSorting'], 'uses' => 'AdminMenuController@adminMenuSortingAction'));
                // SOFTWARE MENU SORTING
                Route::get('softwareMenuSorting', array('as' => 'softwareMenuSorting', 'uses' => 'SoftwareMenuController@softwareMenuSorting'));
                Route::get('softwareMenuSortingMenuList', array('as' => 'softwareMenuSortingMenuList', 'access' => ['softwareMenuSorting'], 'uses' => 'SoftwareMenuController@softwareMenuSortingMenuList'));
                Route::post('softwareMenuSorting', array('as' => 'softwareMenuSorting', 'access' => ['softwareMenuSorting'], 'uses' => 'SoftwareMenuController@softwareMenuSortingAction'));

                //Mail Configaration
                Route::get('projectMailConfiguration', array('access' => ['resource|projectRegistration.index'], 'as' => 'projectMailConfiguration', 'uses' => 'ProjectMailConfigurationController@create'));
                Route::put('projectMailConfigurationAc', array('access' => ['resource|projectRegistration.index'], 'as' => 'projectMailConfigurations', 'uses' => 'ProjectMailConfigurationController@store'));
                Route::get('projectMailConfigurationTest', array('access' => ['resource|projectRegistration.index'], 'uses' => 'ProjectMailConfigurationController@configarationTest'));
                Route::get('projectAccessMenuView', array('as' => 'projectAccessMenuView', 'access' => ['projectAccess'], 'uses' => 'ProjectAccessController@projectAccessMenuView'));

                //User Access
                Route::get('userAccess', array('as' => 'userAccess', 'uses' => 'UserAccessController@userAccess'));
                Route::post('userAccess', array('as' => 'userAccess', 'access' => ['userAccess'], 'uses' => 'UserAccessController@userAccessAction'));
                Route::get('userAccessMenuView', array('as' => 'userAccessMenuView', 'access' => ['userAccess'], 'uses' => 'UserAccessController@userAccessMenuView'));
                Route::get('userAccessView', array('as' => 'userAccessView', 'access' => ['userAccess'], 'uses' => 'UserAccessController@userAccessView'));

                Route::get('projectNameById', array('as' => 'projectNameById', 'access' => ['resource|user.create', 'resource|user.edit'], 'uses' => 'ProjectController@projectNameById'));
                Route::get('projectUserById', array('as' => 'projectUserById', 'access' => ['resource|user.create', 'resource|user.edit'], 'uses' => 'ProjectController@projectUserById'));
                Route::get('projectDesignationById', array('as' => 'projectDesignationById', 'access' => ['resource|user.create', 'resource|user.edit'], 'uses' => 'ProjectController@projectDesignationById'));
                Route::get('projectAreaById', array('as' => 'projectAreaById', 'access' => ['resource|user.create', 'resource|user.edit'], 'uses' => 'ProjectController@projectAreaById'));

                Route::get('softwareLinkModule', array('as' => 'softwareLinkModule', 'access' => ['resource|softwareMenu.create'], 'uses' => 'SoftwareMenuController@softwareLinkModule'));
                Route::get('softwareLinkMenu', array('as' => 'softwareLinkMenu', 'access' => ['resource|softwareInternalLink.create'], 'uses' => 'SoftwareInternalLinkController@softwareLinkMenu'));

                Route::get('softwareInternalLinkModule', array('as' => 'softwareInternalLinkModule', 'access' => ['resource|softwareInternalLink.create'], 'uses' => 'SoftwareInternalLinkController@softwareInternalLinkModule'));
                
                //Menu Sorting Module View
                Route::get('menuSortingModuleView', array('as' => 'menuSortingModuleView', 'access' => ['softwareMenuSorting'], 'uses' => 'SoftwareMenuController@menuSortingModuleView'));
               
                //Project Access
                Route::get('projectAccess', array('as' => 'projectAccess', 'uses' => 'ProjectAccessController@projectAccess'));
                Route::post('projectAccess', array('as' => 'projectAccess', 'access' => ['projectAccess'], 'uses' => 'ProjectAccessController@projectAccessAction'));
                Route::get('projectAccessModuleViewByFolder', array('as' => 'projectAccessModuleViewByFolder', 'access' => ['projectAccess'], 'uses' => 'ProjectAccessController@projectAccessModuleViewByFolder'));
                Route::get('projectAccessMenuView', array('as' => 'projectAccessMenuView', 'access' => ['projectAccess'], 'uses' => 'ProjectAccessController@projectAccessMenuView'));
                Route::get('projectAccessView', array('as' => 'projectAccessView', 'access' => ['projectAccess'], 'uses' => 'ProjectAccessController@projectAccessView'));
                Route::get('projectIdView', array('access' => ['resource|projectRegistration.create'], 'uses' => 'ProjectController@projectIdView'));

                Route::get('adminAccess', array('as' => 'adminAccess', 'uses' => 'AdminAccessController@adminAccess'));
                Route::post('adminAccess', array('as' => 'adminAccess', 'access' => ['adminAccess'], 'uses' => 'AdminAccessController@adminAccessAction'));
                Route::get('adminAccessView', array('as' => 'adminAccessView', 'access' => ['adminAccess'], 'uses' => 'AdminAccessController@adminAccessView'));

                // Route::resource('admin', 'AdminController');
                // Route::get('admin', function(){
                // 	return "hello rudra";
                // })->name('dashboard');
            });
		});
	});
});