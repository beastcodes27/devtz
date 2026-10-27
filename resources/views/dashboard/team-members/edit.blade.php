@extends('dashboard.layouts.app')

@section('title', 'Edit '.$teamMember->name)
@section('heading', 'Edit team member')
@section('subheading', 'Keep '.$teamMember->name.'’s role, profile, and portfolio visibility up to date.')

@section('content')
    <form method="POST" action="{{ route('dashboard.team-members.update', $teamMember) }}">
        @csrf
        @method('PUT')
        @include('dashboard.team-members._form', ['submitLabel' => 'Save changes'])
    </form>
@endsection
