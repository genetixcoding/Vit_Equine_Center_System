$(document).ready(function () {
    $('#iconNavbarSidenav').on('click', function () {
        $('#sidenav-main').toggleClass('active');
        $('body').toggleClass('g-sidenav-pinned');
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#sidenav-main, #iconNavbarSidenav').length) {
            $('#sidenav-main').removeClass('active');
            $('body').removeClass('g-sidenav-pinned');
        }
    });
});

