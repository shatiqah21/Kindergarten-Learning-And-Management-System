@extends('layouts.master')
@section('page_title', 'Manage Events')

@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">School Events</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            {{-- Hanya Admin/Staff boleh tambah event --}}
                @if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff' || auth()->user()->user_type == 'super_admin')
                    <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">
                        <i class="icon-plus-circle2"></i> Add Event
                    </a>
                @endif


            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                          @if(in_array(auth()->user()->user_type, ['admin','staff','super_admin']))
                            <th class="text-center">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                       @foreach($events as $event)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $event->title }}</td>
                                <td>{{ $event->description }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y H:i') }}</td>

                                @if(in_array(auth()->user()->user_type, ['admin','staff','super_admin']))
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <a href="{{ route('events.edit', $event->id) }}" class="dropdown-item">
                                                        <i class="icon-pencil"></i> Edit
                                                    </a>
                                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure want to delete this event?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="icon-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        @if($events->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No events found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
