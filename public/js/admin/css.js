
    // Objeto para armazenar os valores atuais das variáveis CSS
    let currentCssValues = {
        @foreach($cssVariables as $key => $variable)
        '{{ $key }}': '{{ $currentValues[$key] }}',
        @endforeach
    };
    
    // Constantes para armazenar os valores padrão
    const defaultValues = {
        @foreach($cssVariables as $key => $variable)
        '{{ $key }}': '{{ $variable['default'] }}',
        @endforeach
    };
    
    // Função para atualizar o CSS em tempo real
    function updateLiveCSS() {
        let cssText = ":root {\n";
        
        // Adiciona todas as variáveis atuais
        Object.keys(currentCssValues).forEach(key => {
            cssText += `    --${key}: ${currentCssValues[key]};\n`;
        });
        
        cssText += "}\n\n";
        
        // Adiciona CSS personalizado
        const customCss = document.getElementById('customCss').value;
        cssText += customCss;
        
        // Atualiza o CSS inline
        const styleElement = document.getElementById('live-preview-css');
        styleElement.textContent = cssText;
        
        // Atualiza também os previews de código
        document.getElementById('cssPreview').textContent = ":root {\n" + Object.keys(currentCssValues).map(key => `    --${key}: ${currentCssValues[key]};`).join('\n') + "\n}";
    }
    
    // Função para atualizar uma variável CSS quando o usuário seleciona uma cor
    function updateCssVariable(element) {
        const varName = element.dataset.var;
        const colorValue = element.value;
        
        // Atualiza o valor no objeto de estado
        currentCssValues[varName] = colorValue;
        
        // Atualiza o campo de texto correspondente
        document.getElementById(`text_${varName}`).value = colorValue;
        
        // Atualiza a cor de preview
        const previewEl = element.closest('.card-body').querySelector('.color-preview');
        if (previewEl) {
            previewEl.style.backgroundColor = colorValue;
        }
        
        // Atualiza o CSS em tempo real
        updateLiveCSS();
        
        // Salva no servidor
        saveCssVariable(varName, colorValue);
    }
    
    // Função para atualizar a cor a partir do campo de texto
    function updateColorFromText(element, varName) {
        const colorValue = element.value;
        
        // Verifica se o valor é uma cor válida
        if (isValidColor(colorValue)) {
            // Atualiza o valor no objeto de estado
            currentCssValues[varName] = colorValue;
            
            // Atualiza o campo de cor correspondente se for hexadecimal
            if (colorValue.startsWith('#')) {
                const colorInput = document.getElementById(`color_${varName}`);
                if (colorInput) {
                    colorInput.value = colorValue;
                }
            }
            
            // Atualiza a cor de preview
            const previewEl = element.closest('.card-body').querySelector('.color-preview');
            if (previewEl) {
                previewEl.style.backgroundColor = colorValue;
            }
            
            // Atualiza o CSS em tempo real
            updateLiveCSS();
            
            // Salva no servidor
            saveCssVariable(varName, colorValue);
        } else {
            // Se não for válido, restaura o valor anterior
            element.value = currentCssValues[varName];
            alert('Por favor, insira um valor de cor válido (hex, rgb, rgba, etc).');
        }
    }
    
    // Função para resetar para o valor padrão
    function resetToDefault(varName, defaultValue) {
        // Atualiza o valor no objeto de estado
        currentCssValues[varName] = defaultValue;
        
        // Atualiza os campos correspondentes
        const textInput = document.getElementById(`text_${varName}`);
        textInput.value = defaultValue;
        
        // Se for um valor hex, atualiza o input color
        if (defaultValue.startsWith('#')) {
            const colorInput = document.getElementById(`color_${varName}`);
            if (colorInput) {
                colorInput.value = defaultValue;
            }
        }
        
        // Atualiza a cor de preview
        const previewEl = textInput.closest('.card-body').querySelector('.color-preview');
        if (previewEl) {
            previewEl.style.backgroundColor = defaultValue;
        }
        
        // Atualiza o CSS em tempo real
        updateLiveCSS();
        
        // Salva no servidor
        saveCssVariable(varName, defaultValue);
    }
    
    // Função para atualizar o preview do CSS personalizado
    function updateCustomCssPreview(value) {
        document.getElementById('customCssPreview').textContent = value || '/* Sem CSS personalizado */';
        updateLiveCSS();
    }
    
    // Função para salvar uma variável CSS no servidor via AJAX
    function saveCssVariable(varName, value) {
        // Mostra indicador de carregamento
        showLoadingIndicator('Salvando alterações...');
        
        // Prepara os dados para enviar
        const formData = new FormData();
        formData.append('variable', varName);
        formData.append('value', value);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Envia a requisição AJAX
        fetch('{{ route("admin.custom-css.update-variable") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideLoadingIndicator();
            showSuccessMessage('Alterações salvas com sucesso!');
        })
        .catch(error => {
            hideLoadingIndicator();
            showErrorMessage('Erro ao salvar alterações. Tente novamente.');
            console.error('Erro ao salvar:', error);
        });
    }
    
    // Função para salvar o CSS personalizado
    function saveCustomCss() {
        // Obtém o valor do textarea
        const customCss = document.getElementById('customCss').value;
        
        // Mostra indicador de carregamento
        showLoadingIndicator('Salvando CSS personalizado...');
        
        // Prepara os dados para enviar
        const formData = new FormData();
        formData.append('custom_css', customCss);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Envia a requisição AJAX
        fetch('{{ route("admin.custom-css.update-custom") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideLoadingIndicator();
            showSuccessMessage('CSS personalizado salvo com sucesso!');
            
            // Atualiza o preview
            updateCustomCssPreview(customCss);
        })
        .catch(error => {
            hideLoadingIndicator();
            showErrorMessage('Erro ao salvar CSS personalizado. Tente novamente.');
            console.error('Erro ao salvar CSS personalizado:', error);
        });
    }
    
    // Funções utilitárias
    
    // Função para mostrar indicador de carregamento
    function showLoadingIndicator(message) {
        // Remove indicadores anteriores
        hideLoadingIndicator();
        
        // Cria novo indicador
        const loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'saving-indicator';
        loadingIndicator.className = 'alert alert-info position-fixed';
        loadingIndicator.style.top = '20px';
        loadingIndicator.style.right = '20px';
        loadingIndicator.style.zIndex = '9999';
        loadingIndicator.innerHTML = `<div class="spinner-border spinner-border-sm me-2" role="status"></div> ${message}`;
        document.body.appendChild(loadingIndicator);
    }
    
    // Função para esconder indicador de carregamento
    function hideLoadingIndicator() {
        const existingIndicator = document.getElementById('saving-indicator');
        if (existingIndicator) {
            existingIndicator.remove();
        }
    }
    
    // Função para mostrar mensagem de sucesso
    function showSuccessMessage(message) {
        const successIndicator = document.createElement('div');
        successIndicator.className = 'alert alert-success position-fixed';
        successIndicator.style.top = '20px';
        successIndicator.style.right = '20px';
        successIndicator.style.zIndex = '9999';
        successIndicator.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle me-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> ${message}`;
        document.body.appendChild(successIndicator);
        
        // Remove a mensagem após 2 segundos
        setTimeout(() => {
            successIndicator.remove();
        }, 2000);
    }
    
    // Função para mostrar mensagem de erro
    function showErrorMessage(message) {
        const errorIndicator = document.createElement('div');
        errorIndicator.className = 'alert alert-danger position-fixed';
        errorIndicator.style.top = '20px';
        errorIndicator.style.right = '20px';
        errorIndicator.style.zIndex = '9999';
        errorIndicator.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle me-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> ${message}`;
        document.body.appendChild(errorIndicator);
        
        // Remove a mensagem após 3 segundos
        setTimeout(() => {
            errorIndicator.remove();
        }, 3000);
    }
    
    // Verifica se uma string é uma cor válida
    function isValidColor(color) {
        // Cria um elemento temporário para testar a cor
        const tempElement = document.createElement('div');
        tempElement.style.color = "";
        tempElement.style.color = color;
        return tempElement.style.color !== "";
    }
    
    // Inicialização
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa a visualização do CSS
        updateLiveCSS();
    });