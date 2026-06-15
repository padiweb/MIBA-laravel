<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DebitController;
use App\Http\Controllers\KreditController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\Portal\AuthController as PortalAuthController;
use App\Http\Controllers\Portal\DashboardController as PortalDashboardController;
use App\Http\Controllers\Portal\ProfileController as PortalProfileController;
use App\Http\Controllers\Portal\PayoutController as PortalPayoutController;

// Auth
Route::get('/',  [AuthController::class, 'index']);
Route::get('/login',  [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth.miba')->prefix('manage')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/events', [DashboardController::class, 'getEvents'])->name('dashboard.events');

    // Student
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/',             [StudentController::class, 'index'])->name('index');
        Route::get('/tambah',       [StudentController::class, 'create'])->name('create');
        Route::post('/tambah',      [StudentController::class, 'store'])->name('store');
        Route::get('/edit/{id}',    [StudentController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}',    [StudentController::class, 'update'])->name('update');
        Route::delete('/{id}',      [StudentController::class, 'destroy'])->name('destroy');
        Route::get('/detail/{id}',  [StudentController::class, 'show'])->name('show');
        // Kelas
        Route::get('/kelas',        [StudentController::class, 'classes'])->name('classes');
        Route::post('/kelas',       [StudentController::class, 'storeClass'])->name('classes.store');
        Route::put('/kelas/{id}',   [StudentController::class, 'updateClass'])->name('classes.update');
        Route::delete('/kelas/{id}',[StudentController::class, 'destroyClass'])->name('classes.destroy');
        // Jurusan
        Route::get('/jurusan',        [StudentController::class, 'majorsList'])->name('majors');
        Route::post('/jurusan',       [StudentController::class, 'storeMajors'])->name('majors.store');
        Route::put('/jurusan/{id}',   [StudentController::class, 'updateMajors'])->name('majors.update');
        Route::delete('/jurusan/{id}',[StudentController::class, 'destroyMajors'])->name('majors.destroy');

        Route::get('/rpw/{id}',  [StudentController::class, 'resetPasswordForm'])->name('resetPasswordForm');
        Route::post('/rpw/{id}', [StudentController::class, 'resetPassword'])->name('resetPassword');

        Route::get('/kartu/{id}',  [StudentController::class, 'printPdf'])->name('printPdf');
        Route::post('/kartu',      [StudentController::class, 'printCards'])->name('printCards');

        Route::get('/import',  [StudentController::class, 'importForm'])->name('importForm');
        Route::post('/import', [StudentController::class, 'importStore'])->name('importStore');
        Route::get('/download-template', [StudentController::class, 'downloadTemplate'])->name('downloadTemplate');
        // Kenaikan kelas & kelulusan
        Route::get('/upgrade',  [StudentController::class, 'upgrade'])->name('upgrade');
        Route::get('/pass',     [StudentController::class, 'pass'])->name('pass');
        Route::post('/multiple',[StudentController::class, 'multiple'])->name('multiple');
    });

    // Payment (jenis pembayaran)
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/',          [PaymentController::class, 'index'])->name('index');
        Route::get('/tambah',    [PaymentController::class, 'create'])->name('create');
        Route::post('/tambah',   [PaymentController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{id}',   [PaymentController::class, 'destroy'])->name('destroy');

        // ===== Tarif Pembayaran - Bulanan =====
        Route::get('/view-bulan/{payment}',              [PaymentController::class, 'viewBulan'])->name('viewBulan');
        Route::get('/add-bulan/{payment}/{mode}',        [PaymentController::class, 'addBulanForm'])->name('addBulanForm');
        Route::post('/add-bulan/{payment}/{mode}',       [PaymentController::class, 'storeBulan'])->name('storeBulan');
        Route::get('/edit-bulan/{payment}/{student}',    [PaymentController::class, 'editBulan'])->name('editBulan');
        Route::post('/edit-bulan/{payment}/{student}',   [PaymentController::class, 'updateBulan'])->name('updateBulan');

        // ===== Tarif Pembayaran - Bebas =====
        Route::get('/view-bebas/{payment}',                       [PaymentController::class, 'viewBebas'])->name('viewBebas');
        Route::get('/add-bebas/{payment}/{mode}',                 [PaymentController::class, 'addBebasForm'])->name('addBebasForm');
        Route::post('/add-bebas/{payment}/{mode}',                [PaymentController::class, 'storeBebas'])->name('storeBebas');
        Route::get('/edit-bebas/{payment}/{student}/{bebas}',     [PaymentController::class, 'editBebas'])->name('editBebas');
        Route::post('/edit-bebas/{payment}/{student}/{bebas}',    [PaymentController::class, 'updateBebas'])->name('updateBebas');
        Route::delete('/delete-bebas/{payment}/{student}/{bebas}',[PaymentController::class, 'deletePaymentBebas'])->name('deletePaymentBebas');
    });

    // Payout (pembayaran siswa) - alur seperti CI3
    Route::prefix('payout')->name('payout.')->group(function () {
        Route::get('/',                                          [PayoutController::class, 'index'])->name('index');
        Route::get('/bayar/{payment_id}/{student_id}',          [PayoutController::class, 'bayar'])->name('bayar');
        Route::get('/pay/{payment_id}/{student_id}/{bulan_id}', [PayoutController::class, 'pay'])->name('pay');
        Route::get('/unpay/{payment_id}/{student_id}/{bulan_id}',[PayoutController::class, 'unpay'])->name('unpay');
        Route::post('/update-desc',                             [PayoutController::class, 'updateDesc'])->name('updateDesc');
        Route::post('/payout-bebas',                            [PayoutController::class, 'payoutBebas'])->name('payoutBebas');
        Route::get('/riwayat-bebas/{bebas_id}',                 [PayoutController::class, 'riwayatBebas'])->name('riwayatBebas');
        Route::get('/delete-pay-free/{payment_id}/{student_id}/{bebas_id}/{bebas_pay_id}', [PayoutController::class, 'deletePayFree'])->name('deletePayFree');
        Route::get('/cetak-bukti',                              [PayoutController::class, 'cetakBukti'])->name('cetakBukti');
        Route::get('/print-bill',                               [PayoutController::class, 'cetakTagihan'])->name('printBill');
        Route::get('/cetak/{bulan_id}',                         [PayoutController::class, 'cetak'])->name('cetak');
        Route::get('/cetak-tagihan',                            [PayoutController::class, 'cetakTagihan'])->name('cetakTagihan');
    });

    // Period
    Route::prefix('period')->name('period.')->group(function () {
        Route::get('/',              [PeriodController::class, 'index'])->name('index');
        Route::post('/',             [PeriodController::class, 'store'])->name('store');
        Route::put('/{id}',          [PeriodController::class, 'update'])->name('update');
        Route::get('/aktif/{id}',    [PeriodController::class, 'setActive'])->name('active');
        Route::delete('/{id}',       [PeriodController::class, 'destroy'])->name('destroy');
    });

    // Pos (jenis biaya)
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/',          [PosController::class, 'index'])->name('index');
        Route::post('/',         [PosController::class, 'store'])->name('store');
        Route::put('/{id}',      [PosController::class, 'update'])->name('update');
        Route::delete('/{id}',   [PosController::class, 'destroy'])->name('destroy');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',           [UsersController::class, 'index'])->name('index');
        Route::post('/',          [UsersController::class, 'store'])->name('store');
        Route::get('/{id}',       [UsersController::class, 'show'])->name('show');
        Route::put('/{id}',       [UsersController::class, 'update'])->name('update');
        Route::delete('/{id}',    [UsersController::class, 'destroy'])->name('destroy');
        Route::get('/rpw/{id}',   [UsersController::class, 'resetPasswordForm'])->name('resetPasswordForm');
        Route::post('/rpw/{id}',  [UsersController::class, 'resetPassword'])->name('resetPassword');
        Route::get('/roles',      [UsersController::class, 'roles'])->name('roles');
        Route::post('/roles',     [UsersController::class, 'storeRole'])->name('roles.store');
        Route::put('/roles/{id}', [UsersController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{id}', [UsersController::class, 'destroyRole'])->name('roles.destroy');
    });

    // Setting
    Route::get('/setting',  [SettingController::class, 'index'])->name('setting.index');
    Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');

    // Report
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/',          [ReportController::class, 'index'])->name('index');
        Route::get('/cetak',     [ReportController::class, 'cetak'])->name('cetak');
        Route::get('/bill',      [ReportController::class, 'bill'])->name('bill');
        Route::get('/bill-export', [ReportController::class, 'billExport'])->name('billExport');
        Route::get('/export-keuangan',   [ReportController::class, 'exportKeuangan'])->name('exportKeuangan');
        Route::get('/bill-detail-export',[ReportController::class, 'billDetailExport'])->name('billDetailExport');
    });

    // Month (Bulan)
    Route::prefix('month')->name('month.')->group(function () {
        Route::get('/',        [MonthController::class, 'index'])->name('index');
        Route::post('/',       [MonthController::class, 'store'])->name('store');
        Route::put('/{id}',    [MonthController::class, 'update'])->name('update');
        Route::delete('/{id}', [MonthController::class, 'destroy'])->name('destroy');
    });

    // Class (Kelas - standalone Akademik)
    Route::prefix('class')->name('class.')->group(function () {
        Route::get('/',        [ClassController::class, 'index'])->name('index');
        Route::post('/',       [ClassController::class, 'store'])->name('store');
        Route::put('/{id}',    [ClassController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClassController::class, 'destroy'])->name('destroy');
    });

    // Maintenance (Backup)
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/',       [MaintenanceController::class, 'index'])->name('index');
        Route::get('/backup', [MaintenanceController::class, 'backup'])->name('backup');
    });

    // Debit & Kredit
    Route::resource('debit',  DebitController::class)->except(['show']);
    Route::post('/debit/glob',    [DebitController::class, 'storeGlob'])->name('debit.storeGlob');

    Route::resource('kredit', KreditController::class)->except(['show']);
    Route::post('/kredit/glob',   [KreditController::class, 'storeGlob'])->name('kredit.storeGlob');

    // Information
    Route::resource('information', InformationController::class)->except(['show']);

    // Holiday
    Route::resource('holiday', HolidayController::class)->except(['show']);

    // Logs
    Route::get('/logs', [LogsController::class, 'index'])->name('logs.index');

    // Profile
    Route::get('/profile',           [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit',      [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/cpw',       [ProfileController::class, 'cpw'])->name('profile.cpw');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| PORTAL SISWA (Student Portal) - terpisah dari area "manage"
|--------------------------------------------------------------------------
*/
Route::prefix('portal')->name('portal.')->group(function () {
    // Auth
    Route::get('/login',  [PortalAuthController::class, 'login'])->name('login');
    Route::post('/login', [PortalAuthController::class, 'doLogin'])->name('doLogin');
    Route::get('/logout', [PortalAuthController::class, 'logout'])->name('logout');

    // Protected
    Route::middleware('auth.student')->group(function () {
        Route::get('/', [PortalDashboardController::class, 'index'])->name('dashboard');

        Route::get('/payout', [PortalPayoutController::class, 'index'])->name('payout');

        Route::get('/profile',          [PortalProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit',     [PortalProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile',          [PortalProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/cpw',      [PortalProfileController::class, 'changePasswordForm'])->name('profile.cpw');
        Route::post('/profile/cpw',     [PortalProfileController::class, 'changePassword'])->name('profile.cpw.update');
    });
});
