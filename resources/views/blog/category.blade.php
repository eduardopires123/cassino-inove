{{-- resources/views/blog/category.blade.php --}}
@extends('layouts.app')

@section('title', $category->name . ' - Blog BETBR')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Lista de posts -->
        <div class="w-full lg:w-3/4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold mb-2">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-gray-400">{{ $category->description }}</p>
                @endif
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <p class="text-gray-300">{{ $posts->total() }} artigos encontrados</p>
                </div>
            </div>
            
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
                    <p class="text-lg text-gray-400">Nenhum post encontrado nesta categoria. Volte em breve para novidades!</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-1/4">
            <!-- Categorias -->
            <div class="bg-gray-800 rounded-lg p-6 mb-8">
                <h3 class="text-xl font-bold mb-4 pb-2 border-b border-gray-700">Categorias</h3>
                <ul>
                    @foreach(\App\Models\Category::withCount('posts')->get() as $cat)
                        <li class="mb-2">
                            <a href="{{ route('blog.category', $cat->slug) }}" class="flex items-center justify-between text-gray-300 hover:text-green-500 transition-colors {{ $cat->id === $category->id ? 'text-green-500 font-medium' : '' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="bg-gray-700 text-white text-xs rounded-full px-2 py-1">{{ $cat->posts_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Posts recentes -->
            <div class="bg-gray-800 rounded-lg p-6 mb-8">
                <h3 class="text-xl font-bold mb-4 pb-2 border-b border-gray-700">Posts Recentes</h3>
                <div class="space-y-4">
                    @foreach(\App\Models\Post::with('category')->where('status', 'published')->latest()->take(5)->get() as $recentPost)
                        <div class="flex items-center">
                            <a href="{{ route('blog.show', $recentPost->slug) }}" class="block flex-shrink-0 w-20 h-20 mr-4">
                                <img class="w-full h-full object-cover rounded" src="{{ $recentPost->image_url }}" alt="{{ $recentPost->title }}">
                            </a>
                            <div>
                                <a href="{{ route('blog.show', $recentPost->slug) }}" class="font-medium text-white hover:text-green-500 transition-colors">
                                    {{ Str::limit($recentPost->title, 50) }}
                                </a>
                                <p class="text-xs text-gray-400 mt-1">{{ $recentPost->created_at->format('d M, Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
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