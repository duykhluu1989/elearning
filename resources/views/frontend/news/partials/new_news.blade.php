@foreach($newNews as $item)
    <article>
        <div class="row">
            <div class="col-xs-12">
                <a href="{{ $item['link'] }}">
                    <h4>{{ $item['title'] }}</h4>
                </a>
                <?php
                echo $item['description'];
                ?>
            </div>
        </div>
    </article>
@endforeach