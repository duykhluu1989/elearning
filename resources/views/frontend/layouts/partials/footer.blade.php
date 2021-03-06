<footer>
    <section class="footer_top">
        <div class="container">
            <div class="row">

                <?php
                $rootFooterMenus = \App\Models\Menu::getMenuTree(\App\Models\Menu::THEME_POSITION_FOOTER_DB);
                $noParentFooterMenus = array();
                ?>
                @foreach($rootFooterMenus as $rootFooterMenu)
                    <?php
                    if(isset($rootFooterMenu->auto_categories))
                        $countAutoCategory = count($rootFooterMenu->auto_categories);
                    else
                        $countAutoCategory = 0;

                        $countChildrenFooterMenu = count($rootFooterMenu->childrenMenus) + $countAutoCategory;
                    ?>
                    @if($countChildrenFooterMenu == 0)
                        <?php
                        $noParentFooterMenus[] = $rootFooterMenu;
                        ?>
                    @else
                        @if(count($noParentFooterMenus) > 0)
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <h5>&nbsp;</h5>
                                <ul class="list_footer">

                                    @foreach($noParentFooterMenus as $noParentFooterMenu)
                                        <li><a href="{{ $noParentFooterMenu->getMenuUrl() }}">- {{ $noParentFooterMenu->getMenuTitle(false) }}</a></li>
                                    @endforeach

                                </ul>
                            </div>
                            <?php
                            $noParentFooterMenus = array();
                            ?>
                        @else
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <h5>{{ $rootFooterMenu->getMenuTitle(false) }}</h5>
                                <ul class="list_footer">

                                    @if($countAutoCategory > 0)

                                        @foreach($rootFooterMenu->auto_categories as $autoCategory)
                                            <li><a href="{{ action('Frontend\CourseController@detailCategory', ['id' => $autoCategory->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($autoCategory, 'slug')]) }}">- {{ \App\Libraries\Helpers\Utility::getValueByLocale($autoCategory, 'name') }}</a></li>
                                        @endforeach

                                    @endif

                                    @foreach($rootFooterMenu->childrenMenus as $childFooterMenu)
                                        <li><a href="{{ $childFooterMenu->getMenuUrl() }}">- {{ $childFooterMenu->getMenuTitle(false) }}</a></li>
                                    @endforeach

                                </ul>
                            </div>
                        @endif
                    @endif
                @endforeach

                @if(count($noParentFooterMenus) > 0)
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <h5>&nbsp;</h5>
                        <ul class="list_footer">

                            @foreach($noParentFooterMenus as $noParentFooterMenu)
                                <li><a href="{{ $noParentFooterMenu->getMenuUrl() }}">- {{ $noParentFooterMenu->getMenuTitle(false) }}</a></li>
                            @endforeach

                        </ul>
                    </div>
                    <?php
                    $noParentFooterMenus = null;
                    ?>
                @endif
            </div>
        </div>
    </section>
    <section class="footer_bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p class="copyright">&copy; 2017 <a href="{{ action('Frontend\HomeController@home') }}">{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_TITLE) }}</a>. All rights reserved.</p>
                    <address><b>Địa chỉ:</b> 40 Vũ Tùng, P1, Q. Bình Thạnh, TP. HCM</address>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <p class="bocongthuong"><a href="javascript:void(0)"><img src="{{ asset('assets/images/bocongthuong.png') }}" alt="" class="img-responsive"></a></p>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <p class="luottruycap"><i class="fa fa-user-circle fa-lg" aria-hidden="true"></i>@lang('theme.visitor_count'): <span>{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_VISITOR_COUNT) }}</p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <p class="text_social">We are social: </p>
                            <ul class="list_social">

                                @if(!empty(\App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_SOCIAL_DB, \App\Models\Setting::FACEBOOK_PAGE_URL)))
                                    <li><a href="{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_SOCIAL_DB, \App\Models\Setting::FACEBOOK_PAGE_URL) }}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href='#' class="scroll_to_top"><i class="fa fa-angle-double-up fa-2x" aria-hidden="true"></i></a>

        <?php
        $newCourses = \App\Http\Controllers\Frontend\CourseController::getNewCourses();
        $newNews = \App\Http\Controllers\Frontend\NewsController::getNewNews();
        ?>

        <a class="btn btnBGmoi" href="#modal_BGM" data-toggle="modal">
            <span class="count" id="NewCourseModalCount">{{ count($newCourses) }}</span>
            <i class="fa fa-file-text fa-lg" aria-hidden="true"></i> @lang('theme.new_course')
        </a>

        <a class="btn btnTTmoi" href="#modal_TTM" data-toggle="modal">
            <span class="count" id="NewNewsModalCount">{{ count($newNews) }}</span>
            <i class="fa fa-newspaper-o fa-lg" aria-hidden="true"></i> @lang('theme.news')
        </a>

        <div id="modal_TTM" class="modal fade baivietmoi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title text-center">@lang('theme.news')</h4>
                    </div>
                    <div class="modal-body">
                        <div class="baivietmoi_content" id="NewNewsModalContent">

                            @include('frontend.news.partials.new_news')

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal_BGM" class="modal fade baivietmoi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title text-center">@lang('theme.new_course')</h4>
                    </div>
                    <div class="modal-body">
                        <div class="baivietmoi_content" id="NewCourseModalContent">

                            @include('frontend.courses.partials.new_course')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</footer>

@push('scripts')
    <script type="text/javascript">
        var baseNewCourseTimeout = 0;

        var newCourseTimeInterval = setInterval(function() {
            $.ajax({
                url: '{{ action('Frontend\CourseController@newCourseAndNews') }}',
                type: 'get',
                success: function(result) {
                    if(result)
                    {
                        result = JSON.parse(result);

                        var courseHtml = result['courses'];
                        var newsHtml = result['news'];

                        if(courseHtml)
                        {
                            $('#NewCourseModalContent').html(courseHtml);
                            var countNewCourse = courseHtml.split('class="row"').length;
                            if(countNewCourse > 0)
                                countNewCourse -= 1;
                            $('#NewCourseModalCount').html(countNewCourse);
                        }

                        if(newsHtml)
                        {
                            $('#NewNewsModalContent').html(newsHtml);
                            var countNewNews = newsHtml.split('class="row"').length;
                            if(countNewNews > 0)
                                countNewNews -= 1;
                            $('#NewNewsModalCount').html(countNewNews);
                        }
                    }
                }
            });

            baseNewCourseTimeout += 300;

            if(baseNewCourseTimeout >= 3600)
                clearInterval(newCourseTimeInterval);
        }, 300000);
    </script>
@endpush