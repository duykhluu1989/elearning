<?php
$rootMenus = \App\Models\Menu::getMenuTree();
?>
@foreach($rootMenus as $rootMenu)
    <?php
    if(isset($rootMenu->auto_categories))
        $countAutoCategory = count($rootMenu->auto_categories);
    else
        $countAutoCategory = 0;

    $countChildrenMenu = count($rootMenu->childrenMenus) + $countAutoCategory;
    ?>

    <li class="{{ $countChildrenMenu > 0 ? 'dropdown mega-dropdown' : '' }}">
        <a href="{{ $rootMenu->getMenuUrl() }}"<?php echo ($countChildrenMenu > 0 ? ' data-toggle="dropdown" class="dropdown-toggle"' : ''); ?>>{{ $rootMenu->getMenuTitle(false) }}<?php echo ($countChildrenMenu > 0 ? ' <b class="caret"></b>' : ''); ?></a>

        @if($countChildrenMenu > 0)

            <ul role="menu" class="dropdown-menu mega-dropdown-menu">

                @if($countAutoCategory > 0)

                    @foreach($rootMenu->auto_categories as $autoCategory)
                        <?php
                        $countChildrenCategory = count($autoCategory->childrenCategories);
                        ?>

                        @if($countChildrenCategory > 0)

                            <li class="col-sm-3">
                                <ul>
                                    <li class="dropdown-header">{{ \App\Libraries\Helpers\Utility::getValueByLocale($autoCategory, 'name') }}</li>

                                    @foreach($autoCategory->childrenCategories as $childCategory)

                                        <li><a href="{{ \App\Libraries\Helpers\Utility::getValueByLocale($childCategory, 'slug') }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($childCategory, 'name') }}</a></li>

                                    @endforeach

                                </ul>
                            </li>

                        @else

                            <li><a href="{{ \App\Libraries\Helpers\Utility::getValueByLocale($autoCategory, 'slug') }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($autoCategory, 'name') }}</a></li>

                        @endif

                    @endforeach

                @endif

                @foreach($rootMenu->childrenMenus as $childMenu)
                    <?php
                    if(isset($childMenu->auto_categories))
                        $countChildAutoCategory = count($childMenu->auto_categories);
                    else
                        $countChildAutoCategory = 0;

                    $countChildrenMenu2 = count($childMenu->childrenMenus) + $countChildAutoCategory;
                    ?>

                    @if($countChildrenMenu2 > 0)

                        <li class="col-sm-3">
                            <ul>
                                <li class="dropdown-header">{{ $childMenu->getMenuTitle(false) }}</li>

                                @if($countChildAutoCategory > 0)

                                    @foreach($childMenu->auto_categories as $childAutoCategory)

                                        <li><a href="{{ \App\Libraries\Helpers\Utility::getValueByLocale($childAutoCategory, 'slug') }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($childAutoCategory, 'name') }}</a></li>

                                    @endforeach

                                @endif

                                @foreach($childMenu->childrenMenus as $childMenu2)

                                    <li><a href="{{ $childMenu2->getMenuUrl() }}">{{ $childMenu2->getMenuTitle(false) }}</a></li>

                                @endforeach

                            </ul>
                        </li>

                    @else

                        <li><a href="{{ $childMenu->getMenuUrl() }}">{{ $childMenu->getMenuTitle(false) }}</a></li>

                    @endif

                @endforeach

            </ul>

        @endif

    </li>

@endforeach