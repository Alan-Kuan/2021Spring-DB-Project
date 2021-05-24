$(document).ready(function() {

    $('#cancel-selected').click(function(e) {

        e.preventDefault();

        if($('.order-check:checked').length === 0) {
            alert('沒有選擇任何訂單');
            return;
        }

        let form = $(this).parent();

        $('.order-check:checked').each(function() {
            let input = $(`<input type="hidden" name="OIDs[]" value="${ $(this).attr('data-OID') }"/>`);
            form.append(input);
        });

        form.submit();

    });

});
