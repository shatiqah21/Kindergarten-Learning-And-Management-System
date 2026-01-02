{{--My Children--}}
<li class="nav-item">
    <a href="{{ route('my_children') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_children']) ? 'active' : '' }}"><i class="icon-users4"></i> My Children</a>
</li>

{{-- School Timetable --}}
<li class="nav-item">
    <a href="{{ route('parent.timetable.index') }}" 
       class="nav-link {{ in_array(Route::currentRouteName(), ['parent.timetable.index']) ? 'active' : '' }}">
        <i class="icon-calendar3"></i> School Timetable
    </a>
</li>

{{-- Learning Materials --}}
<li class="nav-item">
    <a href="{{ route('parent.materials.index') }}" 
       class="nav-link {{ in_array(Route::currentRouteName(), ['parent.materials.index']) ? 'active' : '' }}">
        <i class="icon-books"></i> Learning Materials
    </a>
</li>

{{-- Events --}}
<li class="nav-item">
    <a href="{{ route('parent.events.index') }}" 
       class="nav-link {{ in_array(Route::currentRouteName(), ['parent.events.index']) ? 'active' : '' }}">
        <i class="icon-calendar52"></i> Events
    </a>
</li>

{{-- Payments --}}
<li class="nav-item">
    <a href="{{ route('parent.payments.index') }}" 
       class="nav-link {{ in_array(Route::currentRouteName(), ['parent.payments.index']) ? 'active' : '' }}">
        <i class="icon-cash3"></i> Payments
    </a>
</li>

{{-- Exams --}}
<li class="nav-item">
    <a href="{{ route('parent.exam.index') }}" 
       class="nav-link {{ in_array(Route::currentRouteName(), ['parent.exams.index']) ? 'active' : '' }}">
        <i class="icon-file-text2"></i> Exams
    </a>
</li>
