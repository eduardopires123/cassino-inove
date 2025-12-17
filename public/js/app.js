document.addEventListener('DOMContentLoaded', function() {
  // Encontre todos os cabeçalhos
  const headers = document.querySelectorAll('header#divPageHeader');
  
  // Se houver mais de um, remova os extras
  if (headers.length > 1) {
      for (let i = 1; i < headers.length; i++) {
          headers[i].remove();
      }
  }
  
  // Função para adicionar efeito hover nos jogos
  function setupGameHoverEffects() {
      // Selecionar todos os elementos de jogos na página
      const gameElements = document.querySelectorAll('.u3Qxq');
      
      // Adicionar eventos de mouse para cada jogo
      gameElements.forEach(game => {
          // Quando o mouse entra no elemento
          game.addEventListener('mouseenter', function() {
              this.classList.add('TmffS');
          });
          
          // Quando o mouse sai do elemento
          game.addEventListener('mouseleave', function() {
              this.classList.remove('TmffS');
          });
      });
  }
  
  // Executar a configuração inicial
  setupGameHoverEffects();
  
  // Configurar um observador de mutações para capturar jogos adicionados dinamicamente
  const observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
          if (mutation.addedNodes.length) {
              setupGameHoverEffects();
          }
      });
  });
  
  // Iniciar a observação do DOM para jogos adicionados dinamicamente
  observer.observe(document.body, {
      childList: true,
      subtree: true
  });
});


// Função para gerenciar o comportamento da sidebar
function initSidebarControl() {
// Variável para controlar o estado do menu (aberto/fechado)
let isMenuOpen = true;
// Variável para controlar o estado do topBar (aberto/fechado)
let isTopBarOpen = true;

// Função para ajustar classes conforme condições
function adjustClasses() {
  // Verifica se a largura da tela é menor que 1024px
  const isMobile = window.innerWidth < 1024;
  
  // Se for mobile, fechar o menu automaticamente
  if (isMobile && isMenuOpen) {
    isMenuOpen = false;
    
    // Atualizar o localStorage
    localStorage.setItem('sidebarOpen', 'false');
  }
  
  // Seleciona os elementos pelo nome da classe
  const element5vWEW = document.querySelector('._5vWEW');
  const element531PZ = document.querySelector('._531PZ');
  const elementIO8Xi = document.querySelector('.IO8Xi');
  const sidebarElement = document.querySelector('.sidebar.no-scrollbar');
  const divSidebarMenu = document.getElementById('divSidebarMenu');
  const elementDYW8 = document.querySelector('.d-yW8');
  const elementmrauto = document.querySelector('.mr-auto');
  
  // Novos elementos adicionados
  const elementAu03f = document.querySelector('.Au03f');
  const elementJbmAp = document.querySelector('.jbmAp');
  const elementsRcNcf = document.querySelectorAll('.RcNcf');
  const elementsPZd1o = document.querySelectorAll('.pZd1o');
  
  // Seleciona TODOS os elementos com a classe sdAXM (itens do menu)
  const elementssdAXM = document.querySelectorAll('.sdAXM');

  // Ajusta o estilo do divSidebarMenu baseado principalmente no estado do topBar
  // A variável menu (aberto/fechado) não deve afetar o valor de --7e9dc732
  if (divSidebarMenu) {
    if (isMobile) {
      // Se for mobile, mantenha o estilo padrão para mobile
      divSidebarMenu.setAttribute('style', '--7e9dc732: 0px; --372e3822: 56.09375px;');
    } else {
      // No desktop, apenas o estado do topBar influencia o valor de --7e9dc732
      if (isTopBarOpen) {
        // Se o topBar estiver aberto
        divSidebarMenu.setAttribute('style', '--7e9dc732: 105px; --372e3822: 0px;');
      } else {
        // Se o topBar estiver fechado
        divSidebarMenu.setAttribute('style', '--7e9dc732: 65px; --372e3822: 0px;');
      }
    }
  }

  // Modificação: Selecionar TODOS os elementos com classe mr-auto
  const elementsWithMrAuto = document.querySelectorAll('.mr-auto');
  
  // Selecionar os ícones angle-down (setas) para ocultar quando o menu estiver recolhido
  const angleDownIcons = document.querySelectorAll('.angle-down');

  // Se menu estiver fechado, aplicar as classes de fechado
  if (!isMenuOpen) {
    // Adicionar classes
    if (element5vWEW && !element5vWEW.classList.contains('undefined')) {
      element5vWEW.classList.add('undefined');
    }
    
    if (element531PZ && !element531PZ.classList.contains('xYg0Z')) {
      element531PZ.classList.add('xYg0Z');
    }
    
    if (elementIO8Xi && !elementIO8Xi.classList.contains('EA7bU')) {
      elementIO8Xi.classList.add('EA7bU');
    }

    // Quando fechado, adicionar classe zU-ks a TODOS os elementos com classe sdAXM
    elementssdAXM.forEach(element => {
      if (!element.classList.contains('zU-ks')) {
        element.classList.add('zU-ks');
      }
    });
    
    // Remover classe open do sidebar - garantindo que funcione no mobile também
    if (sidebarElement) {
      if (sidebarElement.classList.contains('open')) {
        sidebarElement.classList.remove('open');
      }
    }
    
    // Mudar de p-4 para p-3
    if (elementDYW8) {
      if (elementDYW8.classList.contains('p-4')) {
        elementDYW8.classList.remove('p-4');
        elementDYW8.classList.add('p-3');
      }
    }

    // Ocultar TODOS os elementos com classe mr-auto quando o menu estiver fechado
    if (elementsWithMrAuto && elementsWithMrAuto.length > 0) {
      // Apenas manipular elementos mr-auto quando a resolução for 1020px ou maior
      if (window.innerWidth >= 1020) {
        elementsWithMrAuto.forEach(element => {
          if (!element.classList.contains('hidden')) {
            element.classList.add('hidden');
          }
        });
      }
    }
    
    // Ocultar os ícones de seta (angle-down) quando o menu estiver fechado
    if (angleDownIcons && angleDownIcons.length > 0) {
      // Apenas manipular ícones angle-down quando a resolução for 1020px ou maior
      if (window.innerWidth >= 1020) {
        angleDownIcons.forEach(icon => {
          if (!icon.classList.contains('hidden')) {
            icon.classList.add('hidden');
          }
        });
      }
    }
    
    // NOVAS MODIFICAÇÕES - MENU FECHADO
    
    // Elemento Au03f - Remover ZsSUH quando fechado
    if (elementAu03f) {
      if (elementAu03f.classList.contains('ZsSUH')) {
        elementAu03f.classList.remove('ZsSUH');
      }
    }
    
    // Elementos RcNcf - Remover ntdfP quando fechado
    if (elementsRcNcf && elementsRcNcf.length > 0) {
      elementsRcNcf.forEach(element => {
        if (element.classList.contains('ntdfP')) {
          element.classList.remove('ntdfP');
        }
      });
    }
    
    // Elementos pZd1o - Remover ntdfP quando fechado
    if (elementsPZd1o && elementsPZd1o.length > 0) {
      elementsPZd1o.forEach(element => {
        if (element.classList.contains('ntdfP')) {
          element.classList.remove('ntdfP');
        }
      });
    }
    
  } else {
    // Se não for mobile E menu estiver aberto, remover as classes
    if (element5vWEW && element5vWEW.classList.contains('undefined')) {
      element5vWEW.classList.remove('undefined');
    }
    
    if (element531PZ && element531PZ.classList.contains('xYg0Z')) {
      element531PZ.classList.remove('xYg0Z');
    }

    if (elementIO8Xi && elementIO8Xi.classList.contains('EA7bU')) {
      elementIO8Xi.classList.remove('EA7bU');
    }

    // Quando aberto, remover classe zU-ks de TODOS os elementos, mantendo apenas sdAXM
    elementssdAXM.forEach(element => {
      if (element.classList.contains('zU-ks')) {
        element.classList.remove('zU-ks');
      }
    });
    
    // Adicionar classe open ao sidebar - garantindo que funcione no mobile também
    if (sidebarElement) {
      if (!sidebarElement.classList.contains('open')) {
        sidebarElement.classList.add('open');
      }
    }
    
    // Mudar de p-3 para p-4
    if (elementDYW8) {
      if (elementDYW8.classList.contains('p-3')) {
        elementDYW8.classList.remove('p-3');
        elementDYW8.classList.add('p-4');
      }
    }

    // Mostrar TODOS os elementos com classe mr-auto quando o menu estiver aberto
    if (elementsWithMrAuto && elementsWithMrAuto.length > 0) {
      // Apenas manipular elementos mr-auto quando a resolução for 1020px ou maior
      if (window.innerWidth >= 1020) {
        elementsWithMrAuto.forEach(element => {
          if (element.classList.contains('hidden')) {
            element.classList.remove('hidden');
          }
        });
      }
    }
    
    // Mostrar os ícones de seta (angle-down) quando o menu estiver aberto
    if (angleDownIcons && angleDownIcons.length > 0) {
      // Apenas manipular ícones angle-down quando a resolução for 1020px ou maior
      if (window.innerWidth >= 1020) {
        angleDownIcons.forEach(icon => {
          if (icon.classList.contains('hidden')) {
            icon.classList.remove('hidden');
          }
        });
      }
    }
    
    // NOVAS MODIFICAÇÕES - MENU ABERTO
    
    // Elemento Au03f - Adicionar ZsSUH quando aberto
    if (elementAu03f) {
      if (!elementAu03f.classList.contains('ZsSUH')) {
        elementAu03f.classList.add('ZsSUH');
      }
    }
    
    // Elementos RcNcf - Adicionar ntdfP quando aberto
    if (elementsRcNcf && elementsRcNcf.length > 0) {
      elementsRcNcf.forEach(element => {
        if (!element.classList.contains('ntdfP')) {
          element.classList.add('ntdfP');
        }
      });
    }
    
    // Elementos pZd1o - Adicionar ntdfP quando aberto
    if (elementsPZd1o && elementsPZd1o.length > 0) {
      elementsPZd1o.forEach(element => {
        if (!element.classList.contains('ntdfP')) {
          element.classList.add('ntdfP');
        }
      });
    }
  }
  
  // Elemento jbmAp - Manter o estilo independente do estado do menu
  // Este elemento sempre mantém o mesmo estilo conforme solicitado
  if (elementJbmAp && !elementJbmAp.hasAttribute('style')) {
    elementJbmAp.setAttribute('style', '--47d083a8: translateX(47.5%); --43dee2fa: 10px;');
  }
}

// Função para alternar o estado do menu quando o botão for clicado
function toggleMenu() {
  isMenuOpen = !isMenuOpen;
  
  // Força a atualização visual imediatamente
  const sidebarElement = document.querySelector('.sidebar.no-scrollbar');
  if (sidebarElement) {
    if (isMenuOpen) {
      sidebarElement.classList.add('open');
    } else {
      sidebarElement.classList.remove('open');
    }
  }
  
  // Atualiza outras classes e estilos
  adjustClasses();
  
  // Registrar o estado no localStorage para persistência
  localStorage.setItem('sidebarOpen', isMenuOpen);
}

// Função para fechar o topBar
function closeTopBar() {
  isTopBarOpen = false;
  
  // Ocultar o divTopBar
  const divTopBar = document.getElementById('divTopBar');
  if (divTopBar) {
    divTopBar.style.display = 'none';
  }
  
  // Salvar em cookie com um tempo de expiração longo
  const date = new Date();
  date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000)); // 1 ano
  document.cookie = "topbar_closed=true; expires=" + date.toUTCString() + "; path=/; SameSite=Lax";
  
  // Adicionar a classe topbar-closed ao html
  document.documentElement.classList.add('topbar-closed');
  
  // Atualizar a variável CSS root
  document.documentElement.style.setProperty('--sidebar-top-value', '65px');
  
  // Atualizar as classes e estilos
  adjustClasses();
}

// Função para configurar o estado da sidebar
function setupSidebarState() {
  // Verificar se é versão desktop (largura >= 1024px)
  const isDesktop = window.innerWidth >= 1024;
  
  // Verificar se a página atual é uma página de esportes
  const currentUrl = window.location.href.toLowerCase();
  const isEsportesPage = currentUrl.includes('/esportes') || 
                         currentUrl.includes('/sports') || 
                         currentUrl.includes('/en/esportes');
  
  if (isDesktop) {
    // Na versão desktop
    if (isEsportesPage) {
      // Para páginas de esportes, iniciar com sidebar fechado
      isMenuOpen = false;
      localStorage.setItem('sidebarOpen', 'false');
    } else {
      // Para outras páginas, manter comportamento original (aberto)
      isMenuOpen = true;
    }
  } else {
    // Se for mobile (tela <= 1024px), sempre iniciar fechado
    isMenuOpen = false;
    localStorage.setItem('sidebarOpen', 'false');
  }
  
  // Verificar se o topbar foi fechado anteriormente (cookie)
  const topbarClosed = document.cookie.split(';').some(item => item.trim().startsWith('topbar_closed=true'));
  const divTopBar = document.getElementById('divTopBar');
  
  if (topbarClosed) {
    // Se o cookie indica que foi fechado, considerar fechado
    isTopBarOpen = false;
    if (divTopBar) {
      divTopBar.style.display = 'none';
    }
  } else {
    // Se não há cookie, verificar se o topbar está visível
    if (divTopBar) {
      const computedStyle = window.getComputedStyle(divTopBar);
      isTopBarOpen = computedStyle.display !== 'none' && divTopBar.offsetParent !== null;
    } else {
      // Se não existe o elemento, considerar fechado
      isTopBarOpen = false;
    }
  }
  
  // Aplica o estado inicial
  adjustClasses();
}

// Configurar os botões de toggle da sidebar
function setupToggleButtons() {
  // Buscar todos os possíveis botões de toggle, incluindo o botão mobile específico
  const toggleButtons = document.querySelectorAll('.sidebar-toggle, .text-2xl.text-header-texts, .btn.text-2xl.text-header-texts, [data-v-45eef9f5].btn.text-2xl.text-header-texts');
  
  // Adicionar evento de clique a todos os botões encontrados
  toggleButtons.forEach(button => {
    if (button) {
      // Remover handlers existentes para evitar duplicações
      button.removeEventListener('click', toggleMenu);
      // Adicionar novo handler
      button.addEventListener('click', toggleMenu);
    }
  });
  
  // Buscar especificamente o botão mobile com SVG interno
  const mobileMenuButton = document.querySelector('[data-v-45eef9f5].btn.text-2xl.text-header-texts');
  if (mobileMenuButton) {
    mobileMenuButton.removeEventListener('click', toggleMenu);
    mobileMenuButton.addEventListener('click', toggleMenu);
  }
}

// Configurar o botão de fechar o topBar
function setupTopBarCloseButton() {
  const closeTopBarButton = document.querySelector('#divTopBar .KiosR');
  
  // Adicionar evento de clique ao botão de fechar o topBar
  if (closeTopBarButton) {
    // Remover handlers existentes para evitar duplicações
    closeTopBarButton.removeEventListener('click', closeTopBar);
    // Adicionar novo handler
    closeTopBarButton.addEventListener('click', closeTopBar);
  }
}

// Executar configuração inicial do estado da sidebar
setupSidebarState();

// Configurar os botões
setupToggleButtons();
setupTopBarCloseButton();

// Executar ajuste de classes quando a janela for redimensionada
window.addEventListener('resize', adjustClasses);

// Configurar botões periodicamente para garantir que estejam sempre funcionando
// Útil para SPAs que podem recriar elementos
setInterval(() => {
  setupToggleButtons();
  setupTopBarCloseButton();
}, 2000); // Verificar a cada 2 segundos

// Retornar funções públicas para uso externo
return {
  toggleMenu,
  closeTopBar,
  adjustClasses,
  isMenuOpen: function() { return isMenuOpen; },
  setMenuOpen: function(value) { isMenuOpen = value; },
  isTopBarOpen: function() { return isTopBarOpen; }
};
}

// Inicializar o controle da sidebar quando o DOM estiver completamente carregado
let sidebarControl;
document.addEventListener('DOMContentLoaded', () => {
sidebarControl = initSidebarControl();
// Expor globalmente para uso em outros scripts
window.sidebarControl = sidebarControl;

// Aplicar o estado novamente após um pequeno delay 
// para garantir que as classes foram aplicadas corretamente
setTimeout(() => {
  if (sidebarControl && typeof sidebarControl.adjustClasses === 'function') {
    sidebarControl.adjustClasses();
  }
}, 100);

// SOLUÇÃO FINAL PARA O BOTÃO MOBILE
// Adicionar este código diretamente no documento antes de qualquer outro script

// Adicionar um script inline no documento que será executado imediatamente
const directScript = document.createElement('script');
directScript.textContent = `
  // Funções para controlar o menu mobile de forma independente
  function openSidebar() {
    const sidebar = document.querySelector('.sidebar.no-scrollbar');
    if (sidebar) {
      sidebar.classList.add('open');
      sidebar.setAttribute('data-isopen', 'true');
      sidebar.style.display = 'block';
      document.body.classList.add('sidebar-open');
    }
  }
  
  function closeSidebar() {
    const sidebar = document.querySelector('.sidebar.no-scrollbar');
    if (sidebar) {
      sidebar.classList.remove('open');
      sidebar.setAttribute('data-isopen', 'false');
      document.body.classList.remove('sidebar-open');
    }
  }
  
  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar.no-scrollbar');
    if (sidebar && (sidebar.classList.contains('open') || sidebar.getAttribute('data-isopen') === 'true')) {
      closeSidebar();
    } else {
      openSidebar();
    }
  }
  
  // Expor estas funções globalmente
  window.openSidebar = openSidebar;
  window.closeSidebar = closeSidebar;
  window.toggleSidebar = toggleSidebar;
  
  // Configurar para interceptar o clique no botão antes de qualquer outro handler
  document.addEventListener('click', function(e) {
    if (e.target && (
        e.target.matches('button[data-v-45eef9f5]') || 
        e.target.closest('button[data-v-45eef9f5]')
    )) {
      e.preventDefault();
      e.stopPropagation();
      toggleSidebar();
      return false;
    }
  }, true);
`;

// Adicionar o script no início do documento para garantir máxima prioridade
document.head.insertBefore(directScript, document.head.firstChild);

// Adicionar interceptor via mutation observer
const mobileButtonObserver = new MutationObserver(function(mutations) {
  mutations.forEach(function(mutation) {
    if (mutation.addedNodes.length) {
      // Procurar por botões recém-adicionados
      mutation.addedNodes.forEach(function(node) {
        if (node.nodeType === 1) { // ELEMENT_NODE
          const buttons = node.matches('button[data-v-45eef9f5]') ? 
            [node] : node.querySelectorAll('button[data-v-45eef9f5]');
          
          if (buttons.length) {
            buttons.forEach(button => {
              // Substituir por clone para remover todos os listeners
              const newButton = button.cloneNode(true);
              button.parentNode.replaceChild(newButton, button);
              
              // Adicionar nosso handler diretamente no HTML
              newButton.setAttribute('onclick', "event.preventDefault(); event.stopPropagation(); window.toggleSidebar(); return false;");
            });
          }
        }
      });
    }
  });
});

// Observar adições ao DOM
mobileButtonObserver.observe(document.body, {
  childList: true,
  subtree: true
});

// Detectar quando o documento estiver pronto
if (document.readyState === 'complete' || document.readyState === 'interactive') {
  // Adicionar handler inline no botão
  const menuButtons = document.querySelectorAll('button[data-v-45eef9f5]');
  menuButtons.forEach(button => {
    button.setAttribute('onclick', "event.preventDefault(); event.stopPropagation(); window.toggleSidebar(); return false;");
  });
}
});

// Adicione esta função no seu arquivo JavaScript principal
function loadAccountPage(url) {
  fetch(url)
      .then(response => response.text())
      .then(html => {
          // Atualiza o conteúdo da página
          document.querySelector('#main-content').innerHTML = html;
          
          // Dispara o evento personalizado para inicializar a página
          document.dispatchEvent(new Event('account-page-loaded'));
      })
      .catch(error => {
          console.error('Erro ao carregar página de conta:', error);
      });
}

// Selecionar o botão de fechar (X) dentro do divTopBar
const closeButton = document.querySelector('#divTopBar .KiosR');

if (closeButton) {
    closeButton.addEventListener('click', function(e) {
        // Prevenir qualquer comportamento padrão que possa estar afetando o layout
        e.preventDefault();
        e.stopPropagation();
        
        // Obter o elemento pai
        const topBarDiv = document.getElementById('divTopBar');
        
        if (topBarDiv) {
            // Em vez de apenas esconder, vamos remover o elemento completamente
            // para evitar que ele afete o layout
            topBarDiv.parentNode.removeChild(topBarDiv);
            
            // Alternativamente, se remover não for uma opção, use:
            // topBarDiv.style.display = 'none';
            
            // Força uma atualização do layout
            window.dispatchEvent(new Event('resize'));
            
            // Salvar preferência
            localStorage.setItem('topBarClosed', 'true');
        }
    });
}

// Tooltip do menu
document.addEventListener('DOMContentLoaded', function() {
  const sidebarMenu = document.getElementById('divSidebarMenu');
  const tooltip = document.getElementById('inoveTooltip');
  const tooltipWrap = document.getElementById('inoveTooltipWrap');
  
  // Função para verificar se a sidebar está recolhida
  function isSidebarCollapsed() {
      return !sidebarMenu.classList.contains('open');
  }
  
  // Pega todos os links e botões do menu
  const menuItems = sidebarMenu.querySelectorAll('.sdAXM, .f0Xlz');
  
  menuItems.forEach(item => {
      // Ao passar o mouse sobre o item
      item.addEventListener('mouseenter', function(e) {
          if (!isSidebarCollapsed()) return;
          
          // Encontra o elemento span que contém o texto do menu
          const textSpan = this.querySelector('span:not(.inove-icon):not(.xv-nQ):not(.UBVQS):not(.T2jJT):not(.sidebarIcon)');
          
          if (textSpan) {
              // Posiciona o tooltip ao lado do item
              const rect = this.getBoundingClientRect();
              tooltipWrap.style.top = (rect.top + rect.height/2 - 15) + 'px';
              tooltipWrap.style.left = (rect.right + 15) + 'px';
              
              // Define o texto do tooltip
              tooltip.textContent = textSpan.textContent.trim();
              
              // Mostra o tooltip
              tooltipWrap.style.display = 'block';
          }
      });
      
      // Ao tirar o mouse do item
      item.addEventListener('mouseleave', function() {
          tooltipWrap.style.display = 'none';
      });
  });
  
  // Também adicionar tooltips para os elementos específicos do topo da sidebar
  const specialItems = sidebarMenu.querySelectorAll('.W37on, .l6oz0');
  
  specialItems.forEach(item => {
      let tooltipText = '';
      
      if (item.classList.contains('l6oz0')) {
          tooltipText = 'Início';
      } else if (item.classList.contains('W37on')) {
          tooltipText = 'Fechar Menu';
      }
      
      item.addEventListener('mouseenter', function() {
          if (!isSidebarCollapsed()) return;
          
          const rect = this.getBoundingClientRect();
          tooltipWrap.style.top = (rect.top + rect.height/2 - 15) + 'px';
          tooltipWrap.style.left = (rect.right + 15) + 'px';
          tooltip.textContent = tooltipText;
          tooltipWrap.style.display = 'block';
      });
      
      item.addEventListener('mouseleave', function() {
          tooltipWrap.style.display = 'none';
      });
  });
});

// Função para gerenciar os banners responsivos
function gerenciarBannersResponsivos() {
// Buscar todos os banners na página, não apenas pelo ID
const desktopBanners = document.querySelectorAll('#desktop-banner, .desktop-banner');
const mobileBanners = document.querySelectorAll('#mobile-banner, .mobile-banner');

// Verificar o tamanho da tela
const isMobile = window.innerWidth <= 768;

// Aplicar classes a todos os banners desktop encontrados
desktopBanners.forEach(banner => {
  if (isMobile) {
    banner.className = 'j2x6J hidden';
  } else {
    banner.className = 'Ueilo block';
  }
});

// Aplicar classes a todos os banners mobile encontrados
mobileBanners.forEach(banner => {
  if (isMobile) {
    banner.className = 'Ueilo block';
  } else {
    banner.className = 'j2x6J hidden';
  }
});
}

// Executar no carregamento da página
gerenciarBannersResponsivos();

// Adicionar listener para redimensionamento da janela
window.addEventListener('resize', gerenciarBannersResponsivos);

// Função específica para tratar os modais
function tratarBannersEmModais() {
// Funções específicas para cada modal
function verificarModalRegistro() {
  const registerModal = document.querySelector('#registerModal, .registerModal, #register-modal, .register-modal');
  if (registerModal) {
    gerenciarBannersResponsivos();
  }
}

function verificarModalLogin() {
  const loginModal = document.querySelector('#loginModal, .loginModal, #login-modal, .login-modal');
  if (loginModal) {
    gerenciarBannersResponsivos();
  }
}

// Verificar modais a cada 100ms por 3 segundos após carregamento da página
let verificacoesModais = 0;
const intervalId = setInterval(() => {
  verificarModalRegistro();
  verificarModalLogin();
  
  verificacoesModais++;
  if (verificacoesModais >= 30) { // 30 * 100ms = 3 segundos
    clearInterval(intervalId);
  }
}, 100);

// Observer para detectar quando modais são adicionados ao DOM
const modalObserver = new MutationObserver(function(mutations) {
  mutations.forEach(function(mutation) {
    if (mutation.addedNodes.length) {
      gerenciarBannersResponsivos(); // Aplicar em qualquer mudança do DOM
    }
  });
});

// Iniciar observação do DOM com foco em elementos frequentemente alterados
modalObserver.observe(document.body, {
  childList: true,
  subtree: true,
  attributes: true,
  attributeFilter: ['class', 'style', 'hidden']
});

// Eventos específicos para capturar a abertura de modais
if (typeof jQuery !== 'undefined') {
  jQuery(document).on('shown.bs.modal show.bs.modal', function() {
    gerenciarBannersResponsivos();
    
    // Executar novamente após pequeno delay para garantir que os banners estão renderizados
    setTimeout(gerenciarBannersResponsivos, 100);
    setTimeout(gerenciarBannersResponsivos, 300);
  });
}

// Eventos gerais que podem indicar abertura de modal
document.addEventListener('click', function(e) {
  // Verificar se o clique foi em um botão que abre modal
  if (e.target.matches('[data-toggle="modal"], [data-bs-toggle="modal"], .open-modal, .modal-trigger')) {
    setTimeout(gerenciarBannersResponsivos, 100);
    setTimeout(gerenciarBannersResponsivos, 300);
  }
});
}

// Iniciar tratamento de modais quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', tratarBannersEmModais);

// Executar também imediatamente para casos em que o DOM já esteja carregado
if (document.readyState === 'interactive' || document.readyState === 'complete') {
tratarBannersEmModais();
}

// função para gerenciar as classes dos inputs
// Script para gerenciar classes dos inputs
(function() {
  // Função para gerenciar as classes dos inputs
  function handleInputClasses() {
      const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
      
      inputs.forEach(input => {
          // Função para atualizar as classes
          function updateClasses() {
              if (input.value.trim() !== '') {
                  input.classList.add('hasContent');
              } else {
                  input.classList.remove('hasContent');
              }
          }
          
          // Adicionar listeners para eventos
          input.addEventListener('input', updateClasses);
          input.addEventListener('blur', updateClasses);
          input.addEventListener('focus', updateClasses);
          
          // Verificar estado inicial
          updateClasses();
      });
  }

  // Aplicar o gerenciamento de classes imediatamente, se o DOM já estiver carregado
  if (document.readyState === 'complete' || document.readyState === 'interactive') {
      handleInputClasses();
  } else {
      // Caso contrário, esperar o DOM carregar
      document.addEventListener('DOMContentLoaded', handleInputClasses);
  }

  // Adicionar um observador de mutações para capturar novos inputs que possam ser adicionados dinamicamente
  const observer = new MutationObserver(function(mutations) {
      let shouldReapply = false;
      
      mutations.forEach(function(mutation) {
          if (mutation.type === 'childList' && mutation.addedNodes.length) {
              shouldReapply = true;
          }
      });
      
      if (shouldReapply) {
          handleInputClasses();
      }
  });
  
  // Observar o documento inteiro para alterações no DOM
  observer.observe(document.body, {
      childList: true,
      subtree: true
  });
  
  // Verificar os inputs após a alternância entre formulários
  const originalToggleForms = window.toggleForms;
  if (typeof originalToggleForms === 'function') {
      window.toggleForms = function(formToShow) {
          originalToggleForms(formToShow);
          // Reaplicar o handler após a alternância de formulários
          setTimeout(handleInputClasses, 100);
      };
  }
})();


// Função para alternar visibilidade da senha
window.togglePasswordVisibility = function(inputId) {
  const input = document.getElementById(inputId);
  const type = input.type === 'password' ? 'text' : 'password';
  input.type = type;
  
  // Alternar entre os ícones (olho aberto/fechado)
  const iconContainer = input.parentElement.querySelector('.suffix-icon');
  if (type === 'text') {
      // Mostrar ícone de olho aberto
      iconContainer.innerHTML = `
          <span data-v-44b1d268="" class="nuxt-icon nuxt-icon--fill">
              <svg height="1em" viewBox="0 0 576 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                  <path d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z" fill="currentColor"></path>
              </svg>
          </span>
      `;
  } else {
      // Mostrar ícone de olho fechado
      iconContainer.innerHTML = `
          <span data-v-44b1d268="" class="nuxt-icon nuxt-icon--fill">
              <svg height="1em" viewBox="0 0 640 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.112 9.196C13.29-1.236 28.37-3.065 38.81 5.112L630.8 469.1C641.2 477.3 643.1 492.4 634.9 502.8C626.7 513.2 611.6 515.1 601.2 506.9L9.196 42.89C-1.236 34.71-3.065 19.63 5.112 9.196V9.196z" fill="currentColor"></path>
                  <path d="M446.6 324.7C457.7 304.3 464 280.9 464 256C464 176.5 399.5 112 320 112C282.7 112 248.6 126.2 223.1 149.5L150.7 92.77C195 58.27 251.8 32 320 32C400.8 32 465.5 68.84 512.6 112.6C559.4 156 590.7 207.1 605.5 243.7C608.8 251.6 608.8 260.4 605.5 268.3C592.1 300.6 565.2 346.1 525.6 386.7L446.6 324.7zM313.4 220.3C317.6 211.8 320 202.2 320 192C320 180.5 316.1 169.7 311.6 160.4C314.4 160.1 317.2 160 320 160C373 160 416 202.1 416 256C416 269.7 413.1 282.7 407.1 294.5L313.4 220.3zM320 480C239.2 480 174.5 443.2 127.4 399.4C80.62 355.1 49.34 304 34.46 268.3C31.18 260.4 31.18 251.6 34.46 243.7C44 220.8 60.29 191.2 83.09 161.5L177.4 235.8C176.5 242.4 176 249.1 176 256C176 335.5 240.5 400 320 400C338.7 400 356.6 396.4 373 389.9L446.2 447.5C409.9 467.1 367.8 480 320 480H320z" fill="currentColor" opacity="0.4"></path>
              </svg>
          </span>
      `;
  }
};