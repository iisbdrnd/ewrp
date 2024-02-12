<?php
if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
// Ignores notices and reports all other kinds... and warnings
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
// error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}
/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'userAuth'], function() {
    Route::group(['prefix' => 'recruitment', 'as'=>'recruit.'], function (){
        Route::get('/', ['as'=>'master', 'uses' => 'MasterController@recruitment']);
        //For Url
        Route::get('javascript', array('as'=>'setUrl', 'uses'=>'AdapterController@javascript_recruitment'));

        Route::group(['middleware' => 'projectExpiration'], function (){
            Route::get('home', array('uses'=>'MasterController@recruitment_home')); 

            Route::get('user-profile', array('as'=>'userProfile', 'uses'=>'ProfileController@userProfile'));
            Route::put('userProfile', array('as' => 'userProfileUpdate', 'uses' => 'ProfileController@userProfileUpdate'));
            Route::post('changePassword', array('as' => 'changePassword', 'uses' => 'ProfileController@changePassword'));

            Route::get('ownerProfile/{id}/{name}', array('as'=>'ownerProfile', 'uses'=>'ProfileController@ownerProfile'));
            // User Guide
            Route::get('user-guide', array('as'=>'userGuide', 'uses'=>'UserGuideController@index'));

            Route::group(['namespace' => 'Recruitment', 'middleware' => 'userAccess'], function (){
                
                

            //Trades
            Route::resource('trades', 'TradesController');
            Route::get('tradesListData', array('access' => ['resource|trades.index'], 'uses' => 'TradesController@tradesListData'));
            Route::get('getTradesByProject', array('access' => ['resource|trades.index', 'resource|candidate-info.create'], 'as'=> 'getTradesByProject', 'uses' => 'TradesController@getTradesByProject'));
            Route::get('tradeAdd', array('access' => ['resource|trades.index', 'resource|candidate-info.create', 'resource|project-registration.create'], 'as'=> 'tradeAdd', 'uses' => 'TradesController@add')); 

            //Agency
            Route::resource('agency', 'AgencyController');
            Route::get('agencyListData', array('access' => ['resource|agency.index'], 'uses' => 'AgencyController@agencyListData'));
            /*--------------------
                 MOBILIZATION LIST
            ----------------------*/
            Route::resource('mobilization-list', 'MobilizationList');
            
            Route::get('mobilizationListData', array('access' => ['resource|mobilization-list.index'], 'uses' => 'MobilizationList@mobilizationListData'));

            //generate sorting mobilization list
            
            Route::get('generateMobilizeList', array('access' => ['resource|mobilization-list.index'], 'uses' => 'MobilizationList@ '));
            Route::put('generateMobilizeListAction', array('as' => 'generateMobilizeListAction', 'access' => ['resource|mobilization-list.index'], 'uses' => 'MobilizationList@generateMobilizeListAction'));

            Route::get('getMobilizationByProject', array('access' => ['resource|mobilization-list.index', 'resource|candidate-info.create'], 'as'=> 'getMobilizationByProject', 'uses' => 'MobilizationList@getMobilizationByProject'));

            Route::get('mobilizationAdd', array('access' => ['resource|mobilization-list.index', 'resource|candidate-info.create', 'resource|project-registration.create'], 'as'=> 'mobilizationAdd', 'uses' => 'MobilizationList@add')); 

            /*.............Mobilization Dependency.......................*/ 
            Route::resource('mobilization-dependency', 'MobilizationDependency');
            Route::get('mobilizationDependencyData', array('access' => ['resource|mobilization-dependency.index'], 'uses' => 'MobilizationDependency@mobilizationDependencyData'));

            Route::get('get-mobilize-filter', array('access' => ['resource|mobilization-dependency.index'], 'uses' => 'MobilizationDependency@getMobilizeFilter'));

            Route::get('get-dependency-filter', array('access' => ['resource|mobilization-dependency.index'], 'uses' => 'MobilizationDependency@getDependencyFilter'));

            /*----------------------------------------------------
                PASSPORT FORM
            ------------------------------------------------------*/
            Route::resource('passport-form', 'PassportFormController');
            
            Route::get('passportData', array('access' => ['resource|passport-form.index'], 'uses' => 'PassportFormController@passportData'));

            Route::get('getMobilizationByProject', array('access' => ['resource|passport-form.index', 'resource|candidate-info.create'], 'as'=> 'getMobilizationByProject', 'uses' => 'PassportFormController@getMobilizationByProject'));

            Route::get('mobilizationAdd', array('access' => ['resource|passport-form.index', 'resource|candidate-info.create', 'resource|project-registration.create'], 'as'=> 'mobilizationAdd', 'uses' => 'PassportFormController@add')); 

            /*--------------------
                 VISA JOB CATEGORY
            ----------------------*/
            Route::resource('visa-job-category', 'VisaJobCategoryController');
            
            Route::get('visaJobCategoryListData', array('access' => ['resource|visa-job-category.index'], 'uses' => 'VisaJobCategoryController@visaJobCategoryListData'));

            // Route::get('getMobilizationByProject', array('access' => ['resource|mobilization-list.index', 'resource|candidate-info.create'], 'as'=> 'getMobilizationByProject', 'uses' => 'MobilizationList@getMobilizationByProject'));

            // Route::get('mobilizationAdd', array('access' => ['resource|mobilization-list.index', 'resource|candidate-info.create', 'resource|project-registration.create'], 'as'=> 'mobilizationAdd', 'uses' => 'MobilizationList@add'));

            /*--------------------
                 PROJECT CREATE
            ----------------------*/
            Route::resource('project-registration', 'ProjectController');
            Route::get('projectListData', array('access' => ['resource|project-registration.index'], 'uses' => 'ProjectController@projectListData'));

            Route::get('project-status-form/{projectId}', array('access' => ['resource|project-registration.index'], 'uses' => 'ProjectController@projectStatusForm'));

            Route::post('project-status-form/{projectId}', array('access' => ['resource|project-registration.index'], 'uses' => 'ProjectController@updateProjectStatus')); 

            Route::get('assign-project-user/{projectId}', array('access' => ['resource|project-registration.index'], 'uses' => 'ProjectController@assignProjectUser'));
            Route::post('assign-project-user/{projectId}', array('access' => ['resource|project-registration.index'], 'uses' => 'ProjectController@assignProjectUserStore'));

            
            //trade filter
            Route::get('get-trade-filter', array('access' => ['resource|project-registration.index'], 'uses' => 'ProjectController@traderFilter'));

            Route::get('get-agency-filter', array('access' => ['resource|project-registration.index'], 'uses' => 'ProjectController@agencyFilter'));
             /*--------------------
                 REFERENCE
            ----------------------*/
            Route::resource('reference', 'ReferenceController');
            Route::get('referenceListData', array('access' => ['resource|reference.index'], 'uses' => 'ReferenceController@referenceListData'));

            /*------------------------------
                CONFIGURATION BLADE ROUTE
            --------------------------------*/  
            Route::get('configure/{project_id}', array('access' => ['resource|project-registration.index'], 'as '=> 'configure', 'uses' => 'ProjectController@configure'));  
            Route::post('configure/{project_id}', array('access' => ['resource|project-registration.index'], 'as '=> 'configure', 'uses' => 'ProjectController@storeConfiguration'));   

             /*--------------------
                TOKEN
            ----------------------*/
            Route::resource('token', 'TokenController');
            Route::get('tokenInfoListData', array('as'=>'tokenInfoListData','access' => ['resource|token.index'], 'uses' => 'TokenController@tokenInfoListData'));

            //Token Menu
            Route::resource('token-bill', 'TokenBillController');
            Route::get('tokenBillListData', array('access' => ['resource|token-bill.index'], 'uses' => 'TokenBillController@tokenBillListData'));
             /*--------------------
                 CANDIDATE CV
            ----------------------*/
            Route::resource('candidates-cv', 'CandidateCVController');
            Route::get('candidatesCVListData', array('access' => ['resource|candidates-cv.index'], 'uses' => 'CandidateCVController@candidatesCVListData'));
            Route::get('candidateCvPDF', array('access' => ['resource|candidates-cv.index'], 'as' => 'candidateCvPDF', 'uses' => 'CandidateCVController@candidateCvPDF'));
            Route::get('candidateCvExcel', array('access' => ['resource|candidates-cv.index'], 'as' => 'candidateCvExcel', 'uses' => 'CandidateCVController@candidateCvExcel'));

             

            Route::get('interview-token/{candidate_id}', array('access' => ['resource|candidates-cv.index'], 'uses' => 'CandidateCVController@interview_token')); 

            Route::get('passportChecker', array('as' => 'passportChecker', 'access' => ['resource|candidates-cv.index'], 'uses' => 'CandidateCVController@passportChecker')); 

            Route::post('dealer', array('as' => 'dealer','access' => ['resource|interview.index'], 'uses' => 'CandidateCVController@getDealer')); 

            Route::get('agency_details', array('as' => 'agency_details','access' => ['resource|candidates-cv.index'], 'uses' => 'CandidateCVController@agency_details')); 

            Route::post('interview-token/{candidate_id}', array('access' => ['resource|candidates-cv.index'], 'uses' => 'CandidateCVController@interview_token_create')); 

            Route::get('cv-moved-form/{candidate_id}', array('access' => ['resource|candidates-cv.index'], 'uses' => 'CandidateCVController@cv_moved_form')); 

            Route::post('cv-moved-form/{candidate_id}', array('access' => ['resource|candidates-cv.index'], 'uses' => 'CandidateCVController@cv_moved_to_interview'));  

            Route::get('cv-print-preview', array('as' => 'cv-print-preview', 'access' => ['resource|candidates-cv.index'], 'uses' => 'CandidateCVController@cvPrintPreview')); 
           
            /*--------------------
                 INTERVIEW CALL
            ----------------------*/
            Route::resource('interview-call', 'InterviewCallController');
            Route::get('interviewCallListData', array('access' => ['resource|interview-call.index'], 'uses' => 'InterviewCallController@interviewCallListData'));  
            Route::get('interview-call-status/{id}', array('access' => ['resource|interview-call.index'], 'uses' => 'InterviewCallController@interview_call_status_form')); 

            Route::post('interview-call-status/{id}', array('access' => ['resource|interview-call.index'], 'uses' => 'InterviewCallController@interview_call_status_update'));  

            /*--------------------
                 INTERVIEW
            ----------------------*/
            Route::resource('interview', 'InterviewController');
            Route::get('interviewListData', array('access' => ['resource|interview.index'], 'uses' => 'InterviewController@interviewListData'));

            Route::get('listOfCV', array('as'=>'listOfCVs','access' => ['resource|interview.index'], 'uses' => 'InterviewController@listOfCV'));

            Route::get('getPassportDetails', array('as'=>'getPassportDetails','access' => ['resource|interview.index'], 'uses' => 'InterviewController@getPassportDetails'));
           
            Route::get('getPassportFormData', array(
                'as'     => 'getPassportFormData',
                'access' => ['resource|interview.index'],
                 'uses'  => 'InterviewController@getPassportFormData'
                ));

            Route::get('create-cv/{projectId}/{projectCountryId}', array('access' => ['resource|interview.index'], 'uses' => 'InterviewController@interviewCreateCVForm'));

            Route::post('create-cv/{projectId}/{projectCountryId}', array('access' => ['resource|interview.index'], 'uses' => 'InterviewController@interviewCreateCVStore'));
            
             Route::get('candidate-details/{projectId}/{candidateId}', array('access' => ['resource|candidates-cv.index'], 'uses' => 'InterviewController@candidateDetails'));

             Route::get('interview-evaluation-form', array('as' => 'interview-evaluation-form', 'access' => ['resource|interview.index'], 'uses' => 'InterviewController@interviewEvaluationForm'));
             Route::get('worker-evaluation-form', array('as' => 'worker-evaluation-form', 'access' => ['resource|interview.index'], 'uses' => 'InterviewController@workerEvaluationForm'));

             Route::get('cv-info-print', array('as' => 'cv-info-print', 'access' => ['resource|interview.index'], 'uses' => 'InterviewController@cvInfoPrint'));  
             

            /*--------------------
                 MOBILIZATION
            ----------------------*/
             Route::resource('mobilization', 'MobilizationRoomController');
             Route::get('mobilizationRoomListData', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationRoomController@mobilizationRoomListData')); 
             

            Route::get('mobilization/mobilization-room-list/{projectId}/{projectCountryId}', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationRoomController@mobilizationRoomList'));

            Route::get('mobilizationCandidateList', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationRoomController@mobilizationCandidateList'));
            /*--------------------
                 MOBILIZATION
            ----------------------*/
            Route::resource('mobilization', 'MobilizationController');
            
            Route::get('mobilizationProjectListData', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@mobilizationProjectListData'));

            Route::get('mobilization/mobilization-room/{projectId}/{projectCountryId}', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@mobilizationRoom'));

            Route::post('mobilization/mobilization-room/{projectId}/{projectCountryId}', array('as'=>'candidateApproveStatus','access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@candidateApproveStatus'));

            Route::post('restoreCandidate', array('as'=>'restoreCandidate','access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@restoreCandidate'));

            Route::post('releaseCandidate', array(
                'as'     => 'releaseCandidate',
                'access' => ['resource|mobilization.index'],
                'uses'   => 'MobilizationController@releaseCandidate'
                ));

            Route::get('getMobilizationData', array('as'=>'getMobilizationData','access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@getMobilizationData'));

            Route::get('getPrevMobilizationDate', array('as'=>'getPrevMobilizationDate','access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@getPrevMobilizationDate'));

            Route::get('wip_status', array('as'=>'wip_status','access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@wip_status'));

            Route::get('getSelectedTrade', array('as'=>'getSelectedTrade','access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@getSelectedTrade'));
           

            Route::get('mobilization/mobilization-activity-room/{projectId}', array('as' => 'mobilization-activity-room' ,'access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@mobilizationActivityRoomCandidateList'));

            Route::get('mobilizationActivityRoomCandidateListData', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@mobilizationActivityRoomCandidateListData'));

            Route::get('mobilizationRoomData', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@mobilizationRoomData')); 

            Route::get('mobilization/mobilizationRoomCandidateList/{projectId}/{projectCountryId}', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@mobilizationRoomCandidateList')); 

            Route::get('mobilization/mobilizationRoomCandidateData/{projectId}/{projectCountryId}/{mobilizeId}/{data}', array('as'=>'mobilizationRoomCandidateData','access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@mobilizationRoomCandidateData'));

            Route::get('mobilization/mobilizeModalViewForm/{projectId}/{mobilizeId}/{data}', array('as' => 'mobilizeModalViewForm','access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@mobilizeModalViewForm'));

            Route::get('mobilizeModalActivityViewFrom', array('as' => 'mobilizeModalActivityViewFrom','access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@mobilizeModalActivityViewFrom'));
            
            /* Mobilise Activity */
            Route::get('mobilize-activity', array('as' => 'mobilize-activity', 'access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@mobilizeActivity'));
 
            /*---------------------------------
                SINGLE CANDIDATE MOBILIZATION
            -----------------------------------*/
            Route::get('mobilization/single-candidate/{projectId}/{candidateId}', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@singleCandidateMobilization'));

            Route::get('mobilization/accounts-transfer-candidate/{projectId}', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@accountsTransferCandidateList'));

            Route::get('accountsTransferCandidateData', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@accountsTransferCandidateData'));
            

            Route::get('updateSelectedTradeForm', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@updateSelectedTradeForm'));

            Route::post('updateSelectedTradeForm', array('as' =>'updateSelectedTradeForm', 'access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@updateSelectedTrade'));

            Route::get('create-candidate', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@candidateForm'));

            Route::post('create-candidate', array('as' => 'create-candidate','access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@candidateFormStore'));

            Route::get('singleCandidateMobilizationData', array('access' => ['resource|mobilization.index'], 'uses' => 'MobilizationController@singleCandidateMobilizationData'));

            Route::get('mobilization/single-candidate/{projectId}/{candidateId}/{mobilizeId}', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@mobilizeTemplate'));

            /*--------------------------------
                Mobilization Activities
            ----------------------------------*/
             Route::get('mobilization/mobilization-activities/{projectId}/{candidateId}/{mobilizeId}', array('as' => 'mobilization-activities', 'access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@mobilizationActivities'));

            /*---------------------
                    MEDICAL
            -----------------------*/
            Route::get('mobilization/medical-type/{projectId}/{candidateId}/{mobilizeId}', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@medicalType'));
            Route::post('medical-type', array('as' => 'medical-type', 'access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@medicalStoreData'));

            /*---------------------
                    VISA
            -----------------------*/
             Route::get('mobilization/visa-type/{projectId}/{candidateId}/{mobilizeId}', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@visaType'));
            Route::post('visa-type', array('as' => 'visa-type', 'access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@visaStoreData')); 

            /*---------------------------------
                Mobilization General Form Page
            -----------------------------------*/

            Route::get('get-flight-briefing-date', array('as'=>'getFliteBriefDate','access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@getFlightBriefingDate'));


             Route::get('mobilization/general-page/{projectId}/{candidateId}/{mobilizeId}', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@mobilizationGeneralForm'));
            Route::post('general-page', array('as'=>'general-page','access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@mobilizationGeneralFormDataStore')); 

            /*---------------------
                CALL ACTIVITIES
            -----------------------*/
            Route::get('mobilization/call-activities/{projectId}/{candidateId}/{mobilizeId}', array('as' => 'call-activities','access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesCall'));

            Route::get('mobilization/call-activities-details/{projectId}/{candidateId}/{mobilizeId}', array('as' => 'call-activities-details', 'access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesCallDetails'));

            Route::get('mobilization/selected-mobilize-details/{projectId}/{candidateId}/{mobilizeId}', array('as' => 'selected-mobilize-details', 'access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@selectedMobilizeDetails'));

            Route::post('call-activities', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesCallStore'));

             /*---------------------
                Direct Contact
            -----------------------*/
            Route::get('mobilization/direct-contact/{projectId}/{candidateId}/{mobilizeId}', array('as' => 'direct-contact','access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesDirectContact'));

            Route::get('mobilization/direct-contact-details/{projectId}/{candidateId}/{mobilizeId}', array('as' => 'direct-contact-details', 'access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesDirectContactDetails'));

            Route::post('activitiesDirectContactStore', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesDirectContactStore')); 

            /*---------------------
                Note Details
            -----------------------*/
            Route::get('mobilization/note-activities/{projectId}/{candidateId}/{mobilizeId}', array('as' => 'note-activities', 'access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesNote'));
            
            Route::get('mobilization/note-activity-details/{projectId}/{candidateId}/{mobilizeId}', array('as' => 'note-activity-details', 'access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesNoteDetails'));

            Route::post('activitiesNoteStore', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesNoteStore'));

            /*---------------------
                Side Note
            -----------------------*/
            Route::get('mobilization/activitiesSideNote/{projectId}/{candidateId}/{mobilizeId}', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesSideNote'));  

            /*---------------------
                Side Note List
            -----------------------*/
            Route::get('activitiesSideNoteList/{projectId}/{candidateId}/{mobilizeId}', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesSideNoteList'));   

            /*---------------------
                Side Note Data
            -----------------------*/
            Route::get('activitiesSideNoteData', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesSideNoteData')); 

            /*---------------------
                Side Attachment
            -----------------------*/
            Route::get('mobilization/activitiesSideAttachment/{projectId}/{candidateId}/{mobilizeId}', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@activitiesSideAttachment')); 

            Route::post('mobilizationSigleCompletedStatus', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@mobilizationSigleCompletedStatus')); 
            
            /**
            *Mobilzing Dashboard-> blade -> mobilizationRoomData
            * Ajax call for retrieve candidate data by this url
            */
            Route::get('getIncompletedMObilizeCandidate', array('access' => ['resource|mobilization.index'], 'uses'=>'MobilizationController@getIncompletedMObilizeCandidate'));

            /*------------------
                REPORT
            --------------------*/
           Route::resource('reports', 'ReportController');
            Route::get('projectFinalList', array('access' => ['resource|reports.index'], 'uses' => 'ReportController@projectFinalList'));

            Route::get('candiate-list', array('access' => ['resource|reports.index'], 'uses' => 'ReportController@candidateList'));

            Route::get('report-print-preview', array('access' => ['resource|reports.index'], 'uses' => 'ReportController@previewReport'));

            Route::get('reports/candidate-report/{projectId}', array('access' => ['resource|reports.index'], 'uses' => 'ReportController@candidateReport'));
            Route::get('candidateReportData', array('access' => ['resource|reports.index'], 'uses' => 'ReportController@candidateReportData'));
            Route::get('reports/view-report/{projectId}/{candidateId}', array('access' => ['resource|reports.index'], 'uses' => 'ReportController@viewReport'));

            Route::get('selection-report', array('as'=>'selection-report','access' => ['resource|reports.index'], 'uses' => 'ReportController@selectionReport'));

            Route::get('reports/selection-candidate-list/{projectId}', array('as'=>'selection-candidate-list','access' => ['resource|reports.index'], 'uses' => 'ReportController@selectionCandidateList'));

            Route::get('selectionCandidateListData', array('as'=>'selectionCandidateListData','access' => ['resource|reports.index'], 'uses' => 'ReportController@selectionCandidateListData'));

            Route::get('selectionReportData', array('as'=>'selectionReportData','access' => ['resource|reports.index'], 'uses' => 'ReportController@selectionReportData'));

            Route::get('rejection-report', array('as'=>'rejection-report','access' => ['resource|reports.index'], 'uses' => 'ReportController@rejectionReport'));

            Route::get('rejectionReportData', array('access' => ['resource|reports.index'], 'uses' => 'ReportController@rejectionReportData'));

            Route::get('reports/rejection-candidate-list/{projectId}', array('as'=>'selection-candidate-list','access' => ['resource|reports.index'], 'uses' => 'ReportController@rejectionCandidateList'));
            /*ROUTE CONFIGURATION END*/
            });
        });
    });
});

