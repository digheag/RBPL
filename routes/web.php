<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthController;
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
    Route::get('/account/manage', [AdminController::class, 'accountManage'])->middleware("auth");
    Route::post('/account/manage', [AdminController::class, 'homePost'])->middleware("auth");
    Route::get('/region', function () {
        return view('admin/region', [
            "pageTitle" => "Region Management"
        ]);
    })->middleware("auth");
    
    Route::get('/user', [AdminController::class, 'user'])->middleware("auth");
});

Route::get('notary/home', function () {
    return view('notary/home');
});

Route::prefix('/users')->group(function (){
    Route::get('property', function () {
        return view('users/property');
    });
     Route::get('property/detail/{id}', function ($id) {
        return view('users/detail-property', compact('id'));
    })->name('property.detail');

    Route::get('/transaction', function () {
        return view('users/transaction', [
        "propertyName" => "Modern Building House",
        "transactionType" => "Pembayaran Langsung",
        "price" => "IDR 500.000.000,00"
    ]);
    });

    Route::get('/transaction/method', function () {
        return view('users/method-transaction');
    });
    Route::get('/add-negotiation', function () {
        return view('users.add-negotiation');
    });
    Route::get('/negotiation', function () {
        return view('users.negotiation');
    });
    Route::get('/negotiation-detail', function () {
        return view('users.negotiation-detail');
    });
    Route::get('/negotiation-detail-rejected', function () {
        return view('users.negotiation-detail-rejected');
    });
    Route::get('/renegotiation', function () {
        return view('users.renegotiation');
    });
});


Route::get('/users/choose/agent', function () {
    return view('users/choose-agent');
});

Route::get('/users/property/add', function () {
    return view('users/add-property');
});

Route::get('/users/appoinment', function () {
    return view('users/appoinment');
});



Route::prefix('/agent')
->middleware("auth")
->group(function () {
    Route::get('/appointment', [AgentController::class, 'appointment']);
    Route::get('/appointment/{id}', [AgentController::class, 'appointmentDetail']);
    Route::post("/logout", [AuthController::class, 'logout']);

    Route::get('/appointment/{id}/create-property', [AgentController::class, 'createProperty']);
    Route::get('/property', [AgentController::class, 'property']);
    Route::get('/property/{id}', [AgentController::class, 'detailProperty']);
    Route::get('/property/{id}/publication', [AgentController::class, 'publication']);
    Route::get('/offer', [AgentController::class, 'offer']);
    Route::get('/history-negotiation', function () {
    return view('agent.history-negotiation'); });
    Route::get('/negotiation-pending', function () {
    return view('agent.negotiation-pending'); });
    Route::get('/negotiation-rejected', function () {
    return view('agent.negotiation-rejected'); });
});

Route::get('/notary/home', function () {
    return view('notary/home');
});
Route::get('/notary/verification', function () {
    return view('notary.verification');
});
Route::get('/notary/add-AJB', function () {
    return view('notary.add-AJB');
});
Route::get('/notary/upload-detail', function () {
    return view('notary.upload-detail');
});
Route::get('/notary/upload-invalid', function () {
    return view('notary.upload-invalid');
});
Route::get('/notary/upload-successful', function () {
    return view('notary.upload-successful');
})->name('upload.success');
Route::get('/notary/verification-approve', function () {
    return view('notary.verification-approve');
});
Route::get('/notary/verification-revision', function () {
    return view('notary.verification-revision');
});
Route::get('/notary/verification-reject', function () {
    return view('notary.verification-reject');
});
Route::get('/notary/AJB-detail', function () {
    return view('notary.AJB-detail');
});
Route::get('/notary/verification-final', function () {
    return view('notary.verification-final');
});
Route::get('/notary/verification-final-reject', function () {
    return view('notary.verification-final-reject');
});