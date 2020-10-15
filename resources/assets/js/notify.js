$(document).ready(function() {
    var $message = $('.notify').attr('data');
    if ($('div').hasClass('success')) {
        toastr.success(
            $message,
            '',
            {
                positionClass: 'toast-bottom-right',
                timeOut: 5000
            }
        )
    }
    if ($('div').hasClass('error')) {
        toastr.error(
            $message,
            '',
            {
                positionClass: 'toast-bottom-right',
                timeOut: 5000
            }
        )
    }
    // if ($('div').hasClass('warning')) {
    //     toastr.warning(
    //         $message,
    //         '',
    //         {
    //             positionClass: 'toast-bottom-right',
    //             timeOut: 5000000
    //         }
    //     )
    // }
})
