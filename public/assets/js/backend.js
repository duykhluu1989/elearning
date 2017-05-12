$('[data-toggle="popover"]').popover({
    'trigger': 'hover'
});

$('.Confirmation').click(function() {
    return confirm('Bạn Chắc Chắn Rồi Chứ ?');
});