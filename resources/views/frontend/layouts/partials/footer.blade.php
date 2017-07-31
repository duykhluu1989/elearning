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
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <p class="copyright">&copy; 2017 <a href="{{ action('Frontend\HomeController@home') }}">{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_TITLE) }}</a>. All rights reserved.</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <p class="luottruycap"><i class="fa fa-user-circle fa-lg" aria-hidden="true"></i>@lang('theme.visitor_count'): <span>{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_VISITOR_COUNT) }}</p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
        ?>

        <a class="btn btnBGmoi" href="#modal_BGM" data-toggle="modal">
            <span class="count">{{ count($newCourses) }}</span>
            <i class="fa fa-newspaper-o fa-lg" aria-hidden="true"></i> @lang('theme.new_course')
        </a>

        <a class="btn btnTTmoi" href="#modal_TTM" data-toggle="modal">
            <span class="count">3</span>
            <i class="fa fa-newspaper-o fa-lg" aria-hidden="true"></i> @lang('theme.news')
        </a>

        <div id="modal_TTM" class="modal fade baivietmoi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title text-center">TIN TỨC MỚI</h4>
                    </div>
                    <div class="modal-body">
                        <div class="baivietmoi_content">
                            <article>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <img src="{{ asset('themes/images/hv01.jpg') }}" alt="" class="img-responsive w100p">
                                    </div>
                                    <div class="col-xs-9">
                                        <h4>Lorem ipsum dolor.</h4>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorem, deserunt.</p>
                                    </div>
                                </div>
                            </article>
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
                        <div class="baivietmoi_content">

                            @foreach($newCourses as $newCourse)
                                <article>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <a href="{{ action('Frontend\CourseController@detailCourse', ['id' => $newCourse->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($newCourse, 'slug')]) }}">
                                                <img src="{{ $newCourse->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($newCourse, 'name') }}" class="img-responsive w100p">
                                            </a>
                                        </div>
                                        <div class="col-xs-9">
                                            <h4>{{ \App\Libraries\Helpers\Utility::getValueByLocale($newCourse, 'name') }}</h4>
                                            <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($newCourse, 'short_description') }}</p>
                                        </div>
                                    </div>
                                </article>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</footer>