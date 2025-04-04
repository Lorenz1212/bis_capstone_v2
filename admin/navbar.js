$(document).ready(function(){
    $('.navbar-menu ul li a').click(function(){
        $('.navbar-menu ul li a').removeClass('active');
        $(this).addClass('active');
    });
});
