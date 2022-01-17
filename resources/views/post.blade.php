<x-layout>
  <article>
    <h1>
      {{ $post->title }}
    </h1>
    <p>
      {!! $post->body !!}
    </p>
    <a class="link--back" href="/">Go back</a>
  </article>
</x-layout>
