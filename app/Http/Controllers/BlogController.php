<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\News;
use File;

class BlogController extends Controller
{
    public function AddPostBlog()
    {
        return view('admin.blog.create_post');
    }

    public function CreatePostBlog(Request $request)
    {
        $create = new Blog;
        $create->title    = $request->title;
        $create->category = $request->category;
        $create->post     = $request->post;

        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

            $requestImage = $request->imagem;
            $extension    = $requestImage->extension();
            $imageName    = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
            $requestImage->move(public_path('img/blog'), $imageName);

            $create->imagem = $imageName;
        }

        $create->save();

        return back();
    }

    public function ListPostsBlog()
    {
        // $list_blog = Blog::all()->paginate(10);
        $list_blog = Blog::paginate(10);

        return view('admin.blog.list_posts', compact('list_blog'));
    }

    public function PostsEdit($id)
    {
        $post_edit = Blog::find($id);

        return view('admin.blog.edit_post', compact('post_edit'));
    }

    public function PostsUpdate(Request $request, $id)
    {
        $post_edit = Blog::find($id);
        $post_edit->update([
            'title'    => $request->title,
            'category' => $request->category,
            'post'     => $request->post,
        ]);

        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

            File::delete('img/blog/'.$post_edit->imagem);

            $requestImage = $request->imagem;
            $extension    = $requestImage->extension();
            $imageName    = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
            $requestImage->move(public_path('img/blog'), $imageName);

            $post_edit->update([
                'imagem'  => $imageName
            ]);
        }

        return redirect()->route('admin.blog.ListPostsBlog');
    }

    public function PostsDelete($id)
    {
        $delete_post = Blog::find($id);

        File::delete('img/blog/'.$delete_post->imagem);

        $delete_post->delete();

        return redirect()->route('admin.blog.ListPostsBlog');
    }

    public function blog()
    {
        $blog = Blog::first();

        return view('blog', compact('blog'));
    }




    public function ListPostsNews()
    {
        $news = News::paginate(10);
        return view('admin.news.list_news', compact('news'));
    }

    public function AddPostNews()
    {
        return view('admin.news.create_news');
    }

    public function CreatePostNews(Request $request)
    {
        $create = new News;
        $create->title       = $request->title;
        $create->resume      = $request->resume;
        $create->description = $request->description;
        $create->activated   = 1;

        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

            $requestImage = $request->imagem;
            $extension    = $requestImage->extension();
            $imageName    = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
            $requestImage->move(public_path('img/blog'), $imageName);

            $create->img_url = $imageName;
        }

        $create->save();

        return redirect()->route('admin.news.ListPostsNews');
    }

    public function PostsNewsEdit($id)
    {
        $news = News::find($id);

        return view('admin.news.edit_news', compact('news'));
    }

    public function PostsNewsUpdate(Request $request, $id)
    {
        $news = News::find($id);
        $news->update([
            'title'       => $request->title,
            'resume'      => $request->resume,
            'description' => $request->description,
        ]);

        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

            File::delete('img/blog/'.$news->img_url);

            $requestImage = $request->imagem;
            $extension    = $requestImage->extension();
            $imageName    = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
            $requestImage->move(public_path('img/blog'), $imageName);

            $post_edit->update([
                'img_url'  => $imageName
            ]);
        }

        return back();
    }

    public function PostsNewsDisabled($id)
    {
        $news = News::find($id);
        $news->update([
            'activated' => 2,
        ]);

        return back();
    }

    public function PostsNewsActivated($id)
    {
        $news = News::find($id);
        $news->update([
            'activated' => 1,
        ]);

        return back();
    }

    public function PostsNewsDelete($id)
    {
        $news = News::find($id);
        File::delete('img/blog/'.$news->img_url);
        $news->delete();

        return back();
    }
}
