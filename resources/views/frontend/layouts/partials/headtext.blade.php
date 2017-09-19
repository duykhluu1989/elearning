<section class="hotdeal mt125">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <?php
                $headlineWidget = \App\Models\Widget::select('detail')->where('status', \App\Libraries\Helpers\Utility::ACTIVE_DB)->where('code', \App\Models\Widget::GROUP_HEAD_LINE)->first();
                $headlineItems = array();

                if(!empty($headlineWidget) && !empty($headlineWidget->detail))
                    $headlineItems = json_decode($headlineWidget->detail, true);
                ?>

                @foreach($headlineItems as $headlineItem)
                    <a href="{{ isset($headlineItem['url']) ? $headlineItem['url'] : 'javascript:void(0)' }}"><i class="fa fa-fire" aria-hidden="true"></i> {{ \App\Libraries\Helpers\Utility::getValueByLocale($headlineItem, 'description') }}</a>
                @endforeach
            </div>
            <div class="col-lg-5">
                <p class="cskh"><i class="fa fa-phone-square" aria-hidden="true"></i> {{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::HOT_LINE) }}</p>
            </div>
        </div>
    </div>
</section>