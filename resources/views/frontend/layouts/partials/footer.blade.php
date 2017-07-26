<footer>
    <section class="footer_top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <h5>@lang('theme.category_list')</h5>
                    <ul class="list_footer">

                        <?php
                        $rootCategories = \App\Models\Category::select('id', 'name', 'name_en', 'slug', 'slug_en')
                            ->where('status', \App\Libraries\Helpers\Utility::ACTIVE_DB)
                            ->where('parent_status', \App\Libraries\Helpers\Utility::ACTIVE_DB)
                            ->whereNull('parent_id')
                            ->orderBy('order', 'desc')
                            ->get();
                        ?>

                        @foreach($rootCategories as $rootCategory)
                            <li><a href="{{ action('Frontend\CourseController@detailCategory', ['id' => $rootCategory->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($rootCategory, 'slug')]) }}">- {{ \App\Libraries\Helpers\Utility::getValueByLocale($rootCategory, 'name') }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <h5>@lang('theme.course_description')</h5>
                    <ul class="list_footer">

                        <?php
                        $groupIntroPages = \App\Models\Article::select('id', 'name', 'name_en', 'slug', 'slug_en')
                            ->where('type', \App\Models\Article::TYPE_STATIC_ARTICLE_DB)
                            ->where('status', \App\Models\Course::STATUS_PUBLISH_DB)
                            ->where('group', \App\Models\Article::STATIC_ARTICLE_GROUP_INTRO_DB)
                            ->orderBy('order', 'desc')
                            ->get();
                        ?>

                        @foreach($groupIntroPages as $groupIntroPage)
                            <li><a href="{{ action('Frontend\PageController@detailPage', ['id' => $groupIntroPage->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($groupIntroPage, 'slug')]) }}">- {{ \App\Libraries\Helpers\Utility::getValueByLocale($groupIntroPage, 'name') }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <h5>@lang('theme.guide')</h5>
                    <ul class="list_footer">

                        <?php
                        $groupGuidePages = \App\Models\Article::select('id', 'name', 'name_en', 'slug', 'slug_en')
                            ->where('type', \App\Models\Article::TYPE_STATIC_ARTICLE_DB)
                            ->where('status', \App\Models\Course::STATUS_PUBLISH_DB)
                            ->where('group', \App\Models\Article::STATIC_ARTICLE_GROUP_GUIDE_DB)
                            ->orderBy('order', 'desc')
                            ->get();
                        ?>

                        @foreach($groupGuidePages as $groupGuidePage)
                            <li><a href="{{ action('Frontend\PageController@detailPage', ['id' => $groupGuidePage->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($groupGuidePage, 'slug')]) }}">- {{ \App\Libraries\Helpers\Utility::getValueByLocale($groupGuidePage, 'name') }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <h5>&nbsp;</h5>
                    <ul class="list_footer">
                        <li><a href="{{ action('Frontend\ArticleController@sample') }}">- Hợp tác giảng dạy</a></li>
                        <li><a href="{{ action('Frontend\ArticleController@sample') }}">- Liên kết website </a></li>
                        <li><a href="{{ action('Frontend\ArticleController@sample') }}">- Đăng ký bảo mật</a></li>
                        <li><a href="{{ action('Frontend\ArticleController@sample') }}">- Qui trình mua hàng</a></li>
                        <li><a href="{{ action('Frontend\ArticleController@sample') }}">- Về pháp lý</a></li>
                        <li><a href="{{ action('Frontend\ArticleController@sample') }}">- Bản tin kinh tế</a></li>
                        <li><a href="{{ action('Frontend\ArticleController@sample') }}">- Kiến thức pháp luật</a></li>
                        <li><a href="{{ action('Frontend\ArticleController@sample') }}">- Góc truyền thông</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="footer_bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <p>© 2017 caydenthan.vn. All rights reserved.</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="list_social">
                        <li><a href="{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_SOCIAL_DB, \App\Models\Setting::FACEBOOK_PAGE_URL) }}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="javascript:void(0)"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="javascript:void(0)"><i class="fa fa-youtube-square" aria-hidden="true"></i></a></li>
                    </ul>
                    <p class="pull-right luottruycap"><i class="fa fa-user-circle fa-lg" aria-hidden="true"></i> Lượt truy cập: <span>8888</span></p>
                </div>
            </div>
        </div>
        <a href='#' class="scroll_to_top"><i class="fa fa-angle-double-up fa-2x" aria-hidden="true"></i></a>
        <a class="btn btnBGmoi" href="#modal_BGM" data-toggle="modal">
            <span class="count">3</span>
            <i class="fa fa-newspaper-o fa-lg" aria-hidden="true"></i> BÀI GIẢNG MỚI
        </a>
        <a class="btn btnTTmoi" href="#modal_TTM" data-toggle="modal">
            <span class="count">3</span>
            <i class="fa fa-newspaper-o fa-lg" aria-hidden="true"></i> TIN TỨC MỚI
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
                        <h4 class="modal-title text-center">BÀI GIẢNG MỚI</h4>
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

    </section>
</footer>