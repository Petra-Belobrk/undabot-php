<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Styles -->

    </head>
    <body>
    <div class="col-sm-6">
        {!! Form::open(['method' => 'post', 'action' => 'SearchController@search']) !!}

    <div class="form-group">
        {!! Form::label('search', 'Search:') !!}
        {!! Form::text('search', null, ['class'=>'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('provider', 'From:') !!}
        {!! Form::select('provider', [''=>'Choose Options'] + $providers , null, ['class'=>'form-control'])!!}

    </div>

    <div class="form-group">
        {!! Form::label('apiv2', 'Would you like to get another version of response?') !!}
        {!! Form::checkbox('apiv2', 1) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Search Term', ['class'=>'btn btn-info']) !!}
    </div>

    {!! Form::close() !!}
    </div>
    </body>
</html>
