<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{
    /**
     * Lista de tipos MIME permitidos para imagens
     */
    private $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml'
    ];

    /**
     * Tamanho máximo para imagens
     */
    private $maxWidth = 2024;
    private $maxHeight = 2024;
    private $maxSize = 2048; // em KB (2MB)

    /**
     * Exibe a página principal do blog com os posts mais recentes
     */
    public function index()
    {
        $posts = Post::with(['category', 'user'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        $featured = Post::with(['category', 'user'])
            ->where('status', 'published')
            ->where('featured', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $categories = Category::withCount('posts')->get();
            
        return view('blog.index', compact('posts', 'featured', 'categories'));
    }
    
    /**
     * Exibe posts de uma categoria específica
     */
    public function category($category)
    {
        $category = Category::where('slug', $category)->firstOrFail();
        
        $posts = Post::with(['category', 'user'])
            ->where('status', 'published')
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        return view('blog.category', compact('posts', 'category'));
    }
    
    /**
     * Exibe um post específico
     */
    public function show($slug)
    {
        $post = Post::with(['category', 'user', 'comments.user', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
            
        // Incrementa o contador de visualizações
        $post->increment('views');
        
        // Posts relacionados
        $relatedPosts = Post::with(['category', 'user'])
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        return view('blog.show', compact('post', 'relatedPosts'));
    }
    
    /**
     * Exibe posts com uma tag específica
     */
    public function tag($tag)
    {
        $tag = Tag::where('slug', $tag)->firstOrFail();
        
        $posts = $tag->posts()
            ->with(['category', 'user'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        return view('blog.tag', compact('posts', 'tag'));
    }
    
    /**
     * Exibe posts de um autor específico
     */
    public function author($author)
    {
        $author = \App\Models\User::where('username', $author)->firstOrFail();
        
        $posts = Post::with(['category', 'user'])
            ->where('status', 'published')
            ->where('user_id', $author->id)
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        return view('blog.author', compact('posts', 'author'));
    }
    
    /**
     * Realiza uma busca nos posts
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $posts = Post::with(['category', 'user'])
            ->where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        return view('blog.search', compact('posts', 'query'));
    }
    
    /**
     * Exibe o painel de administração do blog
     */
    public function adminIndex()
    {
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        $totalCategories = Category::count();
        $totalComments = Comment::count();
        
        $recentPosts = Post::with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentComments = Comment::with(['post', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.blog.index', compact(
            'totalPosts', 
            'publishedPosts', 
            'draftPosts', 
            'totalCategories', 
            'totalComments', 
            'recentPosts', 
            'recentComments'
        ));
    }
    
    /**
     * Exibe a lista de posts no painel administrativo
     */
    public function adminPosts()
    {
        $posts = Post::with(['category', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.blog.posts', compact('posts'));
    }
    
    /**
     * Exibe o formulário para criar um novo post
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('admin.blog.create', compact('categories', 'tags'));
    }
    
    /**
     * Armazena um novo post no banco de dados
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048|mimes:jpeg,png,gif,webp,svg',
            'status' => 'required|in:published,draft',
            'featured' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);
        
        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->excerpt = Str::limit(strip_tags($request->content), 150);
        $post->category_id = $request->category_id;
        $post->user_id = Auth::id();
        $post->status = $request->status;
        $post->featured = $request->has('featured');
        
        // Upload da imagem (se enviada)
        if ($request->hasFile('image')) {
            $this->validateImage($request->file('image'));
            
            $imagePath = $request->file('image')->store('blog', 'public');
            $post->image = $imagePath;
            
            // Opcionalmente, redimensionar a imagem para garantir compatibilidade
            $this->resizeImage($imagePath);
        }
        
        $post->save();
        
        // Associar tags
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }
        
        return redirect()->route('admin.blog.posts')
            ->with('success', 'Post criado com sucesso!');
    }
    
    /**
     * Exibe o formulário para editar um post
     */
    public function edit($id)
    {
        $post = Post::with('tags')->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('admin.blog.edit', compact('post', 'categories', 'tags'));
    }
    
    /**
     * Atualiza um post no banco de dados
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048|mimes:jpeg,png,gif,webp,svg',
            'status' => 'required|in:published,draft',
            'featured' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);
        
        $post = Post::findOrFail($id);
        $post->title = $request->title;
        
        // Atualiza o slug apenas se o título mudar
        if ($post->title != $request->title) {
            $post->slug = Str::slug($request->title);
        }
        
        $post->content = $request->content;
        $post->excerpt = Str::limit(strip_tags($request->content), 150);
        $post->category_id = $request->category_id;
        $post->status = $request->status;
        $post->featured = $request->has('featured');
        
        // Upload da imagem (se enviada)
        if ($request->hasFile('image')) {
            $this->validateImage($request->file('image'));
            
            // Remove a imagem antiga (se existir)
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            
            $imagePath = $request->file('image')->store('blog', 'public');
            $post->image = $imagePath;
            
            // Opcionalmente, redimensionar a imagem para garantir compatibilidade
            $this->resizeImage($imagePath);
        }
        
        $post->save();
        
        // Atualizar tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }
        
        return redirect()->route('admin.blog.posts')
            ->with('success', 'Post atualizado com sucesso!');
    }
    
    /**
     * Remove um post do banco de dados
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        // Remove a imagem (se existir)
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        
        // Remove as associações com tags
        $post->tags()->detach();
        
        // Remove o post
        $post->delete();
        
        return redirect()->route('admin.blog.posts')
            ->with('success', 'Post removido com sucesso!');
    }
    
    /**
     * Exibe a lista de categorias no painel administrativo
     */
    public function adminCategories()
    {
        $categories = Category::withCount('posts')->get();
        
        return view('admin.blog.categories', compact('categories'));
    }
    
    /**
     * Armazena uma nova categoria no banco de dados
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name',
        ]);
        
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();
        
        return redirect()->route('admin.blog.categories')
            ->with('success', 'Categoria criada com sucesso!');
    }
    
    /**
     * Atualiza uma categoria no banco de dados
     */
    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $id,
        ]);
        
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();
        
        return redirect()->route('admin.blog.categories')
            ->with('success', 'Categoria atualizada com sucesso!');
    }
    
    /**
     * Remove uma categoria do banco de dados
     */
    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Verifica se existem posts nesta categoria
        if ($category->posts()->count() > 0) {
            return redirect()->route('admin.blog.categories')
                ->with('error', 'Não é possível remover uma categoria que contenha posts.');
        }
        
        $category->delete();
        
        return redirect()->route('admin.blog.categories')
            ->with('success', 'Categoria removida com sucesso!');
    }
    
    /**
     * Exibe a lista de comentários no painel administrativo
     */
    public function adminComments()
    {
        $comments = Comment::with(['post', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.blog.comments', compact('comments'));
    }
    
    /**
     * Atualiza um comentário no banco de dados
     */
    public function updateComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required',
            'status' => 'required|in:approved,pending,spam'
        ]);
        
        $comment = Comment::findOrFail($id);
        $comment->content = $request->content;
        $comment->status = $request->status;
        $comment->save();
        
        return redirect()->route('admin.blog.comments')
            ->with('success', 'Comentário atualizado com sucesso!');
    }
    
    /**
     * Remove um comentário do banco de dados
     */
    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        
        return redirect()->route('admin.blog.comments')
            ->with('success', 'Comentário removido com sucesso!');
    }
    
    /**
     * Armazena um novo comentário para um post
     */
    public function storeComment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required',
        ]);
        
        $post = Post::findOrFail($postId);
        
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->post_id = $post->id;
        $comment->user_id = Auth::id();
        $comment->status = 'pending'; // Pode ser alterado para 'approved' se não quiser moderação
        $comment->save();
        
        return redirect()->back()
            ->with('success', 'Comentário enviado com sucesso! Aguardando aprovação.');
    }
    
    /**
     * Remove um comentário do usuário
     */
    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Verifica se o usuário é o autor do comentário
        if ($comment->user_id != Auth::id()) {
            return redirect()->back()
                ->with('error', 'Você não tem permissão para excluir este comentário.');
        }
        
        $comment->delete();
        
        return redirect()->back()
            ->with('success', 'Comentário removido com sucesso!');
    }
    
    /**
     * Valida a imagem enviada
     * 
     * @param \Illuminate\Http\UploadedFile $image
     * @throws \Exception
     */
    private function validateImage($image)
    {
        // Verifica o tipo MIME
        if (!in_array($image->getMimeType(), $this->allowedMimes)) {
            $allowedTypesStr = implode(', ', array_map(function($mime) {
                return str_replace('image/', '', $mime);
            }, $this->allowedMimes));
            
            throw new \Exception("Tipo de arquivo inválido. Tipos permitidos: {$allowedTypesStr}");
        }
        
        // Verifica o tamanho (já validado pelo Laravel, mas podemos fazer uma verificação adicional)
        $maxSizeBytes = $this->maxSize * 1024; // Converter KB para bytes
        if ($image->getSize() > $maxSizeBytes) {
            throw new \Exception("A imagem excede o tamanho máximo permitido de {$this->maxSize}KB");
        }
        
        // Verifica as dimensões
        $imageInfo = getimagesize($image->getPathname());
        if ($imageInfo) {
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            
            if ($width > $this->maxWidth || $height > $this->maxHeight) {
                throw new \Exception("A imagem excede as dimensões máximas permitidas de {$this->maxWidth}x{$this->maxHeight} pixels");
            }
        }
    }
    
    /**
     * Redimensiona a imagem se necessário
     * 
     * @param string $path
     * @return void
     */
    private function resizeImage($path)
    {
        try {
            $fullPath = Storage::disk('public')->path($path);
            $image = Image::make($fullPath);
            
            // Redimensiona apenas se a imagem for maior que as dimensões máximas
            if ($image->width() > $this->maxWidth || $image->height() > $this->maxHeight) {
                $image->resize($this->maxWidth, $this->maxHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                $image->save($fullPath);
            }
        } catch (\Exception $e) {
            \Log::warning("Não foi possível redimensionar a imagem: " . $e->getMessage());
            // Continua mesmo se não conseguir redimensionar
        }
    }
}