// Código limpo para alternar as seções da barra lateral 
// com rotação adequada do ícone (sem logs)

// Encontrar todas as seções com setas
const arrowSections = document.querySelectorAll('.HvFmh');

// Função para inicializar o estado dos arrows com base no conteúdo
function initializeArrowStates() {
  arrowSections.forEach((section) => {
    // Encontrar o ícone de seta dentro da seção
    const arrow = section.querySelector('.inove-icon svg');
    if (!arrow) return;
    
    // Encontrar a seção de conteúdo correspondente
    const contentSection = section.nextElementSibling;
    if (!contentSection || !contentSection.classList.contains('overflow-hidden')) return;
    
    // Verificar o estado atual da seção (expandida ou retraída)
    const isExpanded = contentSection.classList.contains('max-h-none') && 
                      contentSection.style.display !== 'none';
    
    // Definir a rotação correta com base no estado atual
    arrow.style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
  });
}

// Observador de mutação para detectar quando o menu é reaberto
const sidebarElement = document.querySelector('.sidebar.no-scrollbar');
if (sidebarElement) {
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
        // Quando o estilo do sidebar muda (ex: translateX), verifica se está sendo reaberto
        const transform = sidebarElement.style.transform;
        if (transform === 'translateX(0px)' || transform === 'translateX(0%)' || !transform.includes('-100%')) {
          // Menu está sendo reaberto, reinicializa os estados dos arrows
          initializeArrowStates();
        }
      }
    });
  });
  
  // Configurar e iniciar o observador
  observer.observe(sidebarElement, { attributes: true, attributeFilter: ['style'] });
}

// Inicializar o comportamento de clique para cada seção
arrowSections.forEach((section) => {
  // Encontrar o ícone de seta dentro da seção
  const arrow = section.querySelector('.inove-icon svg');
  if (!arrow) return;
  
  // Encontrar a seção de conteúdo correspondente
  const contentSection = section.nextElementSibling;
  if (!contentSection || !contentSection.classList.contains('overflow-hidden')) return;
  
  // Verificar o estado inicial da seção (expandida ou retraída)
  const isExpanded = contentSection.classList.contains('max-h-none') && 
                    contentSection.style.display !== 'none';
  
  // Configurar o estilo inicial do ícone com transição suave
  arrow.style.transition = 'transform 0.3s ease';
  
  // Definir a rotação inicial correta
  arrow.style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
  
  // Tornar o cursor um ponteiro para indicar que é clicável
  section.style.cursor = 'pointer';
  
  // Adicionar o manipulador de clique
  section.onclick = function(event) {
    // Impedir que o evento se propague
    event.stopPropagation();
    
    // Verificar o estado atual da seção
    const currentlyExpanded = contentSection.classList.contains('max-h-none') && 
                             contentSection.style.display !== 'none';
    
    if (currentlyExpanded) {
      // RETRAIR: Ocultar o conteúdo
      contentSection.classList.remove('max-h-none');
      contentSection.classList.add('max-h-0');
      contentSection.style.display = 'none'; 
      
      // Rotacionar ícone para baixo (estado padrão)
      arrow.style.transform = 'rotate(0deg)';
    } else {
      // EXPANDIR: Mostrar o conteúdo
      contentSection.classList.remove('max-h-0');
      contentSection.classList.add('max-h-none');
      contentSection.style.display = 'block';
      
      // Rotacionar ícone para cima (corrigido)
      arrow.style.transform = 'rotate(180deg)';
    }
  };
});

// Inicializar o estado dos arrows no carregamento da página
document.addEventListener('DOMContentLoaded', initializeArrowStates);

// Também observe o botão que abre o menu lateral, se existir
const btnOpenSidebar = document.getElementById('btnOpenSidebar');
if (btnOpenSidebar) {
  btnOpenSidebar.addEventListener('click', () => {
    // Pequeno atraso para garantir que o menu tenha sido aberto antes de reinicializar
    setTimeout(initializeArrowStates, 100);
  });
}


