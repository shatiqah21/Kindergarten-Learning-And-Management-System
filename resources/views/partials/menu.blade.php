<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ route('my_account') }}"></a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-user font-size-sm"></i> &nbsp;{{ ucwords(str_replace('_', ' ', Auth::user()->user_type)) }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('my_account') }}" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ (Route::is('dashboard')) ? 'active' : '' }}">
                        <i class="icon-home4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                

                {{-- Academics --}}
                @if(Qs::userIsAcademic())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                        'tt.index','tt.create','tt.edit',
                        'teacher_timetables.index','teacher_timetables.create','teacher_timetables.edit',
                        'teacher.materials.index','teacher.materials.create','teacher.materials.edit'
                    ]) ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span>Academics</span></a>

                        <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">

                            {{-- Timetable --}}
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                                'tt.index','tt.create','tt.edit',
                                'teacher_timetables.index','teacher_timetables.create','teacher_timetables.edit',
                                'teacher.timetables.view'
                            ]) ? 'nav-item-expanded nav-item-open' : '' }}">
                                <a href="#" class="nav-link">Timetable</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        @if(Route::has('tt.index'))
                                            <a href="{{ route('tt.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['tt.index','tt.create','tt.edit']) ? 'active' : '' }}">Class</a>
                                        @endif
                                    </li>
                                    <li class="nav-item">
                                        @if(auth()->user()->user_type == 'teacher' && Route::has('teacher.timetables.view'))
                                            <a href="{{ route('teacher.timetables.view') }}" class="nav-link {{ Route::currentRouteName() == 'teacher.timetables.view' ? 'active' : '' }}">Teacher</a>
                                        @elseif(Route::has('teacher_timetables.index'))
                                            <a href="{{ route('teacher_timetables.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['teacher_timetables.index','teacher_timetables.create','teacher_timetables.edit']) ? 'active' : '' }}">Teacher</a>
                                        @endif
                                    </li>
                                </ul>
                            </li>

                            {{-- Teacher Learning Materials (Standalone Menu) --}}
                            @if(Qs::userIsTeacher())
                                @php
                                    // Get classes where the teacher has at least one subject assigned
                                    $teacherClasses = \App\Models\MyClass::whereHas('subjects', function($q){
                                        $q->where('teacher_id', Auth::id());
                                    })->get();
                                @endphp

                                @if($teacherClasses->count())
                                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                                        'teacher.materials.index','teacher.materials.create','teacher.materials.edit'
                                    ]) ? 'nav-item-expanded nav-item-open' : '' }}">
                                        <a href="#" class="nav-link"><i class="icon-book"></i> <span>Learning Materials</span></a>
                                        <ul class="nav nav-group-sub" data-submenu-title="Manage Materials">
                                            @foreach($teacherClasses as $class)
                                                <li class="nav-item">
                                                    <a href="{{ route('teacher.materials.index', ['class_id' => $class->id]) }}"
                                                    class="nav-link {{ request('class_id') == $class->id ? 'active' : '' }}">
                                                        {{ $class->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endif

                        </ul>
                    </li>
                @endif


                {{-- Administrative --}}
                @if(Qs::userIsAdministrative())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                        'payments.index','payments.create','payments.edit','payments.manage','payments.show'
                    ]) ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link"><i class="icon-office"></i> <span>Administrative</span></a>
                        <ul class="nav nav-group-sub" data-submenu-title="Administrative">
                            @if(Qs::userIsTeamAccount() && Route::has('payments.index'))
                                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index','payments.create','payments.edit','payments.manage','payments.show']) ? 'nav-item-expanded' : '' }}">
                                    <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index','payments.create','payments.edit','payments.manage','payments.show']) ? 'active' : '' }}">Payments</a>
                                    <ul class="nav nav-group-sub">
                                        <li class="nav-item"><a href="{{ route('payments.create') }}" class="nav-link {{ Route::is('payments.create') ? 'active' : '' }}">Create Payment</a></li>
                                        <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index','payments.edit','payments.show']) ? 'active' : '' }}">Manage Payments</a></li>
                                        <li class="nav-item"><a href="{{ route('payments.manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.manage','payments.invoice','payments.receipts']) ? 'active' : '' }}">Student Payments</a></li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                



               {{-- Students / Exams Menu --}}
                @if(Qs::userIsTeamSAT())
                    {{-- Students Menu --}}
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                        'students.create','students.list','students.edit','students.show','students.promotion','students.promotion_manage','students.graduated'
                    ]) ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link"><i class="icon-users"></i> <span>Students</span></a>
                        <ul class="nav nav-group-sub" data-submenu-title="Manage Students">
                            @if(Qs::userIsTeamSA() && Route::has('students.create'))
                                <li class="nav-item"><a href="{{ route('students.create') }}" class="nav-link {{ Route::is('students.create') ? 'active' : '' }}">Admit Student</a></li>
                            @endif

                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.list','students.edit','students.show']) ? 'nav-item-expanded' : '' }}">
                                <a href="#" class="nav-link">Student Information</a>
                                <ul class="nav nav-group-sub">
                                    @foreach(App\Models\MyClass::orderBy('name')->get() as $c)
                                        <li class="nav-item"><a href="{{ route('students.list', $c->id) }}" class="nav-link">{{ $c->name }}</a></li>
                                    @endforeach
                                </ul>
                            </li>

                            @if(Qs::userIsTeamSA())
                                <li class="nav-item nav-item-submenu">
                                    <a href="#" class="nav-link">Student Promotion</a>
                                    <ul class="nav nav-group-sub">
                                        <li class="nav-item"><a href="{{ route('students.promotion') }}" class="nav-link">Promote Students</a></li>
                                        <li class="nav-item"><a href="{{ route('students.promotion_manage') }}" class="nav-link">Manage Promotions</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item"><a href="{{ route('students.graduated') }}" class="nav-link">Students Graduated</a></li>
                            @endif
                        </ul>
                    </li>

                    {{-- Exams Menu --}}
                    @if(Qs::userIsTeamSA())
                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                            'exams.index','grades.index','marks.tabulation','marks.batch_fix'
                        ]) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a href="#" class="nav-link"><i class="icon-books"></i> <span>Exams</span></a>
                            <ul class="nav nav-group-sub" data-submenu-title="Manage Exams">
                                <li class="nav-item"><a href="{{ route('exams.index') }}" class="nav-link">Exam List</a></li>
                                <li class="nav-item"><a href="{{ route('grades.index') }}" class="nav-link">Grades</a></li>
                                <li class="nav-item"><a href="{{ route('marks.tabulation') }}" class="nav-link">Tabulation Sheet</a></li>
                                <li class="nav-item"><a href="{{ route('marks.batch_fix') }}" class="nav-link">Batch Fix</a></li>
                            </ul>
                        </li>
                    @endif
                @endif

               {{-- Teacher Menu --}}
                @if(Qs::userIsTeacher())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                        'marks.index','marks.bulk' // ,'comments.index','achievements.index' commented out for now
                    ]) ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link"><i class="icon-medal2"></i> <span>E-Report Card</span></a>
                        <ul class="nav nav-group-sub" data-submenu-title="Manage Exams">
                            <li class="nav-item"><a href="{{ route('marks.index') }}" class="nav-link">Marks</a></li>
                            <li class="nav-item"><a href="{{ route('marks.bulk') }}" class="nav-link">Marksheet</a></li>
                            {{-- <li class="nav-item"><a href="{{ route('comments.index') }}" class="nav-link">Comment</a></li> --}}
                            {{-- <li class="nav-item"><a href="{{ route('achievements.index') }}" class="nav-link">Achievement</a></li> --}}
                        </ul>
                    </li>
                @endif

                {{-- Manage Event --}}
                @if(Qs::userIsTeamSAT())
                    <li class="nav-item">
                        <a href="{{ route('events.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['events.index','events.edit']) ? 'active' : '' }}">
                            <i class="icon-calendar"></i> <span>Events</span>
                        </a>
                    </li>
                @elseif(auth()->user()->user_type == 'teacher')
                    <li class="nav-item">
                        <a href="{{ route('events.index') }}" class="nav-link {{ Route::currentRouteName() == 'teacher.events.index' ? 'active' : '' }}">
                            <i class="icon-calendar"></i> <span>Events</span>
                        </a>
                    </li>
                @endif

                @if(Qs::userIsTeamSA())
                    {{--Manage Users--}}
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'active' : '' }}"><i class="icon-users4"></i> <span> Users</span></a>
                    </li>

                    {{--Manage Classes--}}
                    <li class="nav-item">
                        <a href="{{ route('classes.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['classes.index','classes.edit']) ? 'active' : '' }}"><i class="icon-windows2"></i> <span> Classes</span></a>
                    </li>

                    {{--Manage Dorms--}}
                    <li class="nav-item">
                        <a href="{{ route('dorms.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['dorms.index','dorms.edit']) ? 'active' : '' }}"><i class="icon-home9"></i> <span> Dormitories</span></a>
                    </li>

                    {{--Manage Sections--}}
                    <li class="nav-item">
                        <a href="{{ route('sections.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['sections.index','sections.edit',]) ? 'active' : '' }}"><i class="icon-fence"></i> <span>Sections</span></a>
                    </li>

                    {{--Manage Subjects--}}
                    <li class="nav-item">
                        <a href="{{ route('subjects.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['subjects.index','subjects.edit',]) ? 'active' : '' }}"><i class="icon-pin"></i> <span>Subjects</span></a>
                    </li>
                @endif

                {{-- Parent Menu --}}
                @if(Qs::userIsParent())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), [
                        'parent.timetable.index','parent.materials.index','parent.events.index','parent.payments.index'
                    ]) ? 'nav-item-expanded nav-item-open' : '' }}">
                        
                        <li class="nav-item">
                            <a href="{{ route('my_children') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_children',]) ? 'active' : '' }}"><i class="icon-users"></i> <span>My Children</span></a>
                        </li>

                         <li class="nav-item">
                            <a href="{{ route('parent.teachers.index') }}" 
                            class="nav-link {{ Route::currentRouteName() == 'parent.teachers.index' ? 'active' : '' }}">
                                <i class="icon-user-tie"></i> 
                                <span>Teachers</span>
                            </a>
                        </li>



                            <li class="nav-item">
                                <a href="{{ route('parent.timetable.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['parent.timetable.index',]) ? 'active' : '' }}"><i class="icon-calendar"></i> <span>Timetable</span></a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('parent.materials.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['parent.materials.index']) ? 'active' : '' }}">
                                    <i class="icon-book"></i> <span>Learning Materials</span>
                                </a>
                            </li>
                            
                            @if(Route::has('parent.events.index'))
                                <li class="nav-item">
                                    <a href="{{ route('parent.events.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['parent.events.index']) ? 'active' : '' }}">
                                        <i class="icon-ticket"></i> <span>School Events</span>
                                    </a>
                                </li>
                            @endif

                            @if(Route::has('parent.payments.index'))
                                <li class="nav-item">
                                    <a href="{{ route('parent.payments.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['parent.payments.index']) ? 'active' : '' }}">
                                        <i class="icon-cash"></i> <span>Payments</span>
                                    </a>
                                </li>
                            @endif 
                            
                            @if(Route::has('parent.exam.index'))
                                <li class="nav-item">
                                    <a href="{{ route('parent.exam.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['parent.exam.index']) ? 'active' : '' }}">
                                        <i class="icon-medal2"></i> <span>E- Report Card</span>
                                    </a>
                                </li>
                            @endif    

                          
                           
                       
                    </li>
                @endif

                {{-- My Account --}}
                <li class="nav-item">
                    <a href="{{ route('my_account') }}" class="nav-link {{ Route::is('my_account') ? 'active' : '' }}"><i class="icon-user"></i> <span>My Account</span></a>
                </li>

            </ul>
        </div>
    </div>
</div>
