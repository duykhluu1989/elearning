<?php
$rootMenus = \App\Models\Menu::getMenuTree();
?>
@foreach($rootMenus as $rootMenu)
    <?php
    $countChildrenMenu = count($rootMenu->childrenMenus);
    ?>

    <li class="{{ $countChildrenMenu > 0 ? 'dropdown mega-dropdown' : '' }}">
        <a href="{{ $rootMenu->getMenuUrl() }}"<?php echo ($countChildrenMenu > 0 ? ' data-toggle="dropdown" class="dropdown-toggle"' : ''); ?>>{{ $rootMenu->getMenuTitle(false) }}<?php echo ($countChildrenMenu > 0 ? ' <b class="caret"></b>' : ''); ?></a>

        @if($countChildrenMenu > 0)

            <ul role="menu" class="dropdown-menu mega-dropdown-menu">

            @foreach($rootMenu->childrenMenus as $childMenu)
                <?php
                $countChildrenMenu2 = count($childMenu->childrenMenus);
                ?>

                @if($countChildrenMenu2 > 0)

                    <li class="col-sm-3">
                        <ul>
                            <li class="dropdown-header">{{ $childMenu->getMenuTitle(false) }}</li>

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