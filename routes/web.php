<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'registerPost']);

Route::get('/login', [AuthController::class, 'login'])->name("login");
Route::post('/login', [AuthController::class, 'loginPost']);

Route::prefix("/admin")->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name("login");
    Route::post('/login', [AdminController::class, 'loginPost']);
    Route::post("/logout", [AdminController::class, 'logout']);

    Route::get('/home', [AdminController::class, 'home'])->middleware("auth");
    Route::get('/account/manage', [AdminController::class, 'accountManage']);
    Route::post('/account/manage', [AdminController::class, 'homePost'])->middleware("auth");
    Route::get('/region', function () {
        return view('admin/region', [
            "pageTitle" => "Region Management"
        ]);
    })->middleware("auth");

    Route::get('/user', [AdminController::class, 'user'])->middleware("auth");
});

Route::prefix('/users')
    ->middleware("auth")
    ->group(function () {
        Route::get('property', [UsersController::class, 'property'])->name('users.property');
        Route::get('property/detail/{id}', [UsersController::class, 'propertyDetail'])->name('property.detail');
        Route::get('property/detail', [UsersController::class, 'propertyAction'])->name('users.propertyAction');

        Route::get('/choose/agent', [UsersController::class, 'chooseAgent'])->name('users.chooseAgent');
        Route::post('/choose/agent', [UsersController::class, 'chooseAgentAction'])->name('users.chooseAgentAction');
  
        Route::get('appoinment', [UsersController::class, 'appoinment'])->name('users.appointment');
        Route::post('appoinment', [UsersController::class, 'appoinmentPost'])->name('users.appointmentAction');
        Route::post('/appointment/{id}/apporve', [UsersController::class, 'approveAppointment'])->name("users.approveAppointment");
        Route::get('appoinment/list', [UsersController::class, 'listAppoinment'])->name('users.listAppoinment');
        Route::get('appoinment/detail/{id}', [UsersController::class, 'appoinmentDetail'])->name('users.AppoinmentDetail');
        Route::post('appoinment/detail/{id}', [UsersController::class, 'appoinmentDetailPost'])->name('users.appoinmentDetailPost');
        Route::get('/reschedule-appointment/{id}', [UsersController::class, 'rescheduleAppointment'])->name("users.rescheduleAppointment");
        Route::post('/reschedule-appointment/{id}', [UsersController::class, 'rescheduleAppointmentAction'])->name("users.rescheduleAppointmentAction");

        Route::get('reschedule-appointment/{id}', [UsersController::class, 'rescheduleAppointment'])->name('users.rescheduleAppointment');
        Route::post('reschedule-appointment/{id}', [UsersController::class, 'rescheduleAppointmentAction'])->name('users.rescheduleAppointmentAction');

        Route::post('appoinment/detail/{id}', [UsersController::class, 'appoinmentDetailPost'])->name('users.appoinmentDetailPost');

        Route::get('review', [UsersController::class, 'review'])->name('users.review');

        Route::get('negotiation', [TransactionController::class, 'negotiation'])->name('users.negotiation');
        Route::get('negotiation/detail/{id}', [TransactionController::class, 'negotiationDetail'])->name('negotiation.detail');
        Route::get('transaction/method/negotiation', [TransactionController::class, 'negotiationTransaction'])->name('users.negotiationTransaction');
        Route::patch('negotiation/approve/{id}', [TransactionController::class, 'approveNegotiation'])->name('users.approveNegotiation');
        Route::patch('negotiation/reject/{id}', [TransactionController::class, 'rejectNegotiation'])->name('users.rejectNegotiation');
        Route::get('negotiation/rejection/reason/{id}', [TransactionController::class, 'negotiationRejectionReason'])->name('users.negotiationRejectionReason');


        Route::get('/transaction', [TransactionController::class, 'transaction'])->name('users.transaction');
        Route::get('/transaction/detail/{id}', [TransactionController::class, 'transactionDetail'])->name('users.detailTransaction');
        Route::get('/transaction/method', [TransactionController::class, 'transactionMethod'])->name('users.transactionMethod');
        Route::get('/transaction/method/direct',[TransactionController::class, 'transactionDirect'])->name('users.direct');
        Route::post('transaction/method/direct', [TransactionController::class, 'transactionDirectStore'])->name('users.DirectStore');

        Route::get('/negotiation/add', [TransactionController::class, 'addNegotiation'])->name('users.addNegotiation');
        Route::post('/negotiation/add', [TransactionController::class, 'negotiationStore'])->name('users.negotiationStore');
        Route::get('/renegotiation/{id}', [TransactionController::class, 'renegotiation'])->name('users.renegotiation');
        Route::patch('/renegotiation/{id}', [TransactionController::class, 'renegotiationUpdate'])->name('users.renegotiationUpdate');
});

Route::prefix('/agent')
    ->middleware("auth")
    ->group(function () {
        Route::get('/appointment', [AgentController::class, 'appointment'])->name("agent.appointmentList");
        Route::get('/appointment/{id}', [AgentController::class, 'appointmentDetail'])->name("agent.appointmentDetail");
        Route::post('/appointment/{id}/apporve', [AgentController::class, 'approveAppointment'])->name("agent.approveAppointment");
        Route::get('/reschedule-appointment/{id}', [AgentController::class, 'rescheduleAppointment'])->name("agent.rescheduleAppointment");
        Route::post('/reschedule-appointment/{id}', [AgentController::class, 'rescheduleAppointmentAction'])->name("agent.rescheduleAppointmentAction");
        Route::post("/logout", [AuthController::class, 'logout']);

        Route::get('/appointment/{id}/property/create', [AgentController::class, 'createProperty'])->name("agent.createProperty");
        Route::post('/appointment/{id}/property/create', [AgentController::class, 'propertyStore'])->name("agent.propertyStore");
        Route::get('/property', [AgentController::class, 'property'])->name('agent.property');
        Route::get('/property/{id}/detail', [AgentController::class, 'detailProperty'])->name("agent.detailProperty");
        Route::get('/property/{id}/edit', [AgentController::class, 'editProperty'])->name("agent.editProperty");
        Route::put('/property/{id}/update', [AgentController::class, 'updateProperty'])->name("agent.propertyUpdate");
        Route::delete('/property/{id}', [AgentController::class, 'deleteProperty'])->name('agent.propertyDelete');
        // Route::get('/property/{id}/publication', [AgentController::class, 'publication']);
        // Route::get('/offer', [AgentController::class, 'offer']);
        Route::get('negotiation/history', [AgentController::class, 'negotiationHistory'])->name('agent.negotiationHistory');
        Route::get('negotiation/Detail/{id}', [AgentController::class, 'negotiationDetail'])->name('agent.negotiationDetail');
        Route::patch('negotiation/approve/{id}', [AgentController::class, 'approveNegotiation'])->name('agent.approveNegotiation');
        Route::patch('negotiation/reject/{id}', [AgentController::class, 'rejectNegotiation'])->name('agent.rejectNegotiation');
        Route::get('negotiation/rejection/reason/{id}', [AgentController::class, 'negotiationRejectionReason'])->name('agent.negotiationRejectionReason');

    });

Route::prefix('/notary')
    ->middleware("auth")
    ->group(function() {
        Route::get('/home', function () {
            return view('notary/home');
        });
        Route::get('/verification', function () {
            return view('notary.verification');
        });
        Route::get('/add-AJB', function () {
            return view('notary.add-AJB');
        });
        Route::get('/upload-detail', function () {
            return view('notary.upload-detail');
        });
        Route::get('/upload-invalid', function () {
            return view('notary.upload-invalid');
        });
        Route::get('/upload-successful', function () {
            return view('notary.upload-successful');
        })->name('upload.success');
        Route::get('/verification-approve', function () {
            return view('notary.verification-approve');
        });
        Route::get('/verification-revision', function () {
            return view('notary.verification-revision');
        });
        Route::get('/verification-reject', function () {
            return view('notary.verification-reject');
        });
        Route::get('/AJB-detail', function () {
            return view('notary.AJB-detail');
        });
        Route::get('/verification-final', function () {
            return view('notary.verification-final');
        });
        Route::get('/verification-final-reject', function () {
            return view('notary.verification-final-reject');
        });
    });