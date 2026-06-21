@extends('layouts.app')

@section('title', 'Neues Rezept erstellen')

@section('content')
    <div class="wrapper">
        <h1 class="text-2xl font-bold mb-5 ml-5">Neues Rezept erstellen</h1>

        <form class="m-5 bg-stone-800 rounded-2xl pt-5"
              action="{{ route('recipes.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @include('recipes.partials.form')

        </form>
    </div>
@endsection
