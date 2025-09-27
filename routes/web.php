<?php

use App\Http\Controllers\{
    AccountController,
    AuditController,
    ChatController,

    FileController,
    GroupController,
    HistoryController,
    NotificationController,
    RequestController,
    UploadController,
    UserController,
    QAOController,
    CollegeController,
    DepartmentController,
    ProgramController,
    FacultyController,
    CourseController,
    SettingController,
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ==========================
// Authentication
// ==========================
Auth::routes();

Route::get('/', function () {
    return Auth::check() ? redirect('/home') : redirect('/login');
});

Route::get('/php-extensions', function () {
    return phpinfo(); // or collect(get_loaded_extensions());
});





// ==========================
// Process Owners Routes
// ==========================
Route::prefix('process-owners')->middleware('auth')->name('process-owners.')->group(function () {
    Route::get('/dashboard', function () {
        return view('process-owners.dashboard');
    })->name('dashboard');
    
    
    Route::get('/document-management', function () {
        return view('process-owners.document-management');
    })->name('document-management');
    Route::get('/profile', function () {
        return view('process-owners.profile');
    })->name('profile');
    Route::get('/history-log', function () {
        return view('process-owners.history-log');
    })->name('history-log');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/search/live', [App\Http\Controllers\HomeController::class, 'liveSearch'])->name('search.live');

// ==========================
// Super Admin Routes
// ==========================


// ==========================
// Documents
// ==========================
Route::middleware('auth')->prefix('documents')->name('documents.')->group(function () {
    Route::get('/total', [App\Http\Controllers\HomeController::class, 'totalDocuments'])->name('total');
    Route::get('/controlled', [App\Http\Controllers\HomeController::class, 'controlledDocuments'])->name('controlled');
    Route::get('/pending', [App\Http\Controllers\HomeController::class, 'pendingApprovals'])->name('pending');
    Route::get('/downloads', [App\Http\Controllers\HomeController::class, 'totalDownloads'])->name('downloads');

    Route::get('/request_edit/{id}', [UploadController::class, 'requestEdit'])->name('request_edit');

Route::get('/total_data', function () {
    return response()->json(['message' => 'This is total data.']);
})->name('total.data');
    Route::get('/{id}/replace', [UploadController::class, 'update'])->name('replace');
    Route::post('/request/{id}/approve', [UploadController::class, 'approveRequest'])->name('request.approve');
    Route::post('/request/{id}/reject', [UploadController::class, 'rejectRequest'])->name('request.reject');
    Route::post('/{id}/archive', [UploadController::class, 'archiveDocument'])->name('archive');
    Route::put('/{id}/metadata', [UploadController::class, 'updateMetadata'])->name('updateMetadata');
    Route::get('/{id}/edit', [UploadController::class, 'edit'])->name('editDocument');
    Route::get('/{id}/manage-access', [UploadController::class, 'manageAccess'])->name('manageAccess');
    Route::post('/{id}/move-to-controlled', [UploadController::class, 'moveToControlled'])->name('moveToControlled');
    Route::post('/{id}/move-to-uncontrolled', [UploadController::class, 'moveToUncontrolled'])->name('moveToUncontrolled');
    Route::get('/download/{id}', [UploadController::class, 'downloadDocument'])->name('download');
    Route::get('/request_stats/{id}/{type}/{remarks}', [UploadController::class, 'request_stats'])->name('request_stats');
});

Route::post('/messages/{message}/read', [ChatController::class, 'markAsRead'])->name('messages.read');
Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
Route::post('/chat/group/{group}/message', [ChatController::class, 'send'])->name('chat.send');

Route::get('/notifications/get', [NotificationController::class, 'getNotifications'])->name('notifications.get');

// ==========================
// Account
// ==========================
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/profile', [AccountController::class, 'index'])->name('profile');
    Route::get('/password', [AccountController::class, 'editPassword'])->name('password.edit');
    Route::post('/password', [AccountController::class, 'updatePassword'])->name('password.update');
});

// ==========================
// Users
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/role-management', [UserController::class, 'roleManagement'])->name('users.role.management');
    Route::put('/users/roles/{role}/permissions', [UserController::class, 'updatePermissions'])->name('users.roles.updatePermissions');
});

// ==========================
// Uploads
// ==========================
Route::middleware('auth')->prefix('uploads')->name('uploads.')->group(function () {
    Route::get('/', [UploadController::class, 'index'])->name('index');
    Route::get('/create', [UploadController::class, 'create'])->name('create');
    Route::post('/store', [UploadController::class, 'store'])->name('store');
    Route::get('/view/{upload_id}', [UploadController::class, 'view'])->name('view');
    Route::get('/edit/{id}', [UploadController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [UploadController::class, 'update'])->name('update');
    Route::delete('/{upload}', [UploadController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/revisions', [UploadController::class, 'revisions'])->name('revisions');
});

// ==========================
// QAO Routes
// ==========================
Route::middleware(['auth', 'can:qao-only'])->prefix('qao')->group(function () {
    Route::get('/dashboard', [QAOController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [QAOController::class, 'users'])->name('users');
    Route::get('/settings', [QAOController::class, 'settings'])->name('settings');
    Route::get('/audit-logs', [QAOController::class, 'auditLogs'])->name('qao.audit-logs');
});

Route::middleware(['auth', 'can:superadmin-admin-only'])->prefix('qao/eoms')->group(function () {
    Route::get('/reports', [QAOController::class, 'reports'])->name('qao.eoms.reports');

    Route::get('/colleges', [CollegeController::class, 'index'])->name('qao.eoms.colleges');
    Route::get('/departments', [DepartmentController::class, 'index'])->name('qao.eoms.departments');
    Route::get('/programs', [ProgramController::class, 'index'])->name('qao.eoms.programs');
    Route::get('/courses', [CourseController::class, 'index'])->name('qao.eoms.courses');
    Route::get('/faculty', [FacultyController::class, 'index'])->name('qao.eoms.faculty');

    Route::get('/search', [UploadController::class, 'search'])->name('qao.eoms.search');



    Route::post('/colleges', [CollegeController::class, 'store'])->name('qao.eoms.colleges.store');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('qao.eoms.departments.store');
    Route::post('/programs', [ProgramController::class, 'store'])->name('qao.eoms.programs.store');
    Route::post('/courses', [CourseController::class, 'store'])->name('qao.eoms.courses.store');
    Route::post('/faculty', [FacultyController::class, 'store'])->name('qao.eoms.faculty.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/faculty/{faculty}', [FacultyController::class, 'show'])->name('qao.eoms.faculty.show');
    Route::get('/faculty/{faculty}/edit', [FacultyController::class, 'edit'])->name('qao.eoms.faculty.edit');
    Route::put('/faculty/{faculty}', [FacultyController::class, 'update'])->name('qao.eoms.faculty.update');
    Route::delete('/faculty/{faculty}', [FacultyController::class, 'destroy'])->name('qao.eoms.faculty.destroy');
});







// ==========================
// Settings
// ==========================
Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::post('/update', [SettingController::class, 'update'])->name('update');
});

// ==========================
// History
// ==========================
Route::middleware('auth')->prefix('history')->name('history.')->group(function () {
    Route::get('/', [HistoryController::class, 'index'])->name('index');
});



// ==========================
// Chat & Groups
// ==========================
Route::middleware('auth')->group(function () {
    Route::resource('groups', GroupController::class);
    Route::post('groups/{group}/add-user', [GroupController::class, 'addUser'])->name('groups.add-user');
    Route::delete('groups/{group}/remove-user/{user}', [GroupController::class, 'removeUser'])->name('groups.remove-user');
    Route::get('/chat/users', [ChatController::class, 'getUsers'])->name('chat.users');
});

Route::middleware('auth')->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/history', [ChatController::class, 'fetchHistory'])->name('history');
    Route::get('/files/{filename}', [ChatController::class, 'downloadFile'])->name('chat.downloadFile');
    Route::get('/{group}', [ChatController::class, 'show'])->name('show');
    
    
});

Route::middleware('auth')->prefix('groups')->name('groups.')->group(function () {
    Route::get('/', [GroupController::class, 'index'])->name('index');
    Route::post('/store', [GroupController::class, 'store'])->name('store');
    Route::get('/member', [GroupController::class, 'member'])->name('member');
    Route::post('/store_member', [GroupController::class, 'store_member'])->name('store_member');
});

// ==========================
// Notifications
// ==========================
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/fetch', [App\Http\Controllers\NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::get('/mark-read/{id}', [NotificationController::class, 'markRead'])->name('mark-read');
    Route::get('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
});

// ==========================
// Requests & Permissions
// ==========================
Route::middleware('auth')->prefix('requests')->name('requests.')->group(function () {
    Route::get('/approval', [RequestController::class, 'approval'])->name('approval');
    Route::post('/documents/{upload_id}/distribute', [App\Http\Controllers\HomeController::class, 'performDistribution'])->name('documents.performDistribution');
Route::post('/uploads/{upload}/approve', [App\Http\Controllers\UploadController::class, 'approve'])->name('uploads.approve');
    Route::post('/uploads/{upload}/reject', [App\Http\Controllers\UploadController::class, 'reject'])->name('uploads.reject');
    Route::get('/revision', [RequestController::class, 'revision'])->name('revision');
});

Route::middleware('auth')->prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/revision', [RequestController::class, 'permissionRevision'])->name('revision');
});

// ==========================
// QAO Dashboard & Settings
// ==========================
Route::prefix('qao')->middleware('auth')->name('qao.')->group(function () {
    Route::get('/dashboard', [QAOController::class, 'dashboard'])->name('qao.dashboard');
    


    Route::get('/process-manuals', [QAOController::class, 'processManuals'])->name('process-manuals');
    Route::get('/process-manual/overview', function () {
        return view('qao.process-manual.overview');
    })->name('qao.process-manual.overview');
    Route::get('/process-manual/creation', function () {
        return view('qao.process-manual.creation');
    })->name('qao.process-manual.creation');
    Route::get('/process-manual/review', function () {
        return view('qao.process-manual.review');
    })->name('qao.process-manual.review');
    Route::get('/process-manual/approval', function () {
        return view('qao.process-manual.approval');
    })->name('qao.process-manual.approval');
    Route::get('/process-manual/revision', function () {
        return view('qao.process-manual.revision');
    })->name('qao.process-manual.revision');
    Route::get('/process-manual/archiving', function () {
        return view('qao.process-manual.archiving');
    })->name('qao.process-manual.archiving');
    Route::get('/process-manual/roles/superadmin', function () {
        return view('qao.process-manual.roles.superadmin');
    })->name('qao.process-manual.roles.superadmin');
    Route::get('/process-manual/roles/admin', function () {
        return view('qao.process-manual.roles.admin');
    })->name('qao.process-manual.roles.admin');
    Route::get('/process-manual/roles/campus-dcc', function () {
        return view('qao.process-manual.roles.campus-dcc');
    })->name('qao.process-manual.roles.campus-dcc');
    Route::get('/process-manual/roles/process-owners', function () {
        return view('qao.process-manual.roles.process-owners');
    })->name('qao.process-manual.roles.process-owners');
    Route::get('/process-manual/workflows', function () {
        return view('qao.process-manual.workflows');
    })->name('qao.process-manual.workflows');
    Route::get('/process-manual/pending', function () {
        return view('qao.process-manual.pending');
    })->name('qao.process-manual.pending');
    Route::get('/process-manual/reports', function () {
        return view('qao.process-manual.reports');
    })->name('qao.process-manual.reports');
    Route::get('/process-manual/my-processes', function () {
        return view('qao.process-manual.my-processes');
    })->name('qao.process-manual.my-processes');
    Route::get('/process-manual/submit', function () {
        return view('qao.process-manual.submit');
    })->name('qao.process-manual.submit');
    Route::get('/process-manuals/roles', function () {
        return view('process-manual.roles');
    })->name('process-manuals.roles');
    Route::get('/reference-manuals', [QAOController::class, 'referenceManuals'])->name('reference-manuals');
    Route::get('/reference-manual/overview', function () {
        return view('qao.reference-manual.overview');
    })->name('reference-manual.overview');
    // TODO: Create specific views for quality-manual, student-assessment, faculty-evaluation, and document-control
    Route::get('/reference-manual/quality-manual', function () {
        return view('qao.reference-manual.guidelines.quality-manual');
    })->name('qao.reference-manual.quality-manual');
    Route::get('/reference-manual/student-assessment', function () {
        return view('qao.reference-manual.guidelines.student-assessment');
    })->name('qao.reference-manual.student-assessment');
    Route::get('/reference-manual/faculty-evaluation', function () {
        return view('qao.reference-manual.guidelines.faculty-evaluation');
    })->name('qao.reference-manual.faculty-evaluation');
    Route::get('/reference-manual/document-control', function () {
        return view('qao.reference-manual.policies.policies');
    })->name('qao.reference-manual.document-control');
    Route::get('/reference-manual/templates', function () {
        return view('qao.reference-manual.templates.templates');
    })->name('reference-manual.templates');
    Route::get('/reference-manual/iso-standards', function () {
        return view('qao.reference-manual.iso-standards'); // Assuming you have a view named iso-standards.blade.php
    })->name('reference-manual.iso-standards');
    // TODO: Create specific views for university-regulations and search
    Route::get('/reference-manual/university-regulations', function () {
        return view('qao.reference-manual.policies.policies');
    })->name('qao.reference-manual.university-regulations');
    Route::get('/reference-manual/search', function () {
        return view('qao.reference-manual.overview');
    })->name('reference-manual.search');
    Route::get('/reference-manual/guidelines', function () {
        return view('qao.reference-manual.guidelines');
    })->name('qao.reference-manual.guidelines');
    Route::get('/reference-manual/policies', function () {
        return view('qao.reference-manual.policies.policies');
    })->name('qao.reference-manual.policies');
    
    
    
    
    Route::get('/reference-manual/templates/memo-templates', function () {
        return view('qao.reference-manual.templates.memo-templates');
    })->name('qao.reference-manual.templates.memo-templates');
    Route::get('/reference-manual/templates/letter-templates', function () {
        return view('qao.reference-manual.templates.letter-templates');
    })->name('qao.reference-manual.templates.letter-templates');
    Route::get('/reference-manual/templates/report-templates', function () {
        return view('qao.reference-manual.templates.report-templates');
    })->name('qao.reference-manual.templates.report-templates');

    
    
    

    // TODO: Create specific views for evaluation.faculty, evaluation.student, and evaluation.course
    
    
    

    // TODO: Create specific views for admin.account, admin.access, and admin.audit
    
    
    

    // TODO: Create specific views for templates, checklists.compliance, checklists.process, and search
    
    
    

    
    })->name('qao.reference-manual.templates.report-templates');
    Route::get('/reference-manual/checklists/audit-checklists', function () {
        return view('qao.reference-manual.checklists.audit-checklists');
    })->name('qao.reference-manual.checklists.audit-checklists');
    Route::get('/reference-manual/checklists/compliance-checklists', function () {
        return view('qao.reference-manual.checklists.compliance-checklists');
    })->name('qao.reference-manual.checklists.compliance-checklists');
    Route::get('/reference-manual/checklists/process-review-checklists', function () {
        return view('qao.reference-manual.checklists.process-review-checklists');
    })->name('qao.reference-manual.checklists.process-review-checklists');

    Route::get('/audit-logs', [QAOController::class, 'auditLogs'])->name('qao.audit-logs');
    Route::get('/document-management/upload', function () {
        return view('qao.document-management.upload');
    })->name('qao.document-management.upload');
    Route::get('/document-management/manage', function () {
        return view('qao.document-management.manage');
    })->name('qao.document-management.manage');
    
    Route::get('/profile/view', function () {
        return view('qao.profile.view');
    })->name('qao.profile.view');
    Route::get('/profile/edit', function () {
        return view('qao.profile.edit');
    })->name('qao.profile.edit');

    Route::middleware(['can:superadmin-admin-only'])->group(function () {
        
    });


Route::get('/history-log/user-activity', function () {
        return view('qao.history-log.user-activity');
    })->name('qao.history-log.user-activity');
    Route::get('/history-log/reports', function () {
        return view('qao.history-log.reports');
    })->name('qao.history-log.reports');

    

// ==========================
// Campus DCC Routes
// ==========================
Route::middleware(['auth', 'role:campus-dcc'])->prefix('documents')->name('documents.')->group(function () {
    Route::get('/distribute', [App\Http\Controllers\HomeController::class, 'distribute'])->name('distribute');
    Route::get('/distributed', [App\Http\Controllers\HomeController::class, 'distributed'])->name('distributed');
});

// Process Owner Routes
Route::middleware(['auth', 'role:process-owner'])->prefix('documents')->name('documents.')->group(function () {
    Route::get('/my', [App\Http\Controllers\HomeController::class, 'myDocuments'])->name('my');
    Route::get('/feedback', [App\Http\Controllers\HomeController::class, 'feedback'])->name('feedback');
});






// ==========================
// EOMS Routes
// ==========================
Route::prefix('eoms')->middleware('auth')->name('eoms.')->group(function () {
    // Index
    Route::view('/colleges', 'eoms.colleges')->name('colleges');
    Route::view('/departments', 'eoms.departments')->name('departments');
    Route::view('/programs', 'eoms.programs')->name('programs');
    Route::view('/courses', 'eoms.courses')->name('courses');
    Route::view('/faculty', 'eoms.faculty')->name('faculty');
    Route::view('/reports', 'eoms.reports')->name('reports');
    Route::view('/search', 'eoms.search')->name('search');


    // Create (Add buttons)
    Route::get('/colleges/create', [CollegeController::class, 'create'])->name('colleges.create');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::get('/programs/create', [ProgramController::class, 'create'])->name('programs.create');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::get('/faculty/create', [FacultyController::class, 'create'])->name('faculty.create');
});

// ==========================
// QAO Routes
// ==========================
Route::middleware(['auth', 'can:qao-only'])->prefix('qao')->group(function () {
    Route::get('/dashboard', [QAOController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [QAOController::class, 'users'])->name('users');
    Route::get('/settings', [QAOController::class, 'settings'])->name('settings');
    Route::get('/audit-logs', [QAOController::class, 'auditLogs'])->name('qao.audit-logs');

    // Create (Add buttons)
    
    Route::view('/settings/create', 'qao.settings-create')->name('settings.create');
    Route::view('/audit-logs/create', 'qao.audit-logs-create')->name('audit-logs.create');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('documents')->middleware('auth')->name('documents.')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'documentsIndex'])->name('index');
    Route::get('/controlled', [App\Http\Controllers\HomeController::class, 'documentsControlled'])->name('controlled');
    Route::get('/pending', [App\Http\Controllers\HomeController::class, 'documentsPending'])->name('pending');
    Route::get('/assigned', [App\Http\Controllers\HomeController::class, 'documentsAssigned'])->name('assigned');
    Route::get('/distributed-this-month', [App\Http\Controllers\HomeController::class, 'documentsDistributedThisMonth'])->name('distributed-this-month');
    Route::get('/pending-distribution', [App\Http\Controllers\HomeController::class, 'documentsPendingDistribution'])->name('pending-distribution');
    Route::get('/downloads', [App\Http\Controllers\HomeController::class, 'documentsDownloads'])->name('downloads');
});

Route::middleware('auth')->group(function () {
    Route::get('/feedback/pending', [App\Http\Controllers\HomeController::class, 'feedbackPending'])->name('feedback.pending');
    Route::get('/feedback/history', [App\Http\Controllers\HomeController::class, 'feedbackHistory'])->name('feedback.history');
});


