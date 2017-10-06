@if($courseQuestions->total() > 0)
    <hr />
    <ul id="comments-list" class="comments-list">

        @foreach($courseQuestions as $courseQuestion)
            <li>
                <div class="comment-main-level">

                    @if(!empty($courseQuestion->user->avatar))
                        <div class="comment-avatar"><img src="{{ $courseQuestion->user->avatar }}" alt="User Avatar"></div>
                    @endif

                    <div class="comment-box">
                        <div class="comment-head">
                            <span>{{ $courseQuestion->created_at }}</span>
                        </div>
                        <div class="comment-content">
                            {{ $courseQuestion->question }}
                        </div>
                    </div>
                </div>
                <ul class="comments-list reply-list">
                    <li>

                        @if(!empty($courseQuestion->course->user->avatar))
                            <div class="comment-avatar"><img src="{{ $courseQuestion->course->user->avatar }}" alt="Teacher Avatar"></div>
                        @endif

                        <div class="comment-box">
                            <div class="comment-head">
                                <h6 class="comment-name"><a href="javascript:void(0)">{{ $courseQuestion->course->user->profile->name }}</a></h6>
                                <span>{{ $courseQuestion->created_at }}</span>
                                <i class="fa fa-reply"></i>
                            </div>
                            <div class="comment-content">
                                {{ $courseQuestion->answer }}
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
        @endforeach

    </ul>
    <div class="row mt30 mb30">
        <div class="col-lg-12">
            <ul class="pagination pull-right">

                @if($courseQuestions->lastPage() > 1)
                    @if($courseQuestions->currentPage() > 1)
                        <li><a href="javascript:void(0)" data-page="{{ $courseQuestions->currentPage() - 1 }}">&laquo;</a></li>
                        <li><a href="javascript:void(0)" data-page="1">1</a></li>
                    @endif

                    @for($i = 2;$i >= 1;$i --)
                        @if($courseQuestions->currentPage() - $i > 1)
                            @if($courseQuestions->currentPage() - $i > 2 && $i == 2)
                                <li>...</li>
                                <li><a href="javascript:void(0)" data-page="{{ $courseQuestions->currentPage() - $i }}">{{ $courseQuestions->currentPage() - $i }}</a></li>
                            @else
                                <li><a href="javascript:void(0)" data-page="{{ $courseQuestions->currentPage() - $i }}">{{ $courseQuestions->currentPage() - $i }}</a></li>
                            @endif
                        @endif
                    @endfor

                    <li class="active"><a href="javascript:void(0)">{{ $courseQuestions->currentPage() }}</a></li>

                    @for($i = 1;$i <= 2;$i ++)
                        @if($courseQuestions->currentPage() + $i < $courseQuestions->lastPage())
                            @if($courseQuestions->currentPage() + $i < $courseQuestions->lastPage() - 1 && $i == 2)
                                <li><a href="javascript:void(0)" data-page="{{ $courseQuestions->currentPage() + $i }}">{{ $courseQuestions->currentPage() + $i }}</a></li>
                                <li>...</li>
                            @else
                                <li><a href="javascript:void(0)" data-page="{{ $courseQuestions->currentPage() + $i }}">{{ $courseQuestions->currentPage() + $i }}</a></li>
                            @endif
                        @endif
                    @endfor

                    @if($courseQuestions->currentPage() < $courseQuestions->lastPage())
                        <li><a href="javascript:void(0)" data-page="{{ $courseQuestions->lastPage() }}">{{ $courseQuestions->lastPage() }}</a></li>
                        <li><a href="javascript:void(0)" data-page="{{ $courseQuestions->currentPage() + 1 }}">&raquo;</a></li>
                    @endif
                @endif

            </ul>
        </div>
    </div>
@endif