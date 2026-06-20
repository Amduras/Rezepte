@extends('layout/app')

@section('content')
    <div class="wrapper">
        <form class="w-full flex">
            <div id="section__left-recipe" class="w-full md:w-1/4">
                <p class="text-base/7 font-semibold text-white"> Allgemeine Rezept Daten</p>
                <p class="mt-1 text-sm/6 text-gray-400"> Das sind die allgemeinen Daten für das Rezept</p>
            </div>
            <div id="section__right-recipe" class="w-full md:w-3/4 md:ml-15">
                <div class="form__group sm:col-span-4 md:w-8/12">
                    <label for="title" class="block text-sm/6 font-medium text-white">Title:</label>
                    <input type="text" id="title" name="title" placeholder="Name" class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                </div>
                <div class="form__group sm:col-span-4 mt-5 md:w-8/12">
                    <label for="description" class="block text-sm/6 font-medium text-white">Beschreibung:</label>
                    <input type="text" id="description" name="description" placeholder="Beschreibung" class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                </div>
                <div class="form__group sm:col-span-4 mt-5 flex md:w-8/12 justify-between">
                    <div>
                        <label for="prep_time" class="block text-sm/6 font-medium text-white">Vorbereitungszeit:</label>
                        <input type="text" id="prep_time" name="prep_time" placeholder="Vorbereitungszeit" class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                    </div>
                    <div class="ml-8">
                        <label for="cook_time" class="block text-sm/6 font-medium text-white">Kochzeit:</label>
                        <input type="text" id="cook_time" name="cook_time" placeholder="Kochzeit" class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                    </div>
                    <div class="ml-8">
                        <label for="difficulty" class="block text-sm/6 font-medium text-white">Schwierigkeit:</label>
                        <input type="text" id="difficulty" name="difficulty" placeholder="Schwierigkeit" class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                    </div>
                </div>
                <div class="form__group sm:col-span-4 mt-5 md:w-8/12">
                    <label for="title" class="block text-sm/6 font-medium text-white">Tags:</label>
                    <input type="text" id="title" name="title" placeholder="e.g. bohnen,griechisch,brot" class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                </div>
            </div>
        </form>
    </div>
@endsection
