<?php
use App\Http\Controllers\SupportTeam\TeacherTimetableController;
use App\Http\Controllers\SupportTeam\EventController;
use App\Http\Controllers\SupportTeam\LearningMaterialController;
use App\Http\Controllers\SupportTeam\TimeTableController;
use App\Http\Controllers\SupportTeam\PaymentController;
use App\Http\Controllers\MyParent\MyController;
use App\Http\Controllers\SupportTeam\AttendanceController;
use App\Http\Controllers\SupportTeam\ReportCardsController;
use App\Http\Controllers\SupportTeam\MarkController;
use App\Http\Controllers\MyParent\StripeController;


Auth::routes();

             

//Route::get('/test', 'TestController@index')->name('test');
Route::get('/privacy-policy', 'HomeController@privacy_policy')->name('privacy_policy');
Route::get('/terms-of-use', 'HomeController@terms_of_use')->name('terms_of_use');


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@dashboard')->name('home');
    Route::get('/home', 'HomeController@dashboard')->name('home');
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

    Route::group(['prefix' => 'my_account'], function() {
        Route::get('/', 'MyAccountController@edit_profile')->name('my_account');
        Route::put('/', 'MyAccountController@update_profile')->name('my_account.update');
        Route::put('/change_password', 'MyAccountController@change_pass')->name('my_account.change_pass');
    });

    /*************** Support Team *****************/
    Route::group(['namespace' => 'SupportTeam',], function(){

        /*************** Students *****************/
        Route::group(['prefix' => 'students'], function(){
            Route::get('reset_pass/{st_id}', 'StudentRecordController@reset_pass')->name('st.reset_pass');
            Route::get('graduated', 'StudentRecordController@graduated')->name('students.graduated');
            Route::put('not_graduated/{id}', 'StudentRecordController@not_graduated')->name('st.not_graduated');
            Route::get('list/{class_id}', 'StudentRecordController@listByClass')->name('students.list')->middleware('teamSAT');
            Route::get('students/addon_list', 'AddonController@daycareList')->name('students.addon_list');
            Route::post('students/addon/{studentId}', 'AddonController@addAddon')->name('students.addon');
            Route::get('transit', 'StudentRecordController@listTransit')->name('students.transit');
            Route::get('daycare', 'StudentRecordController@listDaycare')->name('students.daycare');
            
            /************************ STUDENT & PARENT Shared Route ****************************/
                Route::group(['middleware' => 'student'], function() {
                    Route::get('/my_children', [App\Http\Controllers\MyParent\MyController::class, 'children'])->name('shared.my_children');
                });


            /* Promotions */
            Route::post('promote_selector', 'PromotionController@selector')->name('students.promote_selector');
            Route::get('promotion/manage', 'PromotionController@manage')->name('students.promotion_manage');
            Route::delete('promotion/reset/{pid}', 'PromotionController@reset')->name('students.promotion_reset');
            Route::delete('promotion/reset_all', 'PromotionController@reset_all')->name('students.promotion_reset_all');
            Route::get('promotion/{fc?}/{fs?}/{tc?}/{ts?}', 'PromotionController@promotion')->name('students.promotion');
            Route::post('promote/{fc}/{fs}/{tc}/{ts}', 'PromotionController@promote')->name('students.promote');

        });

        /*************** Users *****************/
        Route::group(['prefix' => 'users'], function(){
            Route::get('reset_pass/{id}', 'UserController@reset_pass')->name('users.reset_pass');
        });

        /*************** TimeTables *****************/
        Route::group(['prefix' => 'timetables'], function(){
            Route::get('/', 'TimeTableController@index')->name('tt.index');

            Route::group(['middleware' => 'teamSA'], function() {
                Route::post('/', 'TimeTableController@store')->name('tt.store');
                Route::put('/{tt}', 'TimeTableController@update')->name('tt.update');
                Route::delete('/{tt}', 'TimeTableController@delete')->name('tt.delete');
            });
            
            
            /*************** TimeTable Records *****************/
            Route::group(['prefix' => 'records'], function(){

                Route::group(['middleware' => 'teamSA'], function(){
                    Route::get('manage/{ttr}', 'TimeTableController@manage')->name('ttr.manage');
                    Route::post('/', 'TimeTableController@store_record')->name('ttr.store');
                    Route::get('edit/{ttr}', 'TimeTableController@edit_record')->name('ttr.edit');
                    Route::put('/{ttr}', 'TimeTableController@update_record')->name('ttr.update');
                });

                Route::get('show/{ttr}', 'TimeTableController@show_record')->name('ttr.show');
                Route::get('print/{ttr}', 'TimeTableController@print_record')->name('ttr.print');
                Route::delete('/{ttr}', 'TimeTableController@delete_record')->name('ttr.destroy');

            });

            /*************** Time Slots *****************/
            Route::group(['prefix' => 'time_slots', 'middleware' => 'teamSA'], function(){
                Route::post('/', 'TimeTableController@store_time_slot')->name('ts.store');
                Route::post('/use/{ttr}', 'TimeTableController@use_time_slot')->name('ts.use');
                Route::get('edit/{ts}', 'TimeTableController@edit_time_slot')->name('ts.edit');
                Route::delete('/{ts}', 'TimeTableController@delete_time_slot')->name('ts.destroy');
                Route::put('/{ts}', 'TimeTableController@update_time_slot')->name('ts.update');
            });
                 
      

            /*************** Teacher Timetable *****************/
          Route::resource('teacher_timetables', 'TeacherTimetableController'::class)->middleware(['auth', 'teamSA']);
          Route::get('timetables/download-pdf', [TeacherTimetableController::class, 'adminDownloadPdf'])
            ->name('teacher_timetables.admin_download_pdf')
            ->middleware(['auth','teamSA']); // only admin

          /*************** Teacher Timetable ONLY VIEW *****************/
           Route::get('timetables-teacher/view', [TeacherTimetableController::class, 'teacherView'])->name('teacher.timetables.view')
            ->middleware(['auth']); // No admin middleware
            // Teacher timetable PDF download
            Route::get('timetables-teacher/download-pdf', [TeacherTimetableController::class, 'teacherDownloadPdf'])
            ->name('teacher.timetables.download_pdf')
            ->middleware('auth');


        });

        /*************** Learning Material *****************/
        Route::middleware(['auth', 'teacher'])->prefix('teacher')->group(function () {
            Route::resource('materials', 'LearningMaterialController')
                ->names([
                    'index'   => 'teacher.materials.index',
                    'create'  => 'teacher.materials.create',
                    'store'   => 'teacher.materials.store',
                    'destroy' => 'teacher.materials.destroy',
                ]);

            Route::get('materials/{material}/download', 'LearningMaterialController@download')
                ->name('teacher.materials.download');
           
        });

      

        





       /*************** Events (Shared View: Admin + Teacher) *****************/
        Route::group(['prefix' => 'events', 'middleware' => ['auth']], function() {
            Route::get('/index', 'EventController@index')->name('events.index');  
            Route::get('/json', [EventController::class, 'getEvents'])->name('events.json'); // kena letak sebelum {event}
            /****Route::get('/{event}', 'EventController@show')->name('events.show');**/
        });

        /*************** Events (Admin/Staff only - Manage) *****************/
        Route::group(['prefix' => 'events', 'middleware' => ['auth', 'teamSA']], function() {
            Route::get('/create', 'EventController@create')->name('events.create');
            Route::post('/', 'EventController@store')->name('events.store');
            Route::get('/{event}/edit', 'EventController@edit')->name('events.edit');
            Route::put('/{event}', 'EventController@update')->name('events.update');
            Route::delete('/{event}', 'EventController@destroy')->name('events.destroy');
        });

      /*************** Attendance *****************/
      
        Route::group(['prefix' => 'attendance', 'middleware' => ['auth']], function () {
            Route::get('/{section}', [AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('/{section}', [AttendanceController::class, 'store'])->name('attendance.store');
            Route::get('/', [AttendanceController::class, 'showSectionAttendance'])->name('attendance.show');
            Route::get('/attendance/export-monthly', [AttendanceController::class, 'exportMonthlyAttendance'])
            ->name('attendance.export.monthly');
        });




        
        /*************** Payments *****************/
        Route::group(['prefix' => 'payments'], function() {
            Route::get('index', [PaymentController::class, 'index'])->name('payments.index');
            Route::get('manage/{class_id?}', [PaymentController::class, 'manage'])->name('payments.manage');
            Route::get('invoice/{id}/{year?}', [PaymentController::class, 'invoice'])->name('payments.invoice');
            Route::get('receipts/{id}', [PaymentController::class, 'receipts'])->name('payments.receipts');
            Route::get('pdf_receipts/{id}', [PaymentController::class, 'pdf_receipts'])->name('payments.pdf_receipts');
            Route::post('select_year', [PaymentController::class, 'select_year'])->name('payments.select_year');
            Route::post('select_class', [PaymentController::class, 'select_class'])->name('payments.select_class');
            Route::delete('reset_record/{id}', [PaymentController::class, 'reset_record'])->name('payments.reset_record');
            Route::post('pay_now/{id}', [PaymentController::class, 'pay_now'])->name('payments.pay_now');
        });

        /*************** Pins *****************/
        Route::group(['prefix' => 'pins'], function(){
            Route::get('create', 'PinController@create')->name('pins.create');
            Route::get('/', 'PinController@index')->name('pins.index');
            Route::post('/', 'PinController@store')->name('pins.store');
            Route::get('enter/{id}', 'PinController@enter_pin')->name('pins.enter');
            Route::post('verify/{id}', 'PinController@verify')->name('pins.verify');
            Route::delete('/', 'PinController@destroy')->name('pins.destroy');
        });


        /*************** Marks *****************/
        Route::group(['prefix' => 'marks'], function(){

           // FOR teamSA
            Route::group(['middleware' => 'teamSA'], function(){
                Route::get('batch_fix', 'MarkController@batch_fix')->name('marks.batch_fix');
                Route::put('batch_update', 'MarkController@batch_update')->name('marks.batch_update');
                Route::get('tabulation/{exam?}/{class?}/{sec_id?}', 'MarkController@tabulation')->name('marks.tabulation');
                Route::post('tabulation', 'MarkController@tabulation_select')->name('marks.tabulation_select');
                Route::get('tabulation/print/{exam}/{class}/{sec_id}', 'MarkController@print_tabulation')->name('marks.print_tabulation');
            });

            // FOR teamSAT
            Route::group(['middleware' => 'teamSAT'], function(){
                Route::get('/', 'MarkController@index')->name('marks.index');
                Route::get('manage/{exam}/{class}/{section}/{subject}', 'MarkController@manage')->name('marks.manage');
                Route::put('update/{exam}/{class}/{section}/{subject}', 'MarkController@update')->name('marks.update');
                Route::put('comment_update/{exr_id}', 'MarkController@comment_update')->name('marks.comment_update');
                Route::put('skills_update/{skill}/{exr_id}', 'MarkController@skills_update')->name('marks.skills_update');
               // Hafazan
                Route::post('/marks/hafazan/submit/{exr_id}', [MarkController::class, 'hafazanSubmit'])->name('hafazan.submit');

                // Amali Solat
                Route::post('selector', 'MarkController@selector')->name('marks.selector');
                Route::get('bulk/{class?}/{section?}', 'MarkController@bulk')->name('marks.bulk');
                Route::post('bulk', 'MarkController@bulk_select')->name('marks.bulk_select');
            });

           


            Route::get('select_year/{id}', 'MarkController@year_selector')->name('marks.year_selector');
            Route::post('select_year/{id}', 'MarkController@year_selected')->name('marks.year_select');
            Route::get('show/{id}/{year}', 'MarkController@show')->name('marks.show');
            Route::get('print/{id}/{exam_id}/{year}', 'MarkController@print_view')->name('marks.print');

           
        });
      




        Route::resource('students', 'StudentRecordController');
        Route::resource('users', 'UserController');
        Route::resource('classes', 'MyClassController');
        Route::resource('sections', 'SectionController');
        Route::resource('subjects', 'SubjectController');
        Route::resource('grades', 'GradeController');
        Route::resource('exams', 'ExamController');
        Route::resource('dorms', 'DormController');
        Route::resource('payments', 'PaymentController');

    });


    /************************ AJAX ****************************/
    Route::group(['prefix' => 'ajax'], function() {
        Route::get('get_lga/{state_id}', 'AjaxController@get_lga')->name('get_lga');
        Route::get('get_class_sections/{class_id}', 'AjaxController@get_class_sections')->name('get_class_sections');
        Route::get('get_class_subjects/{class_id}', 'AjaxController@get_class_subjects')->name('get_class_subjects');
        Route::get('get_teacher_subjects', 'AjaxController@getTeacherSubjects')->name('ajax.teacher_subjects');
        Route::get('get_teacher_subjects/{class_id?}', 'AjaxController@getTeacherSubjects')->name('ajax.teacher_subjects');
        Route::get('get_teacher_timetable/{class_id?}/{subject_id?}', 'AjaxController@getTeacherTimetable')->name('ajax.teacher_timetable');

    });

});

/************************ SUPER ADMIN ****************************/
Route::group(['namespace' => 'SuperAdmin','middleware' => 'super_admin', 'prefix' => 'super_admin'], function(){

    Route::get('/settings', 'SettingController@index')->name('settings');
    Route::put('/settings', 'SettingController@update')->name('settings.update');

});

/************************ PARENT ****************************/
Route::group(['namespace' => 'MyParent','middleware' => 'my_parent',], function(){

    Route::get('/my_children', 'MyController@children')->name('my_children');

    Route::middleware(['auth'])->group(function() {
        Route::get('/teachers', [MyController::class, 'viewTeachers'])
            ->name('parent.teachers.index');
        Route::get('/teachers/{id}', [MyController::class, 'teacherProfile'])->name('parent.teachers.profile');
    });


    Route::middleware(['auth'])->group(function () {
        Route::get('/parent/timetable', [MyController::class, 'timetable'])
        ->name('parent.timetable.index');
         Route::get('/parent/timetable/pdf', [MyController::class, 'downloadPDF'])
        ->name('parent.timetable.pdf');
    });

    Route::prefix('parent')->middleware(['auth'])->group(function() {

        // List all learning materials
        Route::get('/materials', [MyController::class, 'materials'])
            ->name('parent.materials.index');

        // Download a specific material by ID
        Route::get('/materials/download/{id}', [MyController::class, 'download'])
            ->name('materials.parent.download');

    });

    

    Route::get('/events', 'MyController@events')->name('parent.events.index');
    
    // Parent Exam Routes
    Route::prefix('exam')->group(function() {

        // List all exams
        Route::get('/', 'MyController@exam')->name('parent.exam.index');

        // Show marksheet for specific student
        Route::get('/student/{student_id}', 'MyController@showMarksheet')->name('parent.exam.show');

        // Show marksheet (generic)
        Route::get('/marksheet', [App\Http\Controllers\MyParent\MyController::class, 'marksheet'])
            ->name('marksheet');

        // Wildcard exam route (optional, last in group)
        Route::get('/view/{exam}', [App\Http\Controllers\MyParent\MyController::class, 'showMarksheet'])
            ->name('exam.show');

    });

            
   

    Route::prefix('parent')->middleware('auth')->group(function() {

        // Create multi-child payment & redirect to Stripe
        Route::post('/payments/checkout', [StripeController::class, 'createPayment'])
            ->name('parent.payments.checkout.process'); // POST form here

        // Stripe success callback
        Route::get('/payments/success', [StripeController::class, 'success'])
            ->name('parent.payments.stripe.success');

        // Stripe cancel callback
        Route::get('/payments/cancel', [StripeController::class, 'cancel'])
            ->name('parent.payments.cancel');

        // Show all payments
        Route::get('/payments', [App\Http\Controllers\MyParent\MyController::class, 'showPayments'])
            ->name('parent.payments.index');

        // Receipt
        Route::get('/payments/{payment}/receipt', [App\Http\Controllers\MyParent\MyController::class, 'printReceipt'])
            ->name('parent.payments.receipt');
    });




});


/************************ STUDENT & PARENT ****************************/
Route::group(['namespace' => 'Student', 'middleware' => 'student'], function(){

    Route::get('/student/dashboard', 'StudentDashboardController@index')->name('student.dashboard');

    Route::get('/student/profile', 'StudentDashboardController@profile')->name('student.profile');

    Route::get('/student/marks', 'StudentDashboardController@marks')->name('student.marks');

    Route::get('/student/payments', 'StudentDashboardController@payments')->name('student.payments');

});

