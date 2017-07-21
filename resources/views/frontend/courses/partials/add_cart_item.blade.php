@push('scripts')
    <script type="text/javascript">
        $('#page').on('click', 'a', function() {
            if($(this).hasClass('AddCartItem') || $(this).hasClass('QuickBuyCourse'))
            {
                if($(this).data('course-id') != '')
                {
                    var elem = $(this);

                    $.ajax({
                        url: '{{ action('Frontend\OrderController@addCartItem') }}',
                        type: 'get',
                        data: 'course_id=' + $(this).data('course-id'),
                        success: function(result) {
                            if(result)
                            {
                                if(elem.hasClass('QuickBuyCourse'))
                                    location.href = '{{ action('Frontend\OrderController@editCart') }}';
                                else
                                {
                                    $('#CartDetail').html(result);

                                    swal({
                                        title: '@lang('theme.add_cart')',
                                        type: 'success',
                                        confirmButtonClass: 'btn-success'
                                    });
                                }
                            }
                            else
                            {
                                swal({
                                    title: '@lang('theme.system_error')',
                                    type: 'error',
                                    confirmButtonClass: 'btn-success'
                                });
                            }
                        }
                    });
                }
            }
        });
    </script>
@endpush