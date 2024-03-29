@extends('layouts.theApp')
@section('title')
    {{__('Learn hiragana and katakana')}}
@endsection

@section('seo')
    <title>Learn the kanas</title>
@endsection

{{--@section('toolbar_right')--}}
{{--    <md-button href="{{route('study.kana.lesson')}}">{{__('Start learning !')}}</md-button>--}}
{{--@endsection--}}

@section('content')

    <div class="row mb-5">
        <md-card class="col-lg-3 col-md-4 col-12 mb-3 mb-md-0 ml-0 ml-md-4">
            <md-card-header>
                <h1>{{__('Items to learn')}}</h1>
            </md-card-header>
            <md-card-content>
                @if(count($itemsToLearn))
                    <p class="h3">{{count($itemsToLearn)}}</p>
                @else
                    <p class="h4">New items available when you a kana gets to level 5</p>
                @endif
            </md-card-content>
            <md-card-actions>
                @if(count($itemsToLearn) > 0)
                    <md-button href="{{route('study.kana.lesson')}}" class="md-raised md-accent">{{__('Learn')}}</md-button>
                @else
                    <md-button disabled class="md-raised md-accent">{{__('Learn')}}</md-button>
                @endif
            </md-card-actions>
        </md-card>
        <md-card class="col-lg-3 col-md-4 col-12 ml-0">
            <md-card-header>
                <h1>{{__('Items to review')}}</h1>
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
                @if(count($itemsToReview) > 0)
                    <md-button href="{{route('study.kana.review')}}" class="md-raised md-accent">{{__('Review')}}</md-button>
                @else
                    <md-button disabled class="md-raised md-accent">{{__('Review')}}</md-button>
                @endif
            </md-card-actions>
        </md-card>
    </div>


{{--    <p>The Japanese language consists of two scripts known as "kanas". The first one is the hiraganas.--}}
{{--        These are mainly used for grammatical purposes. The second one is the katakanas.--}}
{{--        These are mainly used to represent words imported from other countries.</p>--}}

    <div class="row">
        <div class="col-md-8 col-12 mb-3">
            <h2>Hiragana</h2>
            <hiragana-table
                :kanas="{{json_encode($allKanas)}}"
            ></hiragana-table>

            <h2>Katakana</h2>
            <hiragana-table
                :kanas="{{json_encode($allKanas)}}"
                :is-katakana="true"
            ></hiragana-table>
        </div>
        <div class="col-md-4 col-12 order-first order-md-last">
            <h1>Reviews this week</h1>

            <review-forecast
                :number-reviews-per-day="{{json_encode($nextWeekKanaReview)}}"
            >

            </review-forecast>
        </div>
    </div>

@endsection
