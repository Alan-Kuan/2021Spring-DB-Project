$(document).ready(function() {

    $('#query-output th:not(#order_mask)').click(function() {

        let item = $(this).attr('id');
        let status = $('#sort-' + item).val();

        switch(status) {
        case 'no-sort':
            $('#sort-' + item).val('asc');
            break;
        case 'asc':
            $('#sort-' + item).val('desc');
            break;
        case 'desc':
            $('#sort-' + item).val('no-sort');
            break;
        }

        $('#search-shop').submit();

    });

});
