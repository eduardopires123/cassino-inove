// Configuração inicial ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    // Open Mail Sidebar on resolution below or equal to 991px.
    $('.mail-menu').on('click', function(e){
        $(this).parents('.mail-box-container').children('.tab-title').addClass('mail-menu-show')
        $(this).parents('.mail-box-container').children('.mail-overlay').addClass('mail-overlay-show')
    })

    // Close sidebar when clicked on overlay (and overlay itself).
    $('.mail-overlay').on('click', function(e){
        $(this).parents('.mail-box-container').children('.tab-title').removeClass('mail-menu-show')
        $(this).removeClass('mail-overlay-show')
    })

    // Close sidebar when clicking on any nav link (for mobile views)
    $('.tab-title .nav-pills a.nav-link').on('click', function(event) {
        $(this).parents('.mail-box-container').find('.tab-title').removeClass('mail-menu-show')
        $(this).parents('.mail-box-container').find('.mail-overlay').removeClass('mail-overlay-show')
    })
});