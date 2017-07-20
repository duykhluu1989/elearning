@push('scripts')
    <script type="text/javascript">
        $('#page').on('click', 'a', function() {
            if($(this).hasClass('AddCartItem'))
            {
                if($(this).data('course-id') != '')
                {
                    $.ajax({
                        url: '{{ action('Frontend\OrderController@addCartItem') }}',
                        type: 'get',
                        data: 'course_id=' + $(this).data('course-id'),
                        success: function(result) {
                            if(result)
                            {
                                $('#CartDetail').html(result);

                                swal({
                                    title: '@lang('theme.add_cart')',
                                    type: 'success',
                                    confirmButtonClass: 'btn-success'
                                });
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