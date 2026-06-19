@extends('layout/app')

@section('content')
    <p>{{ $user->username }}</p>
    <form>
        <label for="title">Rezept Name:</label>
        <input type="text" id="title" name="title">
    </form>
@endsection
