// Script ultra simplificado para navegação dos sliders
document.addEventListener('DOMContentLoaded', function() {
  // Estilo para esconder scrollbars e adicionar responsividade
  const style = document.createElement('style');
  style.textContent = `
    .rpneC, .-JVa3 {
      -ms-overflow-style: none;
      scrollbar-width: none;
      overflow-x: auto;
    }
    .rpneC::-webkit-scrollbar, .-JVa3::-webkit-scrollbar {
      display: none;
    }
    
    /* Estilos responsivos para os sliders */
    @media (min-width: 1201px) {
      .nM44t {
        --d879e6ea: 22px !important; 
        --45b10934: 6 !important;
      }
      .nM44t .-JVa3 {
        --620ba053: calc((100% - 110px) / 6) !important;
        --063993a6: 22px !important;
        --8ec19218: calc((100% - 110px) / 6) !important;
        --543ef9ea: 0 !important;
      }
      .nM44t .-JVa3.Vulse.EEtS9 {
        --620ba053: calc((100% - 110px) / 6) !important;
        --063993a6: 22px !important;
        --8ec19218: calc((100% - 110px) / 6) !important;
        --543ef9ea: 0 !important;
      }
    }
    
    @media (min-width: 1101px) and (max-width: 1200px) {
      .nM44t {
        --d879e6ea: 22px !important;
        --45b10934: 5 !important;
      }
      .nM44t .-JVa3 {
        --620ba053: calc((100% - 88px) / 5) !important;
        --063993a6: 22px !important;
        --8ec19218: calc((100% - 88px) / 5) !important;
        --543ef9ea: 0 !important;
      }
      .nM44t .-JVa3.Vulse.EEtS9 {
        --620ba053: calc((100% - 88px) / 5) !important;
        --063993a6: 22px !important;
        --8ec19218: calc((100% - 88px) / 5) !important;
        --543ef9ea: 0 !important;
      }
    }
    
    @media (min-width: 768px) and (max-width: 1100px) {
      .nM44t {
        --d879e6ea: 22px !important;
        --45b10934: 4 !important;
      }
      .nM44t .-JVa3 {
        --620ba053: calc((100% - 48px) / 4) !important;
        --063993a6: 16px !important;
        --8ec19218: calc((100% - 48px) / 4) !important;
        --543ef9ea: 0 !important;
      }
      
      /* Classes específicas para telas médias */
      .nM44t div.-JVa3 {
        --620ba053: calc((100% - 48px) / 4) !important;
        --063993a6: 16px !important;
        --8ec19218: calc((100% - 48px) / 4) !important;
        --543ef9ea: 0 !important;
      }
      html body .nM44t div.-JVa3 {
        --620ba053: calc((100% - 48px) / 4) !important;
        --063993a6: 16px !important;
        --8ec19218: calc((100% - 48px) / 4) !important;
        --543ef9ea: 0 !important;
      }
      
      /* Forçar classes para telas médias */
      html body .nM44t div.-JVa3:not(.Vulse):not(.EEtS9),
      html body .nM44t div.-JVa3.Vulse:not(.EEtS9),
      html body .nM44t div.-JVa3:not(.Vulse).EEtS9,
      html body .nM44t div.-JVa3.Vulse.EEtS9 {
        --620ba053: calc((100% - 48px) / 4) !important;
        --063993a6: 16px !important;
        --8ec19218: calc((100% - 48px) / 4) !important;
        --543ef9ea: 0 !important;
      }
    }
    
    @media (max-width: 767px) {
      .nM44t {
        --d879e6ea: 10px !important;
        --45b10934: 3 !important;
      }
      .nM44t .-JVa3 {
        --620ba053: calc((100% - 20px) / 3) !important;
        --063993a6: 10px !important;
        --8ec19218: calc((100% - 20px) / 3) !important;
        --543ef9ea: 0 !important;
      }
      
      /* Classes específicas para telas pequenas */
      .nM44t div.-JVa3 {
        --620ba053: calc((100% - 20px) / 3) !important;
        --063993a6: 10px !important;
        --8ec19218: calc((100% - 20px) / 3) !important;
        --543ef9ea: 0 !important;
      }
      html body .nM44t div.-JVa3 {
        --620ba053: calc((100% - 20px) / 3) !important;
        --063993a6: 10px !important;
        --8ec19218: calc((100% - 20px) / 3) !important;
        --543ef9ea: 0 !important;
      }
      
      /* Forçar classes para telas pequenas */
      html body .nM44t div.-JVa3:not(.Vulse):not(.EEtS9),
      html body .nM44t div.-JVa3.Vulse:not(.EEtS9),
      html body .nM44t div.-JVa3:not(.Vulse).EEtS9,
      html body .nM44t div.-JVa3.Vulse.EEtS9 {
        --620ba053: calc((100% - 20px) / 3) !important;
        --063993a6: 10px !important;
        --8ec19218: calc((100% - 20px) / 3) !important;
        --543ef9ea: 0 !important;
      }
    }
  `;
  document.head.appendChild(style);

  // Função simples para habilitar navegação com botões
  function setupNavButtons(container, prevBtn, nextBtn) {
    if (!container || !prevBtn || !nextBtn) return;
    
    const scrollAmount = Math.floor(container.offsetWidth * 0.8);
    
    prevBtn.addEventListener('click', function() {
      container.scrollBy({
        left: -scrollAmount,
        behavior: 'smooth'
      });
    });
    
    nextBtn.addEventListener('click', function() {
      container.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
      });
    });
  }
  
  // Menu de ícones no topo
  const iconesMenu = document.querySelector('#divMenuHighlight .-JVa3');
  if (iconesMenu) {
    iconesMenu.style.cursor = 'grab';
  }
  
  // Mais Pagou Hoje
  const maisPagouBlock = document.querySelector('.nM44t .row_imgs');
  if (maisPagouBlock) {
    const container = maisPagouBlock.querySelector('.rpneC');
    const section = maisPagouBlock.closest('.nM44t');
    
    if (container && section) {
      const buttons = section.querySelectorAll('.nQro9');
      if (buttons.length >= 2) {
        setupNavButtons(container, buttons[0], buttons[1]);
      }
      container.style.cursor = 'grab';
    }
  }
  
  // Sliders com ID específico
  ['#providerSlider', '#liveGamesSlider', '#newGamesSlider', '#topGamesGamesSlider'].forEach(id => {
    const slider = document.querySelector(id);
    if (!slider) return;
    
    const container = slider.querySelector('.rpneC');
    const section = slider.closest('.nM44t');
    
    if (container && section) {
      const buttons = section.querySelectorAll('.nQro9');
      if (buttons.length >= 2) {
        setupNavButtons(container, buttons[0], buttons[1]);
      }
      container.style.cursor = 'grab';
    }
  });
  
  // Sliders de provedores específicos
  document.querySelectorAll('[id^="providerSlider"]').forEach(slider => {
    if (!slider || slider.id === 'providerSlider') return;
    
    const container = slider.querySelector('.rpneC');
    const section = slider.closest('.nM44t');
    
    if (container && section) {
      const buttons = section.querySelectorAll('.nQro9');
      if (buttons.length >= 2) {
        setupNavButtons(container, buttons[0], buttons[1]);
      }
      container.style.cursor = 'grab';
    }
  });
  
  // Outros sliders genéricos
  document.querySelectorAll('.nM44t .-JVa3.Vulse').forEach(slider => {
    const container = slider.querySelector('.rpneC');
    const section = slider.closest('.nM44t');
    
    if (container && section) {
      const buttons = section.querySelectorAll('.nQro9');
      if (buttons.length >= 2) {
        setupNavButtons(container, buttons[0], buttons[1]);
      }
      container.style.cursor = 'grab';
    }
  });
});
