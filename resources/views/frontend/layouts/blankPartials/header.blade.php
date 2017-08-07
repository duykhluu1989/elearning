<header id="header-1" class="navbar-fixed-top header">
    <div class="menu">
        <nav role="navigation" class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="{{ action('Frontend\HomeController@home') }}" class="navbar-brand"></a>
                </div>
                <div class="collapse navbar-collapse js-navbar-collapse">
                    <ul class="nav navbar-nav main_menu">

                        @include('frontend.layouts.partials.menu')

                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>