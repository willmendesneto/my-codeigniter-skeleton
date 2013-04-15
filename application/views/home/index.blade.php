@layout('master')

@section('container')
	<h1>My Awesome Url Shortener</h1>
	{{ form_open('posts/blade', 'id="posts_blade"', array('custom' => 'teste')); }}
		{{ form_input('url'); }}
        {{ form_checkbox('noticias', 'noticias', 'id="noticias"') }}
	{{ form_close(); }}
@endsection