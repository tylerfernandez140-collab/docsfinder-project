@extends('adminlte::page')

@section('title', 'Chat')

@section('content')
<div class="container">
    <h1>Your Chat Groups</h1>

    @if($groups->isEmpty())
        <p>You are not a member of any chat groups yet.</p>
    @else
        <ul class="list-group">
            @foreach ($groups as $group)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('chat.show', $group) }}">
                        {{ $group->name ?? 'Unnamed Group' }}
                    </a>
                    <span class="badge bg-primary rounded-pill">
                        {{ $group->users->count() }} members
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
