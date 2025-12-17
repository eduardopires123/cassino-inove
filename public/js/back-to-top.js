document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.querySelector('.jbmAp');
    if (!backToTopButton) return;

    // Função para verificar a largura da tela e ajustar os estilos
    function adjustButtonStyles() {
        if (window.innerWidth < 1022) {
            backToTopButton.style.setProperty('--47d083a8', 'translateX(-50%)');
            backToTopButton.style.setProperty('--43dee2fa', '66.09375px');
            
            // Não adiciona mais a classe automaticamente em telas menores
            // A classe será adicionada/removida pelo handleScroll para todos os tamanhos de tela
        } else {
            backToTopButton.style.setProperty('--47d083a8', 'translateX(-25%)');
            backToTopButton.style.setProperty('--43dee2fa', '10px');
        }
    }

    // Função para verificar o scroll e adicionar/remover a classe
    function handleScroll() {
        if (window.scrollY > 100) {
            backToTopButton.classList.add('uqx9L');
        } else {
            backToTopButton.classList.remove('uqx9L');
        }
    }

    // Adiciona evento de click para rolagem suave até o topo
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Inicialização
    adjustButtonStyles();
    handleScroll();

    // Eventos
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', adjustButtonStyles);
}); 