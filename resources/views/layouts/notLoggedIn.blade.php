<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="fragment" content="!">
    @yield('seo_info')
    @include('layouts.header')
    @yield('scripts')


    @if(env('APP_ENV') == "production")
        @include('layouts.parts.mailchimp')
        @include('layouts.parts.analytics')
        <script src="https://www.google.com/recaptcha/api.js?render=6LcmAwAVAAAAAAnM-_UF8eg6L_YMNCj67S_2FHKu"></script>
        @include('layouts.parts.pixel')

    @else
    <!-- Dev version of analytics -->

        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-165388196-2', 'auto');
            ga('send', 'pageview');
        </script>
    @endif

{{--    @include('layouts.parts.cookieConsent')--}}

    <script>
        function fireContactEvent() {
            ga('send', 'event', 'contact', 'button_click', 'contact_us', 1, {
                hitCallback: function(){
                    var form = document.getElementById("contact-form");
                    if(form.reportValidity()) {
                        form.submit();
                    }
                }
            });
        }
    </script>

</head>
<body>
    <div id="app" class="page-container md-layout-column">
        <div class="app-wrapper">
            <front-page-toolbar>
                <template v-slot:desktop_menu>
                    <md-button href="{{route('frontpage.home')}}">Home</md-button>
                    <md-button href="{{route('frontpage.grammar')}}">Grammar</md-button>
                    <md-button href="{{route('frontpage.stories')}}">Japanese stories</md-button>
                    <md-button href="{{route('frontpage.blog')}}">Blog</md-button>


                        {{--                            <div class="nav-separator d-none d-lg-block"></div>--}}

                    @if(Auth::guest())
                        <md-button href="{{route('login')}}">Login</md-button>
                        <md-button href="{{route('register')}}">Register</md-button>
                    @else
                        <md-button class="dashboard-btn" href="{{route('dashboard')}}">Dashboard<md-icon>arrow_forward_ios</md-icon></md-button>
                    @endif
                </template>
                <template v-slot:mobile_menu>
                    <md-list-item href="{{route('frontpage.home')}}" exact>
                        <md-icon>home</md-icon>
                        <span class="md-list-item-text">{{__('Home')}}</span>
                    </md-list-item>
                    <md-list-item href="{{route('frontpage.grammar')}}" exact>
                        <md-icon>spellcheck</md-icon>
                        <span class="md-list-item-text">{{__('Grammar')}}</span>
                    </md-list-item>
                    <md-list-item href="{{route('frontpage.stories')}}" exact>
                        <md-icon>subject</md-icon>
                        <span class="md-list-item-text">{{__('Japanese Stories')}}</span>
                    </md-list-item>
                    <md-list-item href="{{route('frontpage.blog')}}" exact>
                        <md-icon>question_answer</md-icon>
                        <span class="md-list-item-text">{{__('Blog')}}</span>
                    </md-list-item>

                        <md-divider></md-divider>

                    @if(Auth::guest())
                        <md-list-item href="{{route('login')}}" exact>
                            <md-icon>forward</md-icon>
                            <span class="md-list-item-text">{{__('Login')}}</span>
                        </md-list-item>
                        <md-list-item href="{{route('register')}}" exact>
                            <md-icon>trip_origin</md-icon>
                            <span class="md-list-item-text">{{__('Create account')}}</span>
                        </md-list-item>
                    @else
                        <md-list-item href="{{route('dashboard')}}" exact>
                            <md-icon>dashboard</md-icon>
                            <span class="md-list-item-text">{{__('Dashboard')}}</span>
                        </md-list-item>
                    @endif

                </template>

                <template v-slot:content>
                    @yield('content')
                </template>

            </front-page-toolbar>
        </div>


        <!-- Snack Bar flash messages -->
        @if(Session::has('message'))
            <flash message="{{Session::get('message')}}"></flash>
        @endif

        <footer class="">

            <div class="row m-0 flex-grow-1 footer-containers">
                <div class="col-12 col-lg-4 mb-5">
                    <h3 class="h2">Latest articles</h3>

                    <latest-articles
                        api-endpoint="{{route('api.frontpage.latest.articles')}}"
                        read-url="{{route('frontpage.blog.view', ['post' => ':slug'])}}"
                        read-more-url="{{route('frontpage.blog')}}"
                    ></latest-articles>
                </div>
                <div class="col-12 col-lg-4 mb-5">
                    <h3 class="h2">Grammar lessons</h3>

                    <random-grammar
                        api-endpoint="{{route('api.frontpage.random.grammar')}}"
                        read-url="{{route('frontpage.grammar.view', ['item' => ':slug'])}}"
                        read-more-url="{{route('frontpage.grammar')}}"
                    >

                    </random-grammar>
                </div>
                <div class="col-12 col-lg-4">
                    <h3 class="h2">Contact us</h3>
                    <md-card>

                        <form method="POST" action="{{route('api.frontpage.contactus')}}" class="contact-form" id="contact-form">
                            @csrf
                            <md-field>
                                <label>Name</label>
                                <md-input name="name" required/>
                            </md-field>

                            <md-field>
                                <label>Email</label>
                                <md-input type="email" name="email" required/>
                            </md-field>

                            <md-field>
                                <label>Message</label>
                                <md-textarea name="message" required></md-textarea>
                            </md-field>

                            <md-button onclick="event.preventDefault();fireContactEvent()" type="submit" class="md-raised md-primary">Send</md-button>
                        </form>
                    </md-card>

                </div>
            </div>

            <div class="copyright">
                <p>© Copyright {{now()->format("Y")}}, JapaLearn</p>
                <p><a href="{{route('frontpage.privacyPolicy')}}">privacy</a> and <a href="{{route('frontpage.termsAndConditions')}}">terms</a></p>
            </div>
        </footer>
    </div>

    @yield('scripts_end')
    <!--End mc_embed_signup-->
</body>
</html>
