<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers;

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
//
Route::post('SendRequest', [App\Http\Controllers\UserController::class, 'Send_request']);
Route::post('SendLabRequest', [App\Http\Controllers\MadicalTestResult\LabController::class, 'Send_request']);
Route::post('SendXrayRequest', [App\Http\Controllers\MadicalTestResult\XrayCenterController::class, 'Send_request']);
Route::post('SendHospitalRequest', [App\Http\Controllers\HospitalController::class, 'SendHospitalRequest']);
Route::post('SendPharmacistRequest', [App\Http\Controllers\PharmacistController::class, 'Send_request']);


Route::post('Store_Request', 'App\Http\Controllers\PrequestController@store_request');
Route::post('login', [AuthController::class, 'login']);
Route::post('loginPatent', 'App\Http\Controllers\PatientController@login_patient');



Route::get('AllPhamacists', 'App\Http\Controllers\PharmacistController@AllPhamacists');


route::group(['prefix' => 'admin', 'middleware' => ['multiAuth:admin']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('registerUser', [AuthController::class, 'registerUser']);
    Route::post('profile', [AuthController::class, 'profile']);
    Route::post('EditProfile', [AuthController::class, 'profile']);
    Route::post('UpdateProfile', [App\Http\Controllers\AdminController::class, 'UpdateProfile']);
    Route::post('ShowUserRequests', [App\Http\Controllers\AdminController::class, 'ShowAllRequests']);
    Route::post('ApproveUserRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'ApproveUserRequest']);
    Route::post('ApproveLabRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'ApproveLabRequest']);
    Route::post('ApproveXrayRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'ApproveXrayRequest']);
    Route::post('ApproveHospitalRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'ApproveHospitalRequest']);
    Route::post('ApprovePharmacistRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'ApprovePharmacistRequest']);

    Route::post('ShowUserRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'ShowUserRequest']);
    Route::post('ShowLabRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'ShowLabRequest']);
    Route::post('ShowXrayRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'ShowXrayRequest']);

    Route::post('DeleteUserRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'DeleteUserRequest']);
    Route::post('DeleteLabRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'DeleteLabRequest']);
    Route::post('DeleteXaryRequest/{requestId}', [App\Http\Controllers\AdminController::class, 'DeleteXaryRequest']);

    //patient request
    Route::get('Requests_Show', 'App\Http\Controllers\PrequestController@prequests_show');
    Route::get('Store_Patient_Info/{prequest_id}', 'App\Http\Controllers\PatientInfoController@store_patient_info');
    Route::get('Destroy_Request/{prequest_id}', 'App\Http\Controllers\PrequestController@destroy_prequest');
});

route::group(['prefix' => 'user', 'middleware' => ['multiAuth:user']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('profile', [App\Http\Controllers\UserController::class, 'profile']);
    Route::post('EditProfile', [App\Http\Controllers\UserController::class, 'profile']);
    Route::post('UpdateProfile', [App\Http\Controllers\UserController::class, 'UpdateProfile']);
    ///////////first_step_to_enter_the_record
    Route::post('Check_First_Time', 'App\Http\Controllers\CheckController@check_first_time');
    ///////////close_the_record
    Route::get('Close_The_Record/{health_record_id}', 'App\Http\Controllers\CheckController@close_the_record');

    //
    Route::get('Your_Patients', 'App\Http\Controllers\CheckController@show_patients_names');
    //the_rest
    Route::post('Check', 'App\Http\Controllers\CheckController@check');
    ///////////////////////////////////////////////////////////////////////////
    //review_store
    Route::post('New_Review/{review_id}', 'App\Http\Controllers\DoctorReview\ReviewController@cteate_review');
    Route::post('New_Diagnose/{review_id}', 'App\Http\Controllers\DoctorReview\ReviewController@create_diagnose');
    Route::post('New_Instruction/{review_id}', 'App\Http\Controllers\DoctorReview\ReviewController@create_instruction');
    Route::post('New_Review_Date/{review_id}', 'App\Http\Controllers\DoctorReview\ReviewController@create_next_review_date');
    Route::post('New_Madical_Test/{review_id}', 'App\Http\Controllers\DoctorReview\DocController@store_madical_test');
    Route::post('New_Progress_Note/{review_id}', 'App\Http\Controllers\DoctorReview\ReviewController@create_procress_notes');
    Route::post('New_Referral/{review_id}', 'App\Http\Controllers\DoctorReview\ReviewController@Referral');
    Route::post('Madical_Support/{review_id}', 'App\Http\Controllers\DoctorReview\ReviewController@madical_support');

    //review_show
    Route::post('Show_Reviews/{health_record_id}', 'App\Http\Controllers\DoctorReview\ReviewController@show_reviews');
    Route::post('Show_Review/{review_id}', 'App\Http\Controllers\DoctorReview\ReviewController@show_review_info');
    Route::get('Madical_Tests/{review_id}', 'App\Http\Controllers\DoctorReview\DocController@show_madical_tests');
    Route::get('Madical_Test_Info/{madical_test_id}', 'App\Http\Controllers\DoctorReview\DocController@show_madical_test_info');
    Route::get('Test_Files/{madical_test_id}', 'App\Http\Controllers\DoctorReview\DocController@show_files');
    //download_file
    Route::get('Download_Test/{file_id}', 'App\Http\Controllers\DoctorReview\DocController@download_test_result');
    ///////////////////
    Route::post('Store_specification/{health_record_id}', 'App\Http\Controllers\PatientInfo\SpecificationController@store_specification_info');

    Route::get('show_specifications/{health_record_id}', 'App\Http\Controllers\PatientInfo\SpecificationController@show_specifications');

    Route::get('show_specification_info/{specification_id}', 'App\Http\Controllers\PatientInfo\SpecificationController@show_specification_info');

    Route::post('update_specification_info/{specification_id}', 'App\Http\Controllers\PatientInfo\SpecificationController@update_specification_info');
    //history
    Route::post('Store_History/{health_record_id}', 'App\Http\Controllers\PatientInfo\FamilyMadicalHistoryController@store_history');

    Route::get('Show_Histories/{health_record_id}', 'App\Http\Controllers\PatientInfo\FamilyMadicalHistoryController@show_histories');

    Route::get('Show_History_Info/{history_id}', 'App\Http\Controllers\PatientInfo\FamilyMadicalHistoryController@show_history_info');

    Route::post('Update_History/{history_id}', 'App\Http\Controllers\PatientInfo\FamilyMadicalHistoryController@update_history');
    //allergy
    Route::post('Store_Allergy/{health_record_id}', 'App\Http\Controllers\PatientInfo\AllergyController@store_allergy');

    Route::get('Show_Allergies/{health_record_id}', 'App\Http\Controllers\PatientInfo\AllergyController@show_allergies');

    Route::get('Show_Allergy_Info/{allergy_id}', 'App\Http\Controllers\PatientInfo\AllergyController@show_allergy_info');

    Route::post('Update_Allergy/{allergy_id}', 'App\Http\Controllers\PatientInfo\AllergyController@update_allergy');
    //habit
    Route::post('Store_Habit/{health_record_id}', 'App\Http\Controllers\PatientInfo\HabitsController@store_habit');

    Route::get('Show_Habits/{health_record_id}', 'App\Http\Controllers\PatientInfo\HabitsController@show_habits');

    Route::get('Show_Habit_Info/{habit_id}', 'App\Http\Controllers\PatientInfo\HabitsController@show_habit_info');

    Route::post('Update_Habit/{habit_id}', 'App\Http\Controllers\PatientInfo\HabitsController@update_habit');
    //vaccine
    Route::post('Store_Vaccine/{health_record_id}', 'App\Http\Controllers\PatientInfo\VaccinesController@store_vaccine');

    Route::get('Show_Vaccines/{health_record_id}', 'App\Http\Controllers\PatientInfo\VaccinesController@show_vaccines');

    Route::get('Show_Vaccine_Info/{vaccine_id}', 'App\Http\Controllers\PatientInfo\VaccinesController@show_vaccine_info');

    Route::post('Update_Vaccine/{vaccine_id}', 'App\Http\Controllers\PatientInfo\VaccinesController@update_vaccine');
    //surgeoncies
    Route::post('Store_Surgeoncy/{health_record_id}', 'App\Http\Controllers\PatientInfo\SurgeonciesController@store_surgeoncy');

    Route::get('Show_Surgeoncies/{health_record_id}', 'App\Http\Controllers\PatientInfo\SurgeonciesController@show_surgeoncies');

    Route::get('Show_Surgeoncy_Info/{surgeoncy_id}', 'App\Http\Controllers\PatientInfo\SurgeonciesController@show_surgeoncy_info');

    Route::post('Update_Surgeoncy/{surgeoncy_id}', 'App\Http\Controllers\PatientInfo\SurgeonciesController@update_surgeoncy');

    ///الوصفة
    Route::post('CreatePrescription/{review_id}', 'App\Http\Controllers\DoctorReview\DocController@CreatePrescription');
    Route::get('ShowAllPrescriptions/{health_record_id}', 'App\Http\Controllers\DoctorReview\DocController@ShowAllPrescriptions');
    Route::get('ShowPrescription/{review_id}', 'App\Http\Controllers\DoctorReview\DocController@ShowPrescription');
    Route::post('UpdatePrescription/{review_id}', 'App\Http\Controllers\DoctorReview\DocController@UpdatePrescription');
    Route::get('CurrentDrugUsed/{health_record_id}', 'App\Http\Controllers\DoctorReview\DocController@CurrentDrugUsed');
    Route::post('DeletePrescription/{Prescription_id}', 'App\Http\Controllers\DoctorReview\DocController@DeletePrescription');

      //diseases
      Route::post(
        'New_Disease/{health_record_id}',
        'App\Http\Controllers\PatientDiseaseController@new_disease'
    );
    Route::get(
        'Show_Disease/{health_record_id}',
        'App\Http\Controllers\PatientDiseaseController@show_diseases'
    );

    Route::get('interactions/{review_id}','App\Http\Controllers\DoctorReview\ReviewController@interactions');
    Route::get('interactionsDiseaseDrug/{drug}/{health_record_id}', 'App\Http\Controllers\PatientDiseaseController@interactionsDiseaseDrug');

//////تعديلات

Route::post('CreateSpecVitalSigns/{health_record_id}', 'App\Http\Controllers\SpecVitalSignsController@CreateSpecVitalSigns');
Route::post('createDiagInstPres/{health_record_id}', 'App\Http\Controllers\DoctorReview\ReviewController@createDiagInstPres');


});


//patient
route::group(['prefix' => 'patient', 'middleware' => ['multiAuth:patient']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('UpdateProfile', 'App\Http\Controllers\PatientController@UpdateProfile');
    Route::get('Show_Tests', 'App\Http\Controllers\PatientController@tests_list');
    Route::get('Show_Personal_Info', 'App\Http\Controllers\PatientController@show_personal_infos');
    Route::get('Show_Reviews_Ascending_Order', 'App\Http\Controllers\PatientController@show_reviews_Ascending_Order');
    Route::get('Show_Reviews_Descending_Order', 'App\Http\Controllers\PatientController@show_reviews_Descending_Order');
    Route::post('Doctor_Reviews', 'App\Http\Controllers\PatientController@show_reviews_accordingTOdoctor');
    Route::get('Doctors', 'App\Http\Controllers\PatientController@doctors_list');
    Route::get('Doctor_Info/{doctor_id}', 'App\Http\Controllers\PatientController@doctor_info');
    Route::get('Show_Health_Info', 'App\Http\Controllers\PatientController@show_infos');
    Route::post('Review_Info', 'App\Http\Controllers\PatientController@review_info');
    Route::get('ActivityList', 'App\Http\Controllers\PatientController@activity_log');
    Route::get('CurrentDrugUsed', 'App\Http\Controllers\PatientController@CurrentDrugUsed');
});



////////////////////////////////////////////////////
Route::get('ShowAllDrugs/{start}/{end}', 'App\Http\Controllers\DrugController@ShowAllDrugs');
Route::get('ShowDrug/{id}', 'App\Http\Controllers\DrugController@ShowDrug');
Route::get('SearchDrug', 'App\Http\Controllers\DrugController@SearchDrug');
Route::get('ShowAllScientificName', 'App\Http\Controllers\DrugController@ShowAllScientificName');
Route::get('ShowAllGroups', 'App\Http\Controllers\DrugController@ShowAllGroups');
Route::post('AddScientificName', 'App\Http\Controllers\DrugController@AddScientificName');
Route::post('AddGroup', 'App\Http\Controllers\DrugController@AddGroup');
Route::post('StoreDrug', 'App\Http\Controllers\DrugController@StoreDrug');
Route::get('EditDrug/{id}', 'App\Http\Controllers\DrugController@EditDrug');
Route::post('UpdateDrug/{drug_id}', 'App\Http\Controllers\DrugController@UpdateDrug');
Route::post('DestroyDrug/{id}', 'App\Http\Controllers\DrugController@DestroyDrug');
Route::post('interactions', 'App\Http\Controllers\DrugController@interactions');
Route::get('Alternatives/{drug_id}', 'App\Http\Controllers\DrugController@Alternatives');
Route::get('ShowDrugOfGroup/{groupID}', 'App\Http\Controllers\DrugController@ShowDrugOfGroup');
Route::get('ShowAllNameDrugs', 'App\Http\Controllers\DrugController@ShowAllNameDrugs');

//lab
route::group(['prefix' => 'lab', 'middleware' => ['multiAuth:lab']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('profile', [App\Http\Controllers\MadicalTestResult\LabController::class, 'profile']);
    Route::post('EditProfile', [App\Http\Controllers\MadicalTestResult\LabController::class, 'profile']);
    Route::post('UpdateProfile', [App\Http\Controllers\MadicalTestResult\LabController::class, 'UpdateProfile']);
    //madical_tests_Rad
    Route::post('Check_To_Get', 'App\Http\Controllers\MadicalTestResult\TestResultController@check_to_get_');
    Route::post('Update_Madical_Test', 'App\Http\Controllers\MadicalTestResult\TestResultController@update_madical_test_info');
    Route::post('Result', 'App\Http\Controllers\MadicalTestResult\TestResultController@store_doc');
});

route::group(['prefix' => 'XrayCenter', 'middleware' => ['multiAuth:xray_center']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('profile', [App\Http\Controllers\MadicalTestResult\XrayCenterController::class, 'profile']);
    Route::post('EditProfile', [App\Http\Controllers\MadicalTestResult\XrayCenterController::class, 'profile']);
    Route::post('UpdateProfile', [App\Http\Controllers\MadicalTestResult\XrayCenterController::class, 'UpdateProfile']);
    //madical_tests_Rad
    Route::post('Check_To_Get_Radiological', 'App\Http\Controllers\MadicalTestResult\RadiologicalTestResultController@check_to_get_Rad');
    Route::post('Update_Madical_Test_Radiological', 'App\Http\Controllers\MadicalTestResult\RadiologicalTestResultController@update_madical_test_info_Rad');
    Route::post('Result_Radiological', 'App\Http\Controllers\MadicalTestResult\RadiologicalTestResultController@store_doc_Rad');
});
route::group(['prefix' => 'Hospital', 'middleware' => ['multiAuth:hospital']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('profile', [App\Http\Controllers\HospitalController::class, 'profile']);
    Route::post('EditProfile', [App\Http\Controllers\HospitalController::class, 'profile']);
    Route::post('UpdateProfile', [App\Http\Controllers\HospitalController::class, 'UpdateProfile']);
    Route::post('AddDoctor', [App\Http\Controllers\HospitalController::class, 'addDoctor']);
    Route::get('ShowDoctors', [App\Http\Controllers\HospitalController::class, 'ShowDoctors']);
    Route::get('ShowDoctor\{id}', [App\Http\Controllers\HospitalController::class, 'ShowDoctor']);
    Route::post('DeleteDoctor\{id}', [App\Http\Controllers\HospitalController::class, 'DeleteDoctor']);

});
route::group(['prefix' => 'pharmacist', 'middleware' => ['multiAuth:pharmacist']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('profile', [App\Http\Controllers\PharmacistController::class, 'profile']);
    Route::post('EditProfile', [App\Http\Controllers\PharmacistController::class, 'profile']);
    Route::post('UpdateProfile', [App\Http\Controllers\PharmacistController::class, 'UpdateProfile']);
    Route::post('Check_To_Get', 'App\Http\Controllers\PharmacistController@check_to_get_');
    Route::post('sellPrescription', 'App\Http\Controllers\PharmacistController@sellPrescription_');
    Route::get('ShowPrescriptions\{healthrecord_id}', 'App\Http\Controllers\PharmacistController@ShowPrescription');
});

//notifications
Route::POST('Notify', 'App\Http\Controllers\CheckController@notify_paitent');
Route::POST('Patient_UNreadNotifications', 'App\Http\Controllers\CheckController@patient_UNreadNotifications');
Route::POST('Accept', 'App\Http\Controllers\CheckController@patient_accept');
