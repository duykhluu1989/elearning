<ul class="{{ $listClass }}">
    @foreach($listMenus as $listMenu)
        <li>
            <div class="input-group col-sm-7">
                <span class="form-control">{{ $listMenu->getMenuTitle() }}</span>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default EditMenuButton" id="EditMenuButton_{{ $listMenu->id }}" value="{{ action('Backend\ThemeController@editMenu', ['id' => $listMenu->id]) }}"><i class="fa fa-pencil fa-fw"></i></button>
                </span>
            </div>

            <?php
            $listMenu->load(['childrenMenus' => function($query) {
                $query->select('id', 'parent_id', 'name', 'url', 'target_id', 'target')->orderBy('position');
            }]);
            ?>

            @if(count($listMenu->childrenMenus) > 0)

                @include('backend.themes.partials.list_menu', ['listMenus' => $listMenu->childrenMenus, 'listClass' => ''])

            @endif

        </li>
    @endforeach
</ul>