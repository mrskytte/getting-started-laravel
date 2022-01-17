<?php


namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Post
{
  public $title;

  public $excerpt;

  public $date;

  public $slug;

  public $body;

  public function __construct($title, $excerpt, $date, $body, $slug)
  {
    $this->title = $title;
    $this->excerpt = $excerpt;
    $this->date = $date;
    $this->body = $body;
    $this->slug = $slug;
  }

  public static function all()
  {
    return cache()->rememberForever('post.all', function () {
      return collect(File::files(resource_path("posts/")))
        ->map(fn ($file) => YamlFrontMatter::parseFile($file))
        ->map(fn ($doc) => new Post(
          $doc->title,
          $doc->excerpt,
          $doc->date,
          $doc->body(),
          $doc->slug
        ))
        ->sortByDesc('date');
    });
  }

  public static function find($slug)
  {
    return static::all()->firstWhere('slug', $slug);
  }

  public static function findOrFail($slug)
  {
    $post = static::find($slug);

    if (!$post) {
      throw new ModelNotFoundException();
    }

    return $post;
  }
}
