document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('updateUsernameForm');
    const modal = document.getElementById('usernameModal');
    const closeBtn = document.getElementById('closeUsernameModal');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            
            fetch('/user/update-username', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ username: username })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualiza o nome na interface
                    const nameElement = document.querySelector('.user-name');
                    if (nameElement) {
                        nameElement.textContent = username;
                    }
                    
                    // Fecha o modal
                    modal.style.display = 'none';
                    
                    // Mostra mensagem de sucesso
                    alert(data.message);
                } else {
                    alert('Erro ao atualizar o nome. Por favor, tente novamente.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao atualizar o nome. Por favor, tente novamente.');
            });
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    // Fecha o modal quando clicar fora dele
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
}); 
