{{-- resources/views/blog/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Blog - BETBR')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Banner principal e posts destacados -->
    <div class="mb-12">
        @if($featured->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach($featured as $key => $post)
                    @if($key === 0)
                        <!-- Post destacado principal -->
                        <div class="lg:col-span-2 relative rounded-lg overflow-hidden group h-96">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                <img class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300" src="{{ $post->image_url }}" alt="{{ $post->title }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-transparent">
                                    <div class="absolute bottom-0 p-6 w-full">
                                        <span class="inline-block bg-green-600 text-white text-xs uppercase font-semibold rounded px-2 py-1 mb-2">{{ $post->category->name }}</span>
                                        <h2 class="text-2xl font-bold text-white mb-2">{{ $post->title }}</h2>
                                        <div class="flex items-center text-gray-300 text-sm">
                                            <span>{{ $post->formatted_date }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $post->read_time }} min de leitura</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @else
                        <!-- Posts destacados secundários -->
                        <div class="relative rounded-lg overflow-hidden group h-96">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                <img class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300" src="{{ $post->image_url }}" alt="{{ $post->title }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-transparent">
                                    <div class="absolute bottom-0 p-6 w-full">
                                        <span class="inline-block bg-green-600 text-white text-xs uppercase font-semibold rounded px-2 py-1 mb-2">{{ $post->category->name }}</span>
                                        <h2 class="text-xl font-bold text-white mb-2">{{ $post->title }}</h2>
                                        <div class="flex items-center text-gray-300 text-sm">
                                            <span>{{ $post->formatted_date }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $post->read_time }} min de leitura</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <!-- Conteúdo principal -->
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Lista de posts -->
        <div class="w-full lg:w-3/4">
            <h1 class="text-3xl font-bold mb-8 pb-4 border-b border-gray-700">Últimos Artigos</h1>
            
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform duration-300 hover:transform hover:scale-[1.02]">
                            <a href="{{ route('blog.show', $post->slug) }}" class="block relative">
                                <img class="w-full h-48 object-cover" src="{{ $post->image_url }}" alt="{{ $post->title }}">
                                <span class="absolute top-4 left-4 bg-green-600 text-white text-xs uppercase font-semibold rounded px-2 py-1">{{ $post->category->name }}</span>
                            </a>
                            <div class="p-5">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <h2 class="text-xl font-bold mb-3 text-white hover:text-green-500 transition-colors">{{ $post->title }}</h2>
                                </a>
                                <p class="text-gray-400 mb-4 text-sm">{{ $post->excerpt }}</p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center mr-3 text-white uppercase">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </div>
                                        <span>{{ $post->user->name }}</span>
                                    </div>
                                    <div>{{ $post->formatted_date }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="bg-gray-800 rounded p-6 text-center">
                    <p class="text-lg text-gray-400">Nenhum post encontrado. Volte em breve para novidades!</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-1/4">
            <!-- Categorias -->
            <div class="bg-gray-800 rounded-lg p-6 mb-8">
                <h3 class="text-xl font-bold mb-4 pb-2 border-b border-gray-700">Categorias</h3>
                <ul>
                    @foreach($categories as $category)
                        <li class="mb-2">
                            <a href="{{ route('blog.category', $category->slug) }}" class="flex items-center justify-between text-gray-300 hover:text-green-500 transition-colors">
                                <span>{{ $category->name }}</span>
                                <span class="bg-gray-700 text-white text-xs rounded-full px-2 py-1">{{ $category->posts_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Banner promocional -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-6 mb-8 text-white">
                <h3 class="text-xl font-bold mb-3">Bônus de Boas-Vindas</h3>
                <p class="mb-4">Registre-se hoje e receba até 100% de bônus no seu primeiro depósito!</p>
                <a href="{{ route('register') }}" class="block w-full bg-yellow-500 hover:bg-yellow-400 text-black font-bold py-2 px-4 rounded text-center transition-colors">
                    Registre-se Agora
                </a>
            </div>

            <!-- Tags populares -->
            <div class="bg-gray-800 rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4 pb-2 border-b border-gray-700">Tags Populares</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach(\App\Models\Tag::withCount('posts')->orderBy('posts_count', 'desc')->take(10)->get() as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}" class="bg-gray-700 hover:bg-green-600 text-gray-300 hover:text-white transition-colors text-sm rounded-full px-3 py-1">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection