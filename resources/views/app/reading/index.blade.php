@extends('layouts.theApp')
@section('title')
    {{__('Learn reading Japanese')}}
@endsection

@section('seo')
    <title>Reading Japanese</title>
@endsection

@section('content')
    <div>
        @foreach($stories as $story)
            <div class="mb-3">
                <story-list-item-card
                    :story="{{json_encode($story)}}"
                    read-url="{{route('stories.read', ['story' => ':story_id'])}}"
                    subscribe-url="{{route('account.subscription.index')}}"
                    :user-subscribed="{{Auth::user()->subscribed() ? 'true' : 'false'}}"
                >

                </story-list-item-card>
            </div>
        @endforeach
    </div>
@endsection
