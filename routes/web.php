<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BreedingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmbryoController;
use App\Http\Controllers\Admin\ExpensesController;
use App\Http\Controllers\Admin\ExternalInvoiceController;
use App\Http\Controllers\Admin\FeedingBeddingController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\HorseController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\Admin\InternalInvoiceController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\StudController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\TreatmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\staff\StaffController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::get('/keep-alive', function() {
    return response()->json(['status' => 'alive']);
});
Route::get('locale/{lang}', [LanguageController::class, 'setlocale'])->name('locale.set');

Route::get('/', [LoginController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/Home', [HomeController::class, 'index'])->name('Home');
    Route::get('User/Stud/{stud_name}/{item_name}', [HomeController::class, 'show']);
    Route::get('visit/{id}', [HomeController::class, 'visittable']);
    Route::get('invoice/{id}', [HomeController::class, 'invoicetable']);


    Route::middleware('staff')->group(function () {
        // --------------------------------------------------------------
        Route::get('/Staff', [StaffController::class, 'index']);
        // --------------------------------------------------------------

        Route::post('insert-note', [NoteController::class, 'store']);
        Route::put('update-task/{id}', [TaskController::class, 'update']);

        Route::post('insert-horse', [HorseController::class, 'store']);
        Route::post('insert-visit', [VisitController::class, 'store']);
        Route::post('insert-treatment', [TreatmentController::class, 'store']);
        Route::post('insert-breeding', [BreedingController::class, 'store']);
        Route::post('insert-embryo', [EmbryoController::class, 'store']);

        Route::post('insert-feedingbedding', [FeedingBeddingController::class, 'store']);
        Route::post('insert-feeding', [FeedingBeddingController::class, 'storefeeding']);
        Route::post('insert-bedding', [FeedingBeddingController::class, 'storebedding']);

        Route::post('insert-expense', [ExpensesController::class, 'store']);

        Route::post('insert-pharmacy', [PharmacyController::class, 'store']);
        // Medical Internal Invoices Routes
        Route::post('insert-supplier', [InternalInvoiceController::class, 'storesupplier']);
        Route::post('insert-medicalinternalinvoice', [InternalInvoiceController::class, 'medstore']);
        Route::post('insert-medexternalinvoice', [ExternalInvoiceController::class, 'medstore']);
        // Supplies Internal Invoices Routes
        Route::post('insert-suppliesinternalinvoice', [InternalInvoiceController::class, 'supstore']);
        Route::post('insert-supexternalinvoice', [ExternalInvoiceController::class, 'supstore']);


        // accountant
        Route::get('Suppliers', [InternalInvoiceController::class, 'indexsupplier']);
        Route::get('Supplier/Details/{id}', [InternalInvoiceController::class, 'showsupplier']);
        Route::get('Supplier/Accounts/{name}', [InternalInvoiceController::class, 'accountsupplier']);

        Route::get('Doctors/Details', [DashboardController::class, 'doctorscount']);
        Route::get('Doctor/Accountants/{name}', [DashboardController::class, 'doctorcount']);

        Route::get('VisitsCount', [VisitController::class, 'countvisit']);
        Route::get('BreedingsCount', [BreedingController::class, 'countbreeding']);
        Route::get('EmbryosCount', [EmbryoController::class, 'countembryo']);
        Route::get('FinancialsCount', [FinancialController::class, 'financialscount']);
        Route::get('Details/Finance/{id}', [FinancialController::class, 'show']);
        Route::get('SalaryCount', [SalaryController::class, 'salarycount']);
        Route::get('ExpensesCount', [ExpensesController::class, 'expensescount']);
        Route::get('FeedingBeddingCount', [FeedingBeddingController::class, 'feedingbeddingcount']);
        Route::get('PharmacyCount', [PharmacyController::class, 'pharmacycount']);
        Route::get('InternalInvoicesCount', [InternalInvoiceController::class, 'invoicescount'])->name('internalinvoices.count');
        Route::get('ExternalInvoicesCount', [ExternalInvoiceController::class, 'countexternalinvoices']);

        Route::get('Details/Stud/{id}', [StudController::class, 'show']);
        Route::get('Studs/Counts', [StudController::class, 'countstuds']);
        Route::get('Studs/Visit/Table/{name}', [StudController::class, 'visittable']);
        Route::get('Studs/Externalinvoices/Table/{name}', [StudController::class, 'invoicetable']);

        // Horses Details Routes
        Route::get('Stud/{stud_name}/{item_name}', [HorseController::class, 'show']);
        Route::get('Horse/Details/{name}', [HorseController::class, 'show']);
        Route::get('Visit/Table/{name}', [HorseController::class, 'visittable']);
        Route::get('Treatment/Table/{name}', [HorseController::class, 'treatmenttable']);
        Route::get('Task/Table/{name}', [HorseController::class, 'tasktable']);
        Route::get('Breeding/Table/{name}', [HorseController::class, 'breedingtable']);
        Route::get('Embryo/Table/{name}', [HorseController::class, 'embryotable']);
        Route::get('Feeding&Bedding/Table/{name}', [HorseController::class, 'feedingbeddingtable']);
        Route::get('Vaccine/Table/{id}', [HorseController::class, 'vaccinetable']);

        // Horses Visit Routes
        // Route::get('show-horse/{item_name}', [HorseController::class, 'showvisit']);



    });
    Route::middleware('admin')->group(function () {
        // --------------------------------------------------------------
        Route::get('/Dashboard', [AdminController::class, 'index']);
        // --------------------------------------------------------------\
        Route::post('insert-user', [DashboardController::class, 'store']);
        Route::put('update-user/{id}', [DashboardController::class, 'update']);

        Route::put('details-users/{id}', [DashboardController::class, 'details']);
        Route::get('delete-user/{id}', [DashboardController::class, 'destroy']);


    });


    Route::middleware('supervisor')->group(function () {
        Route::get('/Supervisor', [AdminController::class, 'index']);
        // Search Routes
        Route::post('searchstud', [StudController::class, 'search']);
        Route::get('stud-list', [StudController::class, 'studlistAjax']);


        Route::get('/accountant', [StaffController::class, 'index']);
        // Studs Routes
        Route::get('Studs', [StudController::class, 'index']);
        // Tasks Routs
        Route::get('Tasks', [TaskController::class, 'index']);
        Route::get('Daily/Tasks', [TaskController::class, 'dailytasks']);
        Route::get('Complete/Tasks', [TaskController::class, 'completetask']);
        Route::get('add-task', [TaskController::class, 'create']);
        Route::post('insert-task', [TaskController::class, 'store']);
        Route::get('delete-task/{id}', [TaskController::class, 'destroy']);

        // Tasks Desciption
        Route::get('delete-taskdesc/{id}', [TaskController::class, 'destroytaskdesc']);

        // Notes Routes
        Route::get('Notes',  [NoteController::class, 'index']);
        Route::get('my-notes/{id}', [NoteController::class, 'mynotes']);
        Route::get('delete-notes/{id}', [NoteController::class, 'destroy']);
        Route::put('update-notes/{id}', [NoteController::class, 'update']);



        // Users Routes
        Route::get('users', [DashboardController::class, 'index']);
        // Tasks Routes
        Route::get('my-task/{id}', [TaskController::class, 'mytask']);
        Route::get('edit-taskdesc/{id}', [TaskController::class, 'edittaskdesc']);
        Route::put('update-taskdesc/{id}', [TaskController::class, 'updatetaskdesc']);


        // Studs Routes
        Route::get('add-stud', [StudController::class, 'create']);
        Route::post('insert-stud', [StudController::class, 'store']);
        Route::get('edit-stud/{id}', [StudController::class, 'edit']);
        Route::put('update-stud/{id}', [StudController::class, 'update']);
        Route::get('delete-stud/{id}', [StudController::class, 'destroy']);


        // Horses Routes
        Route::put('update-horse/{id}', [HorseController::class, 'update']);
        Route::get('edit-horse/{name}', [HorseController::class, 'edit']); // <-- Add this line
        Route::get('delete-horse/{id}', [HorseController::class, 'destroy']);

        // Breedings Routes
        Route::get('Breedings', [BreedingController::class, 'index']);
        Route::get('Details/Breeding/{id}', [BreedingController::class, 'show']);
        Route::put('update-breeding/{id}', [BreedingController::class, 'update']);
        Route::get('delete-breeding/{id}', [BreedingController::class, 'destroy']);
        // Embryo Routes
        Route::get('Embryos', [EmbryoController::class, 'index']);
        Route::put('update-embryo/{id}', [EmbryoController::class, 'update']);
        Route::get('delete-embryo/{id}', [EmbryoController::class, 'destroy']);





        // Visits Routes
        Route::get('Visits', [VisitController::class, 'index']);
        Route::get('my-visit/{id}', [VisitController::class, 'myvisit']);
        Route::get('add-visit', [VisitController::class, 'create']);
        Route::put('update-visit/{id}', [VisitController::class, 'update']);
        Route::get('delete-visit/{id}', [VisitController::class, 'destroy']);
        Route::get('Details/Visit/{id}', [VisitController::class, 'showvisit']);
        Route::get('delete-visitdescs/{id}', [VisitController::class, 'destroyvisitdescs']);
        Route::put('update-visitdescs/{id}', [VisitController::class, 'updatevisitdescs']);

        // Pharmacy Routes
        Route::get('Pharmacy', [PharmacyController::class, 'index']);
        Route::put('update-pharmacy/{id}', [PharmacyController::class, 'update']);
        Route::get('delete-pharmacy/{id}', [PharmacyController::class, 'destroy']);

        // Treatments Routes
        Route::get('Treatments', [TreatmentController::class, 'index']);
        Route::get('add-treatment', [TreatmentController::class, 'create']);
        Route::get('edit-treatment/{id}', [TreatmentController::class, 'edit']);
        Route::put('update-treatment/{id}', [TreatmentController::class, 'update']);
        Route::get('Treatment/details/{id}', [TreatmentController::class, 'show']);
        Route::get('delete-treatment/{id}', [TreatmentController::class, 'destroy']);
        // treatments Desciption
        Route::get('edit-treatmentdesc/{id}', [TreatmentController::class, 'edittreatmentdesc']);
        Route::put('update-treatmentdesc/{id}', [TreatmentController::class, 'updatetreatmentdesc']);
        Route::get('delete-treatmentdesc/{id}', [TreatmentController::class, 'destroytreatmentdesc']);


        // Financials Routes
        Route::get('Financials', [FinancialController::class, 'index']);
        Route::post('insert-finance', [FinancialController::class, 'store']);
        Route::put('update-finance/{id}', [FinancialController::class, 'update']);
        Route::get('delete-finance/{id}', [FinancialController::class, 'destroy']);

        // Expenses Routes
       Route::get('Expenses', [ExpensesController::class, 'index']);
       Route::put('update-expense/{id}', [ExpensesController::class, 'update']);
       Route::get('delete-expense/{id}', [ExpensesController::class, 'destroy']);
       // Salary Routes
       Route::get('Salary', [SalaryController::class, 'index']);
       Route::get('Details/Salary/{id}', [SalaryController::class, 'show']);
       Route::post('insert-salary', [SalaryController::class, 'store']);
       Route::put('update-salary/{id}', [SalaryController::class, 'update']);
       Route::get('delete-salary/{id}', [SalaryController::class, 'destroy']);
       // Salary Descrption Routes
       Route::post('insert-salarydesc', [SalaryController::class, 'storesalarydesc']);
       Route::put('update-salarydesc/{id}', [SalaryController::class, 'updatesalarydesc']);
       Route::get('delete-salarydesc/{id}', [SalaryController::class, 'destroysalarydesc']);


       // Feedingbeddings Routes
       Route::get('FeedingBedding', [FeedingBeddingController::class, 'index']);
       Route::put('update-feedingbedding/{id}', [FeedingBeddingController::class, 'update']);
       Route::get('FeedingOrBedding/Details/{id}', [FeedingBeddingController::class, 'show']);
       Route::get('delete-feedingbedding/{id}', [FeedingBeddingController::class, 'destroy']);

       // feedings Routes
       Route::get('Feeding', [FeedingBeddingController::class, 'indexfeeding']);
       Route::put('update-feeding/{id}', [FeedingBeddingController::class, 'updatefeeding']);
       Route::get('Feeding/Details/{id}', [FeedingBeddingController::class, 'showfeeding']);
       Route::get('delete-feeding/{id}', [FeedingBeddingController::class, 'destroyfeeding']);
       // beddings Routes
       Route::get('Bedding', [FeedingBeddingController::class, 'indexbedding']);
       Route::get('delete-bedding/{id}', [FeedingBeddingController::class, 'destroybedding']);
       Route::put('update-bedding/{id}', [FeedingBeddingController::class, 'updatebedding']);

        //  Invoices Routes
        Route::get('Medicalinternalinvoices', [InternalInvoiceController::class, 'medicalindex']);
        Route::get('Suppliesinternalinvoices', [InternalInvoiceController::class, 'suppliesindex']);
        Route::get('add-internalinvoice', [InternalInvoiceController::class, 'create']);
        Route::put('update-internalinvoice/{id}', [InternalInvoiceController::class, 'update']);
        Route::get('delete-internalinvoice/{id}', [InternalInvoiceController::class, 'destroy']);
        // Medical  Invoices Routes
        Route::put('update-medicalinvoice/{id}', [InternalInvoiceController::class, 'updatemedicine']);
        Route::get('delete-medicalinvoice/{id}', [InternalInvoiceController::class, 'destroymedicine']);
        // Supplies  Invoices Routes
        Route::put('update-suppliesinvoice/{id}', [InternalInvoiceController::class, 'updatesupplies']);
        Route::get('delete-suppliesinvoice/{id}', [InternalInvoiceController::class, 'destroysupplies']);
        // Supplier Routes
        Route::get('edit-supplier/{id}', [InternalInvoiceController::class, 'editsupplier']);
        Route::put('update-supplier/{id}', [InternalInvoiceController::class, 'updatesupplier']);
        Route::get('delete-supplier/{id}', [InternalInvoiceController::class, 'destroysupplier']);
        // External Invoices Routes
        Route::get('Medicalexternalinvoices', [ExternalInvoiceController::class, 'medicalindex']);
        Route::get('Suppliesexternalinvoices', [ExternalInvoiceController::class, 'suppliesindex']);
        Route::get('add-externalinvoice', [ExternalInvoiceController::class, 'create']);
        Route::put('update-externalinvoice/{id}', [ExternalInvoiceController::class, 'update']);
        Route::get('delete-externalinvoice/{id}', [ExternalInvoiceController::class, 'destroy']);


    });
});

Auth::routes();
