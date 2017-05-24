$('[data-toggle="popover"]').popover({
    'trigger': 'hover'
});

$('.Confirmation').click(function(event) {
    if(confirm('Bạn Chắc Chắn Rồi Chứ ?'))
        return true;
    else
    {
        event.stopImmediatePropagation();
        event.preventDefault();
        return false;
    }
});

$('.DatePicker').datepicker({
    changeYear: true,
    changeMonth: true,
    dateFormat: 'yy-mm-dd'
});