<div class="navbar navbar-expand-md navbar-dark">
    <div class="mt-2 mr-5">
        <a href="{{ route('dashboard') }}" class="d-inline-block">
            <h4 class="text-bold text-white">{{ Qs::getSystemName() }}</h4>
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>

        <span class="navbar-text ml-md-3 mr-md-auto"></span>

        <ul class="navbar-nav">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                    <span>{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    @php
                        $user = Auth::user();
                    @endphp

                    @if($user)
                        @if(Qs::userIsStudent())
                            @php
                                $studentRecord = Qs::findStudentRecord($user->id);
                            @endphp
                            @if($studentRecord)
                                <a href="{{ route('students.show', Qs::hash($studentRecord->id)) }}" class="dropdown-item">
                                    <i class="icon-user-plus"></i> My Profile
                                </a>
                            @endif
                        @elseif(Qs::userIsParent())
                            <a href="{{ route('my_children') }}" class="dropdown-item">
                                <i class="icon-users"></i> My Children
                            </a>
                        @else
                            <a href="{{ route('users.show', Qs::hash($user->id)) }}" class="dropdown-item">
                                <i class="icon-user-plus"></i> My Profile
                            </a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <a href="{{ route('my_account') }}" class="dropdown-item">
                            <i class="icon-cog5"></i> Account Settings
                        </a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
                            <i class="icon-switch2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif
                </div>
            </li>
        </ul>
    </div>
</div>
