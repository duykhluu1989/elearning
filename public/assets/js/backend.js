$('[data-toggle="popover"]').popover({
    'trigger': 'hover'
});

$('.Confirmation').click(function() {
    return confirm('Bạn Chắc Chắn Rồi Chứ ?');
});

$('.DatePicker').datepicker({
    changeYear: true,
    changeMonth: true,
    dateFormat: 'yy-mm-dd'
});