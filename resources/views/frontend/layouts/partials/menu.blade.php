@foreach($listMenus as $listMenu)
    <li class="{{ count($listMenu->childrenMenus) > 0 ? $itemClass : '' }}">
        <a href="chude.php"<?php echo (count($listMenu->childrenMenus) > 0 ? ' data-toggle="dropdown" class="dropdown-toggle"' : ''); ?>>{{ $list->getMenuTitle(false) }}<?php echo (count($listMenu->childrenMenus) > 0 ? ' <b class="caret"></b>' : ''); ?></a>

        @if(count($listMenu->childrenMenus) > 0)

        @endif
        <ul role="menu" class="dropdown-menu mega-dropdown-menu">
            <li class="col-sm-3">
                <ul>
                    <li><a href="#">Men Collection</a></li>
                    <li><a href="#">View all Collection</a></li>
                </ul>
            </li>
            <li class="col-sm-3">
                <ul>
                    <li class="dropdown-header">Features</li>
                    <li><a href="#">Auto Carousel</a></li>
                    <li><a href="#">Carousel Control</a></li>
                    <li><a href="#">Left & Right Navigation</a></li>
                    <li><a href="#">Four Columns Grid</a></li>
                    <li class="dropdown-header">Fonts</li>
                    <li><a href="#">Glyphicon</a></li>
                    <li><a href="#">Google Fonts</a></li>
                </ul>
            </li>
            <li class="col-sm-3">
                <ul>
                    <li class="dropdown-header">Plus</li>
                    <li><a href="#">Navbar Inverse</a></li>
                    <li><a href="#">Pull Right Elements</a></li>
                    <li><a href="#">Coloured Headers</a></li>
                    <li><a href="#">Primary Buttons & Default</a></li>
                </ul>
            </li>
            <li class="col-sm-3">
                <ul>
                    <li class="dropdown-header">Much more</li>
                    <li><a href="#">Easy to Customize</a></li>
                    <li><a href="#">Calls to action</a></li>
                    <li><a href="#">Custom Fonts</a></li>
                    <li><a href="#">Slide down on Hover</a></li>
                </ul>
            </li>
        </ul>
    </li>
@endforeach