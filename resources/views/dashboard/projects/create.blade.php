@extends('dashboard.layouts.app')

@section('title', 'New project')
@section('heading', 'Create a project')
@section('subheading', 'Add the work, delivery details, assigned team, and public portfolio settings.')

@section('content')
    <form method="POST" action="{{ route('dashboard.projects.store') }}" enctype="multipart/form-data">
        @csrf
        @include('dashboard.projects._form', ['submitLabel' => 'Create project'])
    </form>
@endsection
