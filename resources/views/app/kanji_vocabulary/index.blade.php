@extends('layouts.theApp')
@section('title')
    {{__('Learn the kanjis')}}
@endsection

@section('seo')
    <title>Learn the kanjis</title>
@endsection

@if(!$userLearnedKanas)
    @section('alert')
        <div class="alert alert-danger m-sm-0 m-2 mb-md-4" role="alert">
            It seems like you haven't learned all the kanas yet. It will be harder to learn the kanjis and vocabulary without this knowledge.
        </div>
    @endsection
@endif


@section('content')
    <div>
        <div class="row">
            <div class="col-md-7 mb-3">
                <div class="row">
                    <md-card class="nb-lessons-reviews-box col-md-4 col-12 mb-3 mb-md-0 ml-0 ml-md-4" >
                        <md-card-header>
                            <h3>{{__('Lessons')}}</h3>
                        </md-card-header>
                        <md-card-content>
                            @if(count($itemsToLearn) > 0)
                                <p class="h3">{{count($itemsToLearn)}}</p>
                            @else
                                <p class="h4">New items available when you successfuly review a kanji 5 times</p>
                            @endif
                        </md-card-content>
                        <md-card-actions>
                            @if(count($itemsToLearn) > 0)
                                <md-button href="{{route('study.vocab.lesson')}}" class="md-raised md-accent">{{__('Learn')}}</md-button>
                            @else
                                <md-button disabled class="md-raised md-accent">{{__('Learn')}}</md-button>
                            @endif
                        </md-card-actions>
                    </md-card>

                    <md-card class="nb-lessons-reviews-box col-md-4 col-12 ml-0">
                        <md-card-header>
                            <h3>{{__('Reviews')}}</h3>
                        </md-card-header>
                        <md-card-content>
                            @if(count($itemsToReview) > 0)
                                <p class="h3">{{count($itemsToReview)}}</p>
                            @else
                                @if($nextReviewIn)
                                    <p class="h4">Next review in <br/>{{$nextReviewIn}}</p>
                                @else
                                    <p class="h3">0</p>
                                @endif
                            @endif
                        </md-card-content>
                        <md-card-actions>
                            @if(count($itemsToReview))
                                <md-button href="{{route('study.vocab.review')}}" class="md-raised md-accent">{{__('Study')}}</md-button>
                            @else
                                <md-button disabled class="md-raised md-accent">{{__('Study')}}</md-button>
                            @endif
                        </md-card-actions>
                    </md-card>
                </div>

                <div class="mt-3">
                    <kanji-current-level-overview
                        fetch-data-endpoint="{{route('api.learning_path.kanji.items.level_overview')}}"
                        :level="{{Auth::user()->info->kanji_level}}"
                    >

                    </kanji-current-level-overview>
                </div>

                <div class="mt-3">
                    <vocab-size-per-day-graph
                        data-endpoint="{{route('api.dashboard.vocab_size')}}"
                    ></vocab-size-per-day-graph>
                </div>
            </div>
            <div class="col-sm-5 col-md-4 offset-md-1 col-xl-3 offset-xl-2">
                <h2>Reviews this week</h2>
                <review-forecast
                    :number-reviews-per-day="{{json_encode($nextWeekVocabReview)}}"
                >

                </review-forecast>
            </div>

        </div>
{{--        <div class="row mb-4">--}}
{{--            @foreach($user->info->itemsPerHumanLevel() as $humanLevel => $nbItems)--}}
{{--                <md-card class="md-2">--}}
{{--                    <md-card-header>--}}
{{--                        <h3>{{$humanLevel}}</h3>--}}
{{--                        <p>{{$nbItems}}</p>--}}
{{--                    </md-card-header>--}}
{{--                </md-card>--}}
{{--            @endforeach--}}
{{--        </div>--}}
    </div>
@endsection
