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
        <h1>Chat Bom</h1>
        
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
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <script>
        currentChannel = null
        Pusher.logToConsole = false;

        const pusher = new Pusher('c1a40ec714d0fc227197', {
            cluster: 'us2',
        });

        document.addEventListener('DOMContentLoaded', function () {
        
    

        // Substitua 'YOUR_PUSHER_APP_KEY' e 'YOUR_PUSHER_APP_CLUSTER' pelos valores corretos das configurações do seu Pusher

        // Seleciona o canal padrão
        const defaultChannelId = document.getElementById('canalSelect').value;
        currentChannel = subscribeToChannel(defaultChannelId);

        // Função para assinar um canal Pusher e ouvir eventos
        function subscribeToChannel(canalId) {
            if (currentChannel) {
                currentChannel.unsubscribe(); // Desinscreve do canal anterior se necessário
            }

            const channel = pusher.subscribe('chat-channel-' + canalId);
            channel.bind('message-sent', function (data) {
                addMessageToScreen(data.message);
            });

            return channel;
        }

        // Função para adicionar uma mensagem na tela
        function addMessageToScreen(message) {
            const messagesDiv = document.getElementById('messages');
            const messageElement = document.createElement('div');
            messageElement.textContent = `${message.nome}: ${message.texto}`;
            messagesDiv.appendChild(messageElement);
            messagesDiv.scrollTop = messagesDiv.scrollHeight; // Rola para a mensagem mais recente
        }

        // Carregar mensagens do canal selecionado ao trocar de canal
        document.getElementById('canalSelect').addEventListener('change', function () {
            const canalId = this.value;
            currentChannel = subscribeToChannel(canalId);
            loadMessages(canalId);
        });

        // Função para carregar mensagens do canal
        function loadMessages(canalId) {
            fetch(`/chat/messages/${canalId}`)
                .then(response => response.json())
                .then(messages => {
                    const messagesDiv = document.getElementById('messages');
                    messagesDiv.innerHTML = ''; // Limpa mensagens antigas
                    messages.forEach(message => {
                        addMessageToScreen(message);
                    });
                });
        }

        // Adiciona evento ao botão de enviar mensagem
        document.getElementById('sendMessage').addEventListener('click', function () {
            const canalId = document.getElementById('canalSelect').value;
            const nome = localStorage.getItem('userName') ?? document.getElementById('nome').value;
            const texto = document.getElementById('texto').value;

            if (nome && texto) {
                fetch('/chat3/messages', {
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
                        document.getElementById('texto').value = ''; // Limpa o campo de mensagem
                    }
                });
            }
        });

        function setupUserName() {
                const userNameInput = document.getElementById('nome');
                const welcomeMessage = document.getElementById('welcomeMessage');
                const storedName = localStorage.getItem('userName');

                if (storedName) {
                    userNameInput.style.display = 'none'; // Oculta o campo de entrada
                    welcomeMessage.textContent = `Bem-vindo(a), ${storedName}!`;
                } else {
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

        // Carregar mensagens do canal padrão ao carregar a página
        loadMessages(defaultChannelId);

        setupUserName();
});

    </script>
</body>
</html>
