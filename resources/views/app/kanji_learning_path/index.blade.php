@extends('layouts.theApp')
@section('title')
    {{__('Learning paths')}}
@endsection

@section('toolbar_right')
    <new-learningpath-item-modal
        create-api-endpoint="{{route('api.kanji_learning_path.items.store')}}"
        word-search-api-endpoint="{{route('api.dictionary.query')}}"
    ></new-learningpath-item-modal>

    <md-button href="{{route('kanji_learning_path.vocab.export')}}">
        {{__('Export')}}
    </md-button>

    <md-button>
        {{__('Import')}}
    </md-button>
@endsection

@section('content')
    @foreach($itemsByLevel as $level => $items)
        <div class="row">
            <md-card class="col-md-3" md-with-hover>
                <md-card-header>
                    <md-card-header-text>
                        <h3>{{__('Level')}} {{$level}}</h3>
                    </md-card-header-text>

                </md-card-header>
                <md-card-content class="learning-path-box-content">
                    <p>Radicals: {{$items->filter(function($item, $key){return $item->word_type_id == \App\Models\WordType::radical()->id;})->count()}}</p>
                    <p>Kanjis: {{$items->filter(function($item, $key){return $item->word_type_id == \App\Models\WordType::kanji()->id;})->count()}}</p>
                    <p>Vocabulary: {{$items->filter(function($item, $key){return $item->word_type_id == \App\Models\WordType::vocabulary()->id;})->count()}}</p>
                </md-card-content>
                <md-card-actions>
                    <md-button href="{{route('kanji_learning_path.vocab.edit', ['level' => $level])}}">{{__("Edit")}}</md-button>
                </md-card-actions>
            </md-card>
        </div>

    @endforeach
@endsection