<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RoleUsersController;
use App\Http\Controllers\Web\EditorController; 
use App\Http\Controllers\Frontend\PlanController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\FrontUserController;
use App\Http\Controllers\Superadmin\CouponController; 
use App\Http\Controllers\Superadmin\TemplateController;
use App\Http\Controllers\Frontend\MyFlowChartController;
use App\Http\Controllers\Frontend\ThemeSettingController;
use App\Http\Controllers\Superadmin\EnterpriseController;
use App\Http\Controllers\Superadmin\ManageTeamController;
use App\Http\Controllers\Superadmin\ManageUserController;
use App\Http\Controllers\Superadmin\SuperAdminController; 
use App\Http\Controllers\Superadmin\Subscriptioncontroller; 
use App\Http\Controllers\Frontend\FlowchartProjectController;
use App\Http\Controllers\Frontend\UserAngularController;
use App\Http\Controllers\Superadmin\IndividualuserController;
use App\Http\Controllers\Frontend\ReceivedFlowChartController;
use App\Http\Controllers\Frontend\ReportUserController;
use App\Http\Controllers\Frontend\DefaultTempleController;
use App\Http\Controllers\FrontendRole\RoleDashboardController;
use App\Http\Controllers\FrontendRole\RoleUserAngularController;
use App\Http\Controllers\FrontendRole\RoleAccountController;
use App\Http\Controllers\Superadmin\EnterpriseUserRequestController;
use App\Http\Controllers\Superadmin\ManageTemplateCategoryController;
use App\Http\Controllers\FrontendRole\EditorProjectController;
use App\Http\Controllers\FrontendRole\ReceiveRoleFlowchartController;
use App\Http\Controllers\FrontendRole\DefaultTemplateRoleController;
use App\Http\Controllers\FrontendRole\ShareChartController;
use App\Http\Controllers\Frontend\EnterpriserUserController;
use App\Http\Controllers\Superadmin\TaxController;
use App\Http\Controllers\Superadmin\ReportController;
use App\Http\Controllers\Superadmin\SuperAdminAngularController;
use App\Http\Controllers\AngularController;
use Illuminate\Http\Request;
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
Route::get('/NA-flowchart', [AngularController::class, 'index'])->name('angular-tool-NoAuth'); 

Route::group(['middleware' => 'roleuser'], function () {
    /*Angular-tool-integration*/
    Route::get('/RU-flowchart', [RoleUserAngularController::class, 'index'])->name('angular-tool-RU'); 
    Route::get('/RU-angular-tool-user', [RoleUserAngularController::class, 'userDetailsForAngularTool'])->name('angular-tool-user-RU'); 

    Route::get('/RU-angular-tool-csrf', function (Request $request) {
        $token = $request->session()->token();

        $token = csrf_token();
    return response()->json(['csrf' => $token]);
    });
    Route::get('/RU-angular-tool-save-test', [RoleUserAngularController::class, 'saveFlowchartTest'])->name('/angular-tool-save-test-RU');


    Route::post('/RU-angular-tool-save/{id?}', [RoleUserAngularController::class, 'saveFlowchart'])->name('/angular-tool-save-RU');
    Route::post('/RU-angular-tool-commentsave', [RoleUserAngularController::class, 'flowchartsWithCommentsSave'])->name('/angular-tool-commentsave-RU');
     Route::post('/RU-angular-tool-notesave/{id?}', [RoleUserAngularController::class, 'flowchartsWithNotesSave'])->name('/angular-tool-notesave-RU');
    Route::post('/RU-angular-tool-defaulttemplate-save/{id?}', [RoleUserAngularController::class, 'saveDefaultFlowchart'])->name('/angular-tool-defaulttemplate-save-RU');


    Route::get('/RU-angular-tool-notes-list', [RoleUserAngularController::class, 'flowchartsWithNotesList'])->name('/angular-tool-notes-list-RU');
    Route::get('/RU-angular-tool-notes-shapeid/{id}/{templateid}', [RoleUserAngularController::class, 'notesWithShapeId'])->name('/angular-tool-notes-shapeid-RU');
    Route::get('/RU-angular-tool-comments-list/{id}', [RoleUserAngularController::class, 'flowchartsWithCommentsList'])->name('/angular-tool-comments-list-RU');
    Route::get('/RU-angular-tool-project-list', [RoleUserAngularController::class, 'projectWithFlowcharts'])->name('/angular-tool-project-list-RU'); 

    Route::get('/RU-angular-tool-edit-flowchart/{id}/{type}', [RoleUserAngularController::class, 'projectWithFlowchartsEdit'])->name('/angular-tool-project-edit-RU'); 

    Route::get('/RU-angular-tool-flowchart-name/{id}/{type}', [RoleUserAngularController::class, 'flowchartName'])->name('/angular-tool-flowchart-name-RU'); 

    Route::get('/RU-angular-tool-comment-noti/{id}/{tempid}/{commentid}', [RoleUserAngularController::class, 'commentNotificationUpdate'])->name('/angular-tool-comment-noti-RU');


    Route::get('/RU-angular-tool-noti-update/{id}', [RoleUserAngularController::class, 'commentNotificationUpdateStatus'])->name('/angular-tool-noti-update-RU');  




Route::get('/RU-angular-tool-fc/{tempid}/fcid}/{order?}', [RoleUserAngularController::class, 'flowchartMapping'])->name('/RU-angular-tool-fc');



    Route::get('/RU-angular-tool-withoutfclist/{id}', [RoleUserAngularController::class, 'flowchartWithoutMappingList'])->name('/RU-angular-tool-withoutfclist');  

Route::get('/RU-angular-tool-fc/{tempid}/fcid}/{order?}', [RoleUserAngularController::class, 'flowchartMapping'])->name('/RU-angular-tool-fc');


    Route::get('/RU-angular-tool-withfclist/{id}', [RoleUserAngularController::class, 'flowchartWithMappingList'])->name('/RU-angular-tool-withfclist'); 

    Route::get('/RU-angular-tool-sharednotification/{id}', [RoleUserAngularController::class, 'sharedUpdateNotification'])->name('/RU-angular-tool-sharednotification'); 

});
Route::get('/', [UsersController::class, 'index'])->name('user-login');

Route::get('/user-register', [UsersController::class, 'register'])->name('register');
Route::post('/submit-register', [UsersController::class, 'postRegister'])->name('submit-register');
Route::get('/edit/plan/{id}', [UsersController::class, 'updatePlan'])->name('edit.plan');
Route::post('/update/subscription', [UsersController::class, 'postSubscription'])->name('subscription');
Route::get('/plan-preview/{id}', [UsersController::class, 'planPreview'])->name('plan-preview');
Route::post('/coupon-apply', [UsersController::class, 'couponApply'])->name('coupon-apply');
Route::post('/payment', [UsersController::class, 'makePayment'])->name('payment');
Route::get('/success-payment/{id}', [UsersController::class, 'successPayment']);
Route::get('stripe/{id}/{amount}', [StripePaymentController::class, 'stripe']);
Route::post('stripe', [StripePaymentController::class,'stripePost'])->name('stripe.post');
Route::post('/get-state-tax', [PlanController::class,'getTax'])->name('get-state-tax');
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin'], function () {
	Route::post('editor',[EditorController::class, 'getEditor'])->name('admin.editor');
}); 


Route::get('/admin-login', [AdminController::class, 'superLogin'])->name('admin-login');
Route::post('submit_login', [AdminController::class, 'postlogin'])->name('super_login');
Route::get('emailOtp', [AdminController::class, 'emailOtp'])->name('emailOtp');

//forgot password 
Route::get('forgotpassword',[AdminController::class, 'forgotpassword'])->name('forgotpassword');
Route::get('otp-confirm',[AdminController::class, 'otpConfirm'])->name('otp-confirm');
Route::post('forgotPasswordOtpPost', [AdminController::class, 'forgotPasswordOtpPost'])->name('forgot-otp-post');
Route::post('confirmPasswordPost', [AdminController::class, 'confirmPasswordPost'])->name('confirm-otp-post');
Route::post('resetPasswordPost', [AdminController::class, 'resetPasswordPost'])->name('reset-PasswordPost');



// Super Admin
Route::group(['middleware' => 'superadmin'], function () {

    /*Angular-tool-integration*/
    Route::get('/SA-flowchart', [SuperAdminAngularController::class, 'index'])->name('angular-tool-superadmin'); 
    Route::get('/SA-angular-tool-user', [SuperAdminAngularController::class, 'userDetailsForAngularTool'])->name('angular-tool-user-superadmin'); 

    Route::get('/SA-angular-tool-csrf', function (Request $request) {
        $token = $request->session()->token();

        $token = csrf_token();
    return response()->json(['csrf' => $token]);
    });
    Route::get('/SA-angular-tool-save-test', [SuperAdminAngularController::class, 'saveFlowchartTest'])->name('/angular-tool-save-test-superadmin');


    Route::post('/SA-angular-tool-save/{id?}', [SuperAdminAngularController::class, 'saveFlowchart'])->name('/angular-tool-save-superadmin');
    Route::post('/SA-angular-tool-commentsave', [SuperAdminAngularController::class, 'flowchartsWithCommentsSave'])->name('/angular-tool-commentsave-superadmin');
     Route::post('/SA-angular-tool-notesave/{id?}', [SuperAdminAngularController::class, 'flowchartsWithNotesSave'])->name('/angular-tool-notesave-superadmin');
    Route::post('/SA-angular-tool-defaulttemplate-save/{id?}', [SuperAdminAngularController::class, 'saveDefaultFlowchart'])->name('/angular-tool-defaulttemplate-save-superadmin');


    Route::get('/SA-angular-tool-notes-list', [SuperAdminAngularController::class, 'flowchartsWithNotesList'])->name('/angular-tool-notes-list-superadmin');
    Route::get('/SA-angular-tool-notes-shapeid/{id}/{templateid}', [SuperAdminAngularController::class, 'notesWithShapeId'])->name('/angular-tool-notes-shapeid-superadmin');
    Route::get('/SA-angular-tool-comments-list/{id}', [SuperAdminAngularController::class, 'flowchartsWithCommentsList'])->name('/angular-tool-comments-list-superadmin');
    Route::get('/SA-angular-tool-project-list', [SuperAdminAngularController::class, 'projectWithFlowcharts'])->name('/angular-tool-project-list-superadmin'); 

    Route::get('/SA-angular-tool-edit-flowchart/{id}/{type}', [SuperAdminAngularController::class, 'projectWithFlowchartsEdit'])->name('/angular-tool-project-edit-superadmin'); 
    Route::get('/SA-angular-tool-flowchart-name/{id}/{type}', [SuperAdminAngularController::class, 'flowchartName'])->name('/angular-tool-flowchart-name-SA'); 


    Route::get('/SA-angular-tool-comment-noti/{id}/{$tempid}/{$commentid}', [SuperAdminAngularController::class, 'commentNotificationUpdate'])->name('/angular-tool-comment-noti-SA');


    Route::get('/SA-angular-tool-noti-update/{id}', [SuperAdminAngularController::class, 'commentNotificationUpdateStatus'])->name('/angular-tool-noti-update-SA');
    /*Angular-tool-integration*/

    Route::get('/super-dashboard', [SuperAdminController::class, 'dashboard']);
    Route::get('logout', [SuperAdminController::class, 'logout'])->name('logout'); 
    Route::get('admin-profile', [SuperAdminController::class, 'adminProfile'])->name('admin-profile');
    Route::post('update-profile', [SuperAdminController::class, 'updateAdminProfile'])->name('update-profile'); 
    Route::get('/get-notification-admin', [SuperAdminController::class, 'getNotification'])->name('get-notification-admin');
    Route::get('/update-notification-admin', [SuperAdminController::class, 'updateNotification'])->name('update-notification-admin');

    //subscription-plan
    Route::get('/super-subscription-plan', [Subscriptioncontroller::class, 'index']);
    Route::get('/addsubscription/{id?}', [Subscriptioncontroller::class, 'edit']);
    Route::get('/deletesubscription/{id}', [Subscriptioncontroller::class, 'delete']);
    Route::post('/savesubscription', [Subscriptioncontroller::class, 'save']);

 	//manage coupon
	Route::get('/coupon-view', [CouponController::class, 'couponIndex'])->name('coupon-view');
	Route::get('/add-coupon/{id?}', [CouponController::class, 'addCoupon'])->name('add-coupon');
	Route::post('/stored-Coupon', [CouponController::class, 'storedCoupon'])->name('stored-Coupon');
	Route::get('/delete-coupon/{id}', [CouponController::class, 'deleteCoupon'])->name('delete-coupon');
    Route::get('/add-coupon', [CouponController::class, 'addCoupon'])->name('add-coupon');
    Route::post('/add-coupon-post', [CouponController::class, 'addCouponPost'])->name('add-coupon-post');
    Route::get('/edit-coupon/{id}', [CouponController::class, 'editCoupon'])->name('edit-coupon'); 

    //manage trial users
	Route::get('/trial-users', [ManageUserController::class, 'getTrialUser'])->name('trial-users');
	Route::get('/create/trial/{id?}', [ManageUserController::class, 'createTrialUser'])->name('add-trial-user');
	Route::post('/create/trial/{id?}', [ManageUserController::class, 'storeTrialUser'])->name('trial-users-store');
	Route::get('/delete/trial/{id}', [ManageUserController::class, 'deleteTrialUser'])->name('trial-users-delete');

    // Manage Team User  
    Route::get('/team-users', [ManageTeamController::class, 'getTeamUser'])->name('team-users');
    Route::get('/create/team-user/{id?}', [ManageTeamController::class, 'createTeamUser'])->name('add-team-user');
    Route::post('/create/team-user/{id?}', [ManageTeamController::class, 'storeTeamUser'])->name('store-team-user');
    Route::get('/delete/team-user/{id}', [ManageTeamController::class, 'deleteTeamUser'])->name('team-users-delete');


    //manage individual user
    Route::get('/individualuser', [IndividualuserController::class, 'index'])->name('individualuserlist');
    Route::get('/create-individualuer/{id?}', [IndividualuserController::class, 'edituser'])->name('createuser');
    Route::get('/deleteinduser/{id}', [IndividualuserController::class, 'deleteuser'])->name('deleteuser');
    Route::post('/saveindividualuser', [IndividualuserController::class, 'saveindividualuser'])->name('createuser');

    //manage enterprise user
    Route::get('/enterpriseuser', [EnterpriseController::class, 'index'])->name('enterpriseuser');
    Route::get('/add-enterprise-user/{id?}', [EnterpriseController::class, 'editenterpriseuser']);
    Route::post('/saveenterpriser',[EnterpriseController::class, 'saveenterprise']);
    Route::get('/deleteenterpriser/{id}', [EnterpriseController::class, 'deleteenterpriser']);

    //mange enterprise user request    
    Route::get('/enterprise-request', [EnterpriseUserRequestController::class, 'enterpriseUserRqtList'])->name('enterprise-request');
    Route::get('/approve-user/{id}', [EnterpriseUserRequestController::class, 'activateEnterpriseUser'])->name('approve_enterprise_user');
    Route::get('/deactive-user/{id}', [EnterpriseUserRequestController::class, 'deactivateEnterpriseUser'])->name('deactive_enterprise_user');
    Route::get('/reject-user/{id}', [EnterpriseUserRequestController::class, 'rejectEnterpriseUser'])->name('deactive_enterprise_user');

    // manage reject enterprise user

    Route::get('/reject-enterprise-request', [EnterpriseUserRequestController::class, 'rejectEnterpriseUserRqtList'])->name('reject-enterprise-request');

    //theme setup
    Route::get('/themes', [CouponController::class, 'themeList'])->name('themes');
    Route::get('/add-theme', [CouponController::class, 'addTheme'])->name('add-theme');
    Route::get('/add-theme/{id?}', [CouponController::class, 'addTheme'])->name('add-theme');
    Route::post('/stored-theme', [CouponController::class, 'storedTheme'])->name('stored-theme');
    Route::get('/delete-theme/{id}', [CouponController::class, 'deleteTheme'])->name('delete-theme');

    // manage template category
    
    // manage template category
    Route::get('tcategory',[ManageTemplateCategoryController::class,'index'])->name('tcategory');
    Route::get('add-tcategory/{id?}',[ManageTemplateCategoryController::class,'create'])->name('add-tcategory');
    Route::post('stored-tcategory',[ManageTemplateCategoryController::class,'store'])->name('store-tcategory');
    Route::get('/delete-tcategory/{id}',[ManageTemplateCategoryController::class,'destroy'])->name('delete-tcategory');
    
    //manage templates
    Route::get('template',[TemplateController::class,'index'])->name('template');
    Route::get('add-template',[TemplateController::class,'create'])->name('add-template');
    Route::post('stored-template',[TemplateController::class,'store'])->name('store-template');
    Route::get('/delete-template/{id}',[TemplateController::class,'destroy'])->name('delete-template');
    Route::post('add-tcategory-tcat',[TemplateController::class,'storeTcat'])->name('add-tcategory-tcat');

    //manage tax
    Route::get('tax',[TaxController::class,'index'])->name('tax');
    Route::get('add-tax',[TaxController::class,'create'])->name('add-tax');
    Route::post('stored-tax',[TaxController::class,'store'])->name('store-tax');
    Route::get('filter-tax',[TaxController::class,'filter'])->name('filter-tax');
    Route::get('/edit-tax/{id}',[TaxController::class,'edit'])->name('edit-tax');
    Route::post('update-tax/{id}',[TaxController::class,'update'])->name('update-tax');
    Route::get('/delete-tax/{id}',[TaxController::class,'destroy'])->name('delete-tax');

       // login report
    
    Route::get('login-report',[ReportController::class,'getLoginUser'])->name('login-report');
    Route::get('login-not-report',[ReportController::class,'getStillNotLoginUser'])->name('login-not-report');
    Route::get('report-payment',[ReportController::class,'payment'])->name('report-payment');

});


//userslogin
Route::get('/user-login', [UsersController::class, 'index'])->name('user-login');
Route::get('/user-login/{id}', [UsersController::class, 'index']);
Route::post('usersubmit', [UsersController::class, 'LoginUser']);
Route::get('/user-logout', [UsersController::class, 'Userlogout']);
Route::get('/user-logout/{id}', [UsersController::class, 'Userlogout']);
Route::post('/get-tax',[PlanController::class,'getTax'])->name('get-tax');
//front end forgot password 
Route::get('forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password');
Route::post('forgot-pwd-post', [LoginController::class, 'forgotPasswordOtpPost'])->name('forgot-pwd-post');
Route::post('otpsubmit', [LoginController::class, 'confirmOtpPost'])->name('Confirm-otp-post');
Route::get('Otpverify', [LoginController::class, 'Otpviewpage'])->name('otp-view');
Route::get('resend-otp/{email}', [LoginController::class, 'ResendOtp'])->name('otp-resend');
Route::post('resetpassword-submit', [LoginController::class, 'ResetPassword'])->name('reset-password');

// Front end Dashboard 
Route::group(['middleware' => 'auth'], function () {
    /*Angular-tool-integration*/
    Route::get('/flowchart', [UserAngularController::class, 'index'])->name('angular-tool'); 
    Route::get('/angular-tool-user', [UserAngularController::class, 'userDetailsForAngularTool'])->name('angular-tool-user'); 

    Route::get('/angular-tool-csrf', function (Request $request) {
        $token = $request->session()->token();

        $token = csrf_token();
    return response()->json(['csrf' => $token]);
    });
    Route::get('/angular-tool-save-test', [UserAngularController::class, 'saveFlowchartTest'])->name('/angular-tool-save-test');


    Route::post('/angular-tool-save/{id?}', [UserAngularController::class, 'saveFlowchart'])->name('/angular-tool-save');
    Route::post('/angular-tool-commentsave', [UserAngularController::class, 'flowchartsWithCommentsSave'])->name('/angular-tool-commentsave');
     Route::post('/angular-tool-notesave/{id?}', [UserAngularController::class, 'flowchartsWithNotesSave'])->name('/angular-tool-notesave');
    Route::post('/angular-tool-defaulttemplate-save/{id?}', [UserAngularController::class, 'saveDefaultFlowchart'])->name('/angular-tool-defaulttemplate-save');


    Route::get('/angular-tool-notes-list', [UserAngularController::class, 'flowchartsWithNotesList'])->name('/angular-tool-notes-list');
    Route::get('/angular-tool-notes-shapeid/{id}/{templateid}', [UserAngularController::class, 'notesWithShapeId'])->name('/angular-tool-notes-shapeid');
    Route::get('/angular-tool-comments-list/{id}', [UserAngularController::class, 'flowchartsWithCommentsList'])->name('/angular-tool-comments-list');
    Route::get('/angular-tool-project-list', [UserAngularController::class, 'projectWithFlowcharts'])->name('/angular-tool-project-list'); 

    Route::get('/angular-tool-edit-flowchart/{id}/{type}', [UserAngularController::class, 'projectWithFlowchartsEdit'])->name('/angular-tool-project-edit'); 
    Route::get('/angular-tool-flowchart-name/{id}/{type}', [UserAngularController::class, 'flowchartName'])->name('/angular-tool-flowchart-name'); 

    Route::get('/angular-tool-comment-noti/{id}/{tempid}/{commentid}', [UserAngularController::class, 'commentNotificationUpdate'])->name('/angular-tool-comment-noti'); 

    Route::get('/angular-tool-noti-update/{id}', [UserAngularController::class, 'commentNotificationUpdateStatus'])->name('/angular-tool-noti-update');



Route::get('/angular-tool-fc/{tempid}/{fcid}/{order?}', [UserAngularController::class, 'flowchartMapping'])->name('/angular-tool-fc');






    Route::get('/angular-tool-withoutfclist/{id}', [UserAngularController::class, 'flowchartWithoutMappingList'])->name('/angular-tool-withoutfclist');  

    Route::get('/angular-tool-withfclist/{id}', [UserAngularController::class, 'flowchartWithMappingList'])->name('/angular-tool-withfclist'); 
    Route::get('/angular-tool-sharednotification/{id}', [UserAngularController::class, 'sharedUpdateNotification'])->name('/angular-tool-sharednotification'); 
    Route::get('/angular-tool-viewednotificationclear/{id}', [UserAngularController::class, 'viewedNotificationClear'])->name('/angular-tool-viewednotificationclear'); 


    /*Angular-tool-integration*/

    Route::get('/user-dashboard', [DashboardController::class, 'index'])->name('user-dashboard');
    Route::post('/template-rename', [DashboardController::class, 'templateRename'])->name('template-rename');
    Route::post('/template-duplicate', [DashboardController::class,'templateDuplicate'])->name('template-duplicate');
    Route::get('/template-delete/{id}', [DashboardController::class, 'templateDelete'])->name('template-delete');
    //share chart in dashboard
    Route::post('/user-lists', [DashboardController::class, 'getSubUsers'])->name('user-lists');
    Route::post('/user-myflowchart/share', [DashboardController::class, 'shareChart'])->name('myflowchart.share');
    Route::post('/check-share-chart', [DashboardController::class, 'chkshareExist'])->name('check-share-chart');
    Route::post('/share-chart-exist', [DashboardController::class, 'chkshareChartExist'])->name('share-chart-exist'); 
    Route::post('/reshare-chart', [DashboardController::class, 'reshareChart'])->name('reshare-chart');
    Route::get('/get-notification', [DashboardController::class, 'getNotification'])->name('get-notification');       
    Route::post('/update-notification', [DashboardController::class, 'updateNotification'])->name('update-notification');
    //account settings
    Route::get('/account-setting', [UsersController::class, 'editprofile'])->name('edit-profile');
    Route::post('/edituser-submit', [UsersController::class, 'edituserupdate'])->name('user-update'); 
    //change pwd  chngpwd-submit
    Route::get('/change-password', [AccountController::class, 'changePassword'])->name('changepassword');
    Route::post('/chngpwd-submit', [AccountController::class, 'updateChangePwd'])->name('update-changepassword');
    

    //  Flowchart project    
    Route::resource('flowchart-project',FlowchartProjectController::class);
    Route::post('/get-subuser', [FlowchartProjectController::class, 'getTeamuser']);
    Route::get('/user-myflowchart/autocomplete-search', [MyFlowChartController::class, 'autocompleteSearch']);
    //add notes
    Route::post('/user-myflowchart/add-notes', [MyFlowChartController::class, 'addNotes'])->name('myflowchart.add-notes');
    Route::post('/user-myflowchart/note-lists', [MyFlowChartController::class, 'noteList'])->name('myflowchart.note-lists');
    //my flow chart MyFlowChartController
    Route::post('/user-myflowchart/rename', [MyFlowChartController::class, 'rename'])->name('myflowchart.rename');
    Route::post('/user-myflowchart/duplicate', [MyFlowChartController::class, 'duplicate'])->name('myflowchart.duplicate');
    Route::get('/user-myflowchart/approved/{id}', [MyFlowChartController::class, 'approved'])->name('myflowchart.approved');
    Route::get('/user-myflowchart/delete/{id}', [MyFlowChartController::class, 'destroy'])->name('flowchart-delete');
    Route::resource('user-myflowchart',MyFlowChartController::class);
    //add comments
    Route::post('/user-myflowchart/add-comments', [MyFlowChartController::class, 'addComments'])->name('myflowchart.add-comments');
    Route::post('/user-comment-lists', [MyFlowChartController::class, 'commentListrqst'])->name('user-comment-lists');
    //request changes
    Route::post('/user-myflowchart/request-changes', [MyFlowChartController::class, 'addChanges'])->name('myflowchart.request-changes');
    Route::post('/user-req-lists', [MyFlowChartController::class, 'reqchangeslist'])->name('user-req-lists');
    Route::post('/user-myflowchart/reject', [MyFlowChartController::class, 'rejectFC'])->name('myflowchart.reject');
    // Received Flow chart     
    Route::get('/received-myflowchart', [ReceivedFlowChartController::class, 'receivedFlowChart'])->name('received-myflowchart');

 //add flowchart project type
    Route::post('/add-flowchart', [MyFlowChartController::class, 'addNewFlowcahrt'])->name('add-flowchart');

    // default template

    Route::get('/default-template', [DefaultTempleController::class, 'index'])->name('default-template');
    Route::post('/use_template_update', [DefaultTempleController::class, 'useTemplateUpdate'])->name('use_template_update');

 //report 
    Route::get('/user-login-report',[ReportUserController::class, 'getLoginUser'])->name('user-login-report');

   
    // plan setting
    Route::get('/plan-setting', [PlanController::class, 'planHistoryList'])->name('plan-setting');
    Route::get('/plan-setting/{id}', [PlanController::class, 'planHistoryList'])->name('plan-setting');
    Route::get('/planchange',[PlanController::class, 'Changeplan'])->name('plan-page');
    Route::post('/updateplan-values',[PlanController::class, 'updateplan'])->name('plan-update');
    Route::get('/user-plan-preview/{id}', [PlanController::class, 'planPreview'])->name('user-plan-preview');
    Route::post('/user-coupon-apply', [PlanController::class, 'couponApply'])->name('user-coupon-apply');
    Route::post('/user-payment', [PlanController::class, 'makePayment'])->name('user-payment');
    Route::post('/user-subscription-plan', [PlanController::class, 'pausePayment'])->name('user-subscription-plan');
    
    //theme setting
    Route::get('/user-themes',[ThemeSettingController::class, 'themeList'])->name('plan-update');
    Route::post('/update/theme', [ThemeSettingController::class, 'updateTheme'])->name('theme-update');
    Route::get('/set/theme', [ThemeSettingController::class, 'setTheme'])->name('set-theme');
    Route::post('/set/defaulttheme', [ThemeSettingController::class, 'setDefaultTheme'])->name('set-defaulttheme');
    Route::group(['middleware' => 'team-user'], function () {
        Route::resource('user-list',FrontUserController::class);
    });
    Route::group(['middleware' => 'enterpriser'], function () {
        Route::resource('team-user-list',EnterpriserUserController::class);
        Route::get('/sub-user-list', [EnterpriserUserController::class, 'getSubUserlist']);
        Route::get('/create/sub-user/{id?}', [EnterpriserUserController::class, 'createSubUser'])->name('add-sub-user');
        Route::post('/create/sub-user/{id?}', [EnterpriserUserController::class, 'storeSubUser'])->name('sub-user-store');
        Route::get('/delete/sub-user/{id}', [EnterpriserUserController::class, 'deleteSubUser'])->name('sub-users-delete');
    });
    Route::get('/view-project/{id}',[FlowchartProjectController::class, 'viewProject']);
});



//roleuserslogin
Route::get('/role-user-login', [RoleUsersController::class, 'index'])->name('role-user-login');
Route::get('/role-user-login/{id}', [RoleUsersController::class, 'index']);
Route::post('role-user-login', [RoleUsersController::class, 'LoginUser']);
Route::get('/role-user-logout', [RoleUsersController::class, 'Userlogout']);
Route::get('/role-user-logout/{id}', [RoleUsersController::class, 'Userlogout']);
Route::group(['prefix' => 'role-user','middleware' => 'roleuser'], function () {
    //role user part 
     Route::get('/dashboard', [RoleDashboardController::class, 'index'])->name('role-user-dashboard');
     Route::get('/user-themes',[RoleDashboardController::class, 'themeList'])->name('role-user.user-themes');
     Route::get('/set-theme', [RoleDashboardController::class, 'setTheme'])->name('role-user.set-theme');
     Route::post('/update/theme', [RoleDashboardController::class, 'updateTheme'])->name('role-user.theme-update');
     Route::post('/set/defaulttheme', [RoleDashboardController::class, 'setDefaultTheme'])->name('role-set-defaulttheme');
     Route::post('/temp-dash-rename', [RoleDashboardController::class, 'templateRename'])->name('temp-dash-rename');
     Route::post('/temp-dash-duplicate', [RoleDashboardController::class,'templateDuplicate'])->name('temp-dash-duplicate');
     Route::post('/temp-dash-delete', [RoleDashboardController::class, 'templateDelete'])->name('temp-dash-delete');

     //change pwd  chngpwd-submit
    Route::get('/role-change-password', [RoleAccountController::class, 'changePassword'])->name('role-changepassword');
    Route::post('/role-chngpwd-submit', [RoleAccountController::class, 'updateChangePwd'])->name('update-changepassword');
     
    // editor project 
     Route::get('/view-projects/{id}',[EditorProjectController::class, 'viewProject']);
     Route::get('/editor-project-list', [EditorProjectController::class, 'index'])->name('editor-project-list');
     Route::post('/add-notes', [EditorProjectController::class, 'addNotes'])->name('add-notes');
     Route::post('/note-lists', [EditorProjectController::class, 'notelist'])->name('note-lists');
     Route::post('/comment-lists', [EditorProjectController::class, 'commentList'])->name('comment-lists');
     Route::post('/comment-rqt-lists', [EditorProjectController::class, 'commentListrqst'])->name('comment-rqt-lists');
     Route::post('/comment-rqt-add', [EditorProjectController::class, 'requestCmtAdd'])->name('comment-rqt-add');
     Route::post('/editorstatus-change', [EditorProjectController::class, 'editorStatusChange'])->name('editorstatus-change');
     Route::post('/approverstatus-change', [EditorProjectController::class, 'approverStatusChange'])->name('approverstatus-change');
     Route::post('/user-rejectstatus-change', [EditorProjectController::class, 'requestStatusChange'])->name('user-rejectstatus-change');
     Route::post('/rejectstatus-change', [EditorProjectController::class, 'rejectStatusChange'])->name('rejectstatus-change');
      Route::post('/temp-rename', [EditorProjectController::class, 'templateRename'])->name('temp-rename');
        Route::post('/temp-duplicate', [EditorProjectController::class,'templateDuplicate'])->name('temp-duplicate');
        Route::post('/temp-delete', [EditorProjectController::class, 'templateDelete'])->name('temp-delete');
        //qrcode
        Route::get('///qrcode-display', [EditorProjectController::class, 'qrCodeGenerate'])->name('qrcode-display');
        // recieved flowchart list
        Route::get('/received-fc-list', [ReceiveRoleFlowchartController::class, 'receivedRoleFCLiset'])->name('received-fc-list');
      
        // Default template flowchart list
        Route::get('/default-temp-list', [DefaultTemplateRoleController::class, 'index'])->name('default-temp-list');


        Route::post('/use_template_role_update', [DefaultTemplateRoleController::class, 'useTemplateRoleUpdate'])->name('use_template_role_update');  
        //share chart   
        Route::post('/team-sub-user-list', [EditorProjectController::class, 'getSubUsers'])->name('team-sub-user-list');

        Route::post('/add-flowchart-role', [EditorProjectController::class, 'addNewRoleFlowcahrt'])->name('add-flowchart-role');

        Route::post('/subuser-myflowchart/share', [ShareChartController::class, 'shareChart'])->name('roleuser.myflowchart.share');
        Route::post('/role-check-share-chart', [ShareChartController::class, 'chkshareExist'])->name('role-check-share-chart');
        Route::post('/role-share-chart-exist', [ShareChartController::class, 'chkshareChartExist'])->name('role-share-chart-exist'); 
        Route::post('/role-reshare-chart', [ShareChartController::class, 'reshareChart'])->name('role-reshare-chart');
        Route::get('/get-notification', [ShareChartController::class, 'getNotification'])->name('role-get-notification');       
        Route::post('/update-notification', [ShareChartController::class, 'updateNotification'])->name('role-update-notification');

       //account settings
     Route::get('/account-setting', [RoleUsersController::class, 'editProfile'])->name('role-user.account-setting');
     Route::post('/edituser-submit', [RoleUsersController::class, 'updateProfile'])->name('role-user.edituser-submit');
    //manage projects
    Route::group(['middleware' => 'admin-user'], function () {
        Route::get('/projects', [ReceiveRoleFlowchartController::class, 'getProjects'])->name('projects');
        Route::get('/edit-project/{id}', [ReceiveRoleFlowchartController::class, 'editProject'])->name('project-edit');
        Route::post('/update-project',[ReceiveRoleFlowchartController::class, 'update'])->name('update-project');
    });
});




// folow diagram 
Route::get('/flowdiagram', function () {
    return view('flowdiagram');
}); 
 
