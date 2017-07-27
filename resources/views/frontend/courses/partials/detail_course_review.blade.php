@if($courseReviews->total() > 0)
    <hr />
    <ul id="comments-list" class="comments-list">

        @foreach($courseReviews as $courseReview)
            <li>
                <div class="comment-main-level">

                    @if(!empty($courseReview->user->image))
                        <div class="comment-avatar"><img src="{{ $courseReview->user->image }}" alt="User Avatar"></div>
                    @endif

                    <div class="comment-box">
                        <div class="comment-head">
                            <h6 class="comment-name"><a href="javascript:void(0)">{{ $courseReview->user->profile->name }}</a></h6>
                            <span>{{ $courseReview->created_at }}</span>
                        </div>
                        <div class="comment-content">
                            {{ $courseReview->detail }}
                        </div>
                    </div>
                </div>
            </li>
        @endforeach

    </ul>
    <div class="row mt30 mb30">
        <div class="col-lg-12">
            <ul class="pagination pull-right">

                @if($courseReviews->lastPage() > 1)
                    @if($courseReviews->currentPage() > 1)
                        <li><a href="javascript:void(0)" data-page="{{ $courseReviews->currentPage() - 1 }}">&laquo;</a></li>
                        <li><a href="javascript:void(0)" data-page="1">1</a></li>
                    @endif

                    @for($i = 2;$i >= 1;$i --)
                        @if($courseReviews->currentPage() - $i > 1)
                            @if($courseReviews->currentPage() - $i > 2 && $i == 2)
                                <li>...</li>
                                <li><a href="javascript:void(0)" data-page="{{ $courseReviews->currentPage() - $i }}">{{ $courseReviews->currentPage() - $i }}</a></li>
                            @else
                                <li><a href="javascript:void(0)" data-page="{{ $courseReviews->currentPage() - $i }}">{{ $courseReviews->currentPage() - $i }}</a></li>
                            @endif
                        @endif
                    @endfor

                    <li class="active"><a href="javascript:void(0)">{{ $courseReviews->currentPage() }}</a></li>

                    @for($i = 1;$i <= 2;$i ++)
                        @if($courseReviews->currentPage() + $i < $courseReviews->lastPage())
                            @if($courseReviews->currentPage() + $i < $courseReviews->lastPage() - 1 && $i == 2)
                                <li><a href="javascript:void(0)" data-page="{{ $courseReviews->currentPage() + $i }}">{{ $courseReviews->currentPage() + $i }}</a></li>
                                <li>...</li>
                            @else
                                <li><a href="javascript:void(0)" data-page="{{ $courseReviews->currentPage() + $i }}">{{ $courseReviews->currentPage() + $i }}</a></li>
                            @endif
                        @endif
                    @endfor

                    @if($courseReviews->currentPage() < $courseReviews->lastPage())
                        <li><a href="javascript:void(0)" data-page="{{ $courseReviews->lastPage() }}">{{ $courseReviews->lastPage() }}</a></li>
                        <li><a href="javascript:void(0)" data-page="{{ $courseReviews->currentPage() + 1 }}">&raquo;</a></li>
                    @endif
                @endif

            </ul>
        </div>
    </div>
@endif