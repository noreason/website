@extends('layouts.main')

@section('content')
    @foreach($nades as $nadeTypeKey => $nadesByType)
    {{ Nade::getNadeTypeLabel($nadeTypeKey) }}:
    <div class="row">
        @foreach($nadesByType as $nade)
        <div class="col-sm-4">
            Title: {{ $nade->title }}
            <br>
            Imgur: {{ $nade->imgur_album }}
            <br>
            YouTube: {{ $nade->youtube }}
            <br>
            Working: {{ ($nade->is_working) ? "Yes" : "No" }}
            <br>
            Tags: {{ $nade->tags }}
            <br>
            Submited By: {{ $nade->user->username }}
            <br>
            Last Updated: {{ $nade->updated_at }}
        </div>
        @endforeach
    </div>
    @endforeach
@stop
