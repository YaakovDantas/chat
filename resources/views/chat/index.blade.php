<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        /* public/css/styles.css */

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        #messages {
            margin: 20px 0;
            border: 1px solid #ddd;
            padding: 10px;
            height: 300px;
            overflow-y: scroll;
        }

        input[type="text"] {
            width: calc(50% - 10px);
            padding: 10px;
            margin-right: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        button {
            padding: 10px 15px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Chat Ruim</h1>
        
        <!-- Mensagem de boas-vindas -->
        <div id="welcomeMessage"></div>
        <br>
        
        <!-- Seleção de Canal -->
        <div>
            <label for="canalSelect">Selecionar Canal:</label>
            <select id="canalSelect">
                <option value="1">Geral</option>
                <option value="2">Específico</option>
                <!-- Adicione mais canais conforme necessário -->
            </select>
        </div>

        <!-- Mensagens -->
        <div id="messages" style="border: 1px solid #ccc; padding: 10px; height: 200px; overflow-y: scroll;">
            <!-- As mensagens serão injetadas aqui -->
        </div>

        <!-- Campos para Enviar Mensagem -->
        <div>
            <input type="text" id="nome" placeholder="Seu nome" required>
            <input type="text" id="texto" placeholder="Digite sua mensagem" required>
            <button id="sendMessage">Enviar</button>
        </div>
    </div>

    <script>
        // Função para configurar o nome do usuário
        function setupUserName() {
            const userNameInput = document.getElementById('nome');
            const welcomeMessage = document.getElementById('welcomeMessage');
            const storedName = localStorage.getItem('userName');

            // Verifica se o nome já foi armazenado
            if (storedName) {
                userNameInput.style.display = 'none'; // Oculta o campo de entrada
                welcomeMessage.textContent = `Bem-vindo(a), ${storedName}!`;
            } else {
                // Adiciona um evento ao botão de enviar nome
                document.getElementById('sendMessage').addEventListener('click', function() {
                    const userName = userNameInput.value.trim();
                    if (userName) {
                        localStorage.setItem('userName', userName); // Armazena o nome no localStorage
                        userNameInput.style.display = 'none'; // Oculta o campo de entrada
                        welcomeMessage.textContent = `Bem-vindo(a), ${userName}!`;
                    }
                });
            }
        }

        // Adiciona o evento para enviar mensagens
        document.getElementById('sendMessage').addEventListener('click', function() {
            const canalId = document.getElementById('canalSelect').value;
            const nome = localStorage.getItem('userName') ?? document.getElementById('nome').value;// Obtém o nome do localStorage
            const texto = document.getElementById('texto').value;
            
            if (nome) {
                fetch('/chat/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ canal_id: canalId, nome: nome, texto: texto })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadMessages(canalId);
                        document.getElementById('texto').value = ''; // Limpa o campo de mensagem após o envio
                    }
                });
            }
        });

        function loadMessages(canalId) {
            fetch(`/chat/messages/${canalId}`)
            .then(response => response.json())
            .then(messages => {
                const messagesDiv = document.getElementById('messages');
                messagesDiv.innerHTML = '';
                messages.forEach(message => {
                    const messageElement = document.createElement('div');
                    messageElement.textContent = `${message.nome}: ${message.texto}`;
                    messagesDiv.appendChild(messageElement);
                });
            });
        }

        document.getElementById('canalSelect').addEventListener('change', function() {
            loadMessages(this.value);
        });

        // Carregar mensagens do canal padrão ao carregar a página
        loadMessages(document.getElementById('canalSelect').value);

        // Chama a função para configurar o nome do usuário
        setupUserName();
    </script>
</body>
</html>
