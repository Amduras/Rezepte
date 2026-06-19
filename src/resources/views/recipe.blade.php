@extends('layout.app')
    @section('content')
        @php
            $recipe = (object) [
                'title' => 'Rustikaler Gemüse-Wombat-Auflauf',
                'description' => 'Rustikaler Gemüse-Wombat-Auflauf mit frischen Kräutern und goldgelber Käsehülle.',
                'images' => [
                    asset('images/wom1.jpg'),
                    asset('images/wom2.jpg'),
                    asset('images/wom3.jpg'),
                ],
                'prepTime' => '40 min',
                'cookTime' => '35 min',
                'difficulty' => 'hard',
                'servings' => '5',
                'ingredients' => [
                    '500g Arsch',
                    '5 Liter Sterni',
                    '3 EL Fichstäbchen',
                    '9 Prisen Vanille Eis',
                    '5 Becher Staatsräson'
                ]
            ];
            $totalImages = count($recipe->images);
        @endphp
        <div class="w-full max-w-3xl mx-auto text-center mb-6 px-4">
            <h1 class="text-2xl md:text-3xl font-bold uppercase tracking-wider text-white">
                {{ $recipe->title }}
            </h1>
            <p class="text-zinc-400 mt-2 text-sm md:text-base font-light">
                {{ $recipe->description }}
            </p>
            </div>

            <div class="relative w-full overflow-hidden group">

                <div id="recipe-carousel" class="relative w-full h-88 md:h-136 overflow-hidden">
                    <div id="carousel-track" class="flex h-full transition-transform duration-500 ease-out">

                        {{-- Klone vom Ende (3 Stück) --}}
                        @if($totalImages >= 3)
                            @for($i = 0; $i < 3; $i++)
                                <div class="carousel-item shrink-0 w-[75%] md:w-[60%] px-2">
                                    <img src="{{ $recipe->images[$totalImages - 3 + $i] }}" class="w-full h-full object-cover rounded-xl shadow-2xl border border-zinc-900">
                                </div>
                            @endfor
                        @endif

                        {{-- Originale Bilder --}}
                        @foreach ($recipe->images as $index => $image)
                            <div class="carousel-item shrink-0 w-[75%] md:w-[60%] px-2" data-index="{{ $index }}">
                                <img src="{{ $image }}" class="w-full h-full object-cover rounded-xl shadow-2xl border border-zinc-900">
                            </div>
                        @endforeach

                        {{-- Klone vom Anfang (3 Stück) --}}
                        @if($totalImages >= 3)
                            @for($i = 0; $i < 3; $i++)
                                <div class="carousel-item shrink-0 w-[75%] md:w-[60%] px-2">
                                    <img src="{{ $recipe->images[$i] }}" class="w-full h-full object-cover rounded-xl shadow-2xl border border-zinc-900">
                                </div>
                            @endfor
                        @endif

                    </div>
                </div>

                <button id="carousel-prev" class="absolute left-2 md:left-[18vw] top-1/2 -translate-y-1/2 w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-white text-zinc-900 shadow-2xl hover:scale-110 active:scale-95 transition-all duration-200 z-20 cursor-pointer opacity-0 group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <button id="carousel-next" class="absolute right-2 md:right-[18vw] top-1/2 -translate-y-1/2 w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-white text-zinc-900 shadow-2xl hover:scale-110 active:scale-95 transition-all duration-200 z-20 cursor-pointer opacity-0 group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>

            <div id="carousel-dots" class="flex justify-center space-x-2 mt-6">
                @foreach ($recipe->images as $index => $image)
                    <span class="carousel-dot w-2 h-2 rounded-full bg-zinc-700 transition-all duration-300 cursor-pointer"></span>
                @endforeach
            </div>
            <div class="flex flex-row min-h-screen relative">
                <div id="section-left" class="w-full md:w-3/4 p-5">
                    <div class="flex justify-between p-5 flex-col md:flex-row">
                        <span class="text-center bg-emerald-500/50 p-2 rounded-xl w-50"><p class="font-bold">Vorbereitungszeit:</p> <p class="flex items-center"><img src="{{ asset('logos/clock.svg') }}" class="w-10 mr-5"> {{ $recipe->prepTime }}</p></span>
                        <span class="text-center bg-emerald-500/50 p-2 md:mt-0 mt-5 rounded-xl w-50"><p class="font-bold">Kochzeit:</p> <p class="flex items-center"><img src="{{ asset('logos/topf.svg') }}" class="w-10 mr-5"> {{ $recipe->cookTime }}</p></span>
                        <span class="text-center bg-emerald-500/50 p-2 md:mt-0 mt-5 rounded-xl w-50"><p class="font-bold">Schwierigkeit:</p> <p> <img src='{{asset('logos/'.$recipe->difficulty.'.png')}}' class='w-20 ml-auto mr-auto' ></p></span>
                    </div>
                    <div>
                        <h2 class="text-2xl text-emerald-500">Einhorn-Blindtext</h2>
                        <p>Es war einmal in einer zauberhaften Welt, in der Einhörner fröhlich durch die duftenden Wiesen galoppierten und ihre funkelnden Hörner stolz gen Himmel streckten. Diese magischen Kreaturen waren bekannt für ihr kreativ-flauschiges Leben und ihre Vorliebe für das Besondere.</p>
                        <p> Eines Tages beschlossen die Einhörner, ihre außergewöhnlichen Fähigkeiten auf eine neue Art und Weise zu feiern. Sie errichteten einen riesigen Pool, gefüllt mit schäumendem Champagner, der im Sonnenlicht funkelte wie tausend Diamanten. In diesem Champagnerbad tauchten die Einhörner mit Begeisterung ein, ihre Mähnen und Schweife glitzernd und sanft schimmernd.</p>
                        <p>Während sie im Champagner planschten, genossen die Einhörner auch ihre Lieblingsspeise: Waffeln. Große Stapel von duftenden Waffeln wurden am Poolrand aufgetürmt, und die Einhörner naschten fröhlich von den zuckersüßen Leckereien. Die Kombination aus prickelndem Champagner und knusprigen Waffeln war für die Einhörner ein wahrer Genuss, der ihre Herzen höherschlagen ließ.</p>
                        <p>Doch das kreativ-flauschige Leben der Einhörner beschränkte sich nicht nur auf Champagnerbäder und Waffeln. Sie waren auch begabte Künstler und erschufen atemberaubende Kunstwerke mit ihren goldenen Hufen. Von malerischen Regenbögen über funkelnde Sternennächte bis hin zu majestätischen Einhornporträts - ihre Werke waren von unübertroffener Schönheit und Magie.</p>
                        <p>Die Einhörner teilten ihre Kunstwerke mit der Welt und luden alle ein, an ihrer kreativen Leidenschaft teilzuhaben. In ihren malerischen Ateliers fanden Workshops statt, in denen Menschen die Geheimnisse der Einhornkunst lernen  konnten. Die Teilnehmer wurden ermutigt, ihre eigene künstlerische Seite zu entdecken und ihre Fantasie auf die Leinwand zu bringen.</p>
                        <p>Während die Einhörner ihre kreativen Fähigkeiten erkundeten, blieben sie stets in Verbindung mit der Natur. Sie pflanzten bunte Blumen und züchteten duftende Kräuter, um ihre Umgebung mit Farben und Düften zu erfüllen. Ihre Welt war ein Garten voller Schönheit und Harmonie, in dem sie sich mit den Tieren des Waldes und den Vögeln des Himmels friedlich verbanden.</p>
                        <p>Die Einhörner lebten ein flauschiges, kreatives Leben voller Freude und Zauber. Sie waren ein Symbol für Einzigartigkeit und Fantasie und erinnerten die Menschen daran, dass es wichtig ist, die kleinen Freuden des Lebens zu genießen und seine kreative Seite zu entfalten.</p>
                    </div>
                    <div>
                        <h2 class="text-2xl text-emerald-500">Einhorn-Blindtext</h2>
                        <p>Es war einmal in einer zauberhaften Welt, in der Einhörner fröhlich durch die duftenden Wiesen galoppierten und ihre funkelnden Hörner stolz gen Himmel streckten. Diese magischen Kreaturen waren bekannt für ihr kreativ-flauschiges Leben und ihre Vorliebe für das Besondere.</p>
                        <p> Eines Tages beschlossen die Einhörner, ihre außergewöhnlichen Fähigkeiten auf eine neue Art und Weise zu feiern. Sie errichteten einen riesigen Pool, gefüllt mit schäumendem Champagner, der im Sonnenlicht funkelte wie tausend Diamanten. In diesem Champagnerbad tauchten die Einhörner mit Begeisterung ein, ihre Mähnen und Schweife glitzernd und sanft schimmernd.</p>
                        <p>Während sie im Champagner planschten, genossen die Einhörner auch ihre Lieblingsspeise: Waffeln. Große Stapel von duftenden Waffeln wurden am Poolrand aufgetürmt, und die Einhörner naschten fröhlich von den zuckersüßen Leckereien. Die Kombination aus prickelndem Champagner und knusprigen Waffeln war für die Einhörner ein wahrer Genuss, der ihre Herzen höherschlagen ließ.</p>
                        <p>Doch das kreativ-flauschige Leben der Einhörner beschränkte sich nicht nur auf Champagnerbäder und Waffeln. Sie waren auch begabte Künstler und erschufen atemberaubende Kunstwerke mit ihren goldenen Hufen. Von malerischen Regenbögen über funkelnde Sternennächte bis hin zu majestätischen Einhornporträts - ihre Werke waren von unübertroffener Schönheit und Magie.</p>
                        <p>Die Einhörner teilten ihre Kunstwerke mit der Welt und luden alle ein, an ihrer kreativen Leidenschaft teilzuhaben. In ihren malerischen Ateliers fanden Workshops statt, in denen Menschen die Geheimnisse der Einhornkunst lernen konnten. Die Teilnehmer wurden ermutigt, ihre eigene künstlerische Seite zu entdecken und ihre Fantasie auf die Leinwand zu bringen.</p>
                        <p>Während die Einhörner ihre kreativen Fähigkeiten erkundeten, blieben sie stets in Verbindung mit der Natur. Sie pflanzten bunte Blumen und züchteten duftende Kräuter, um ihre Umgebung mit Farben und Düften zu erfüllen. Ihre Welt war ein Garten voller Schönheit und Harmonie, in dem sie sich mit den Tieren des Waldes und den Vögeln des Himmels friedlich verbanden.</p>
                        <p>Die Einhörner lebten ein flauschiges, kreatives Leben voller Freude und Zauber. Sie waren ein Symbol für Einzigartigkeit und Fantasie und erinnerten die Menschen daran, dass es wichtig ist, die kleinen Freuden des Lebens zu genießen und seine kreative Seite zu entfalten.</p>
                    </div>
                </div>
                <!-- SCHWIMMENDER BUTTON (Nur Mobile sichtbar) -->
                <button id="ingredients__show" class="md:hidden fixed bottom-6 right-6 z-40 bg-emerald-500 text-white p-4 rounded-full shadow-2xl flex items-center justify-center transition-transform active:scale-95">
                    <img src="{{ asset('logos/leaf.png') }}" class="w-8 h-8">
                    <span class="ml-2 font-bold">Zutaten</span>
                </button>
                <div id="section-right" class="w-full md:w-1/4 p-5 hidden md:block md:relative bg-[#121212] md:bg-transparent">
                    <div class="md:hidden flex justify-between items-center mb-6 border-b border-emerald-500/50 pb-2">
                        <h2 class="text-xl font-bold text-emerald-600">Zutaten</h2>
                        <img src="{{ asset('logos/close.png') }}" id="ingredients__close" class="w-8 h-8 cursor-pointer hidden bg-emerald-500/50 rounded-full p-1">
                    </div>
                    <div class="wrapper md:sticky md:top-30">
                        <div class="ingredients__wrapper md:block top-10">
                            <div class="servings__wrapper flex bg-emerald-500/50 rounded-xl w-full text-center justify-between">
                                <div class="mt-auto mb-auto pl-2 cursor-pointer" id="servings-left"><</div>
                                <span><p class="font-bold">Portionen:</p> <p id="servings-amount">{{ $recipe->servings }}</p></span>
                                <div class="mt-auto mb-auto pr-2 cursor-pointer" id="servings-right">></div>
                            </div>
                            <div id="recipe__list">
                            <h2 class="text-emerald-500 text-2xl">Zutatenliste</h2>
                                <ul id="ingredients-list" class="invisible md:visible">
                                    @foreach ($recipe->ingredients as $index => $ingredient)
                                        <li>{{ $ingredient }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
