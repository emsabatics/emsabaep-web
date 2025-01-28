<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat en Línea</title>
    <style>
        body { font-family: Arial, sans-serif; }
        #chat-container { width: 500px; margin: 0 auto; }
        #messages { max-height: 400px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        .message { margin-bottom: 10px; }
        .message span { font-weight: bold; }
        #input-container { display: flex; }
        #input-container input { width: 80%; padding: 5px; }
        #input-container button { width: 20%; padding: 5px; }
    </style>
</head>
<body>

<div id="chat-container">
    <div id="messages"></div>

    <div id="input-container">
        <input type="text" id="user_name" placeholder="Tu nombre" />
        <input type="text" id="message" placeholder="Escribe tu mensaje" />
        <button id="send-message">Enviar</button>
    </div>
</div>

<script>
    const messagesContainer = document.getElementById('messages');
    const userNameInput = document.getElementById('user_name');
    const messageInput = document.getElementById('message');
    const sendMessageButton = document.getElementById('send-message');

    // Función para recuperar los mensajes
    function fetchMessages() {
        fetch('/get-messages')
            .then(response => response.json())
            .then(data => {
                messagesContainer.innerHTML = '';
                data.reverse().forEach(msg => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    messageElement.innerHTML = `<span>${msg.idusuario}:</span> ${msg.mensaje}`;
                    messagesContainer.appendChild(messageElement);
                });
                messagesContainer.scrollTop = messagesContainer.scrollHeight; // Desplazarse hacia abajo
            });
    }

    // Función para enviar un mensaje
    function sendMessage() {
        const userName = userNameInput.value;
        const message = messageInput.value;
        if (userName && message) {
            fetch('/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ user_name: userName, message: message })
            })
            .then(response => response.json())
            .then(() => {
                messageInput.value = '';
                fetchMessages(); // Actualizar la lista de mensajes
            });
        }
    }

    // Configurar el evento de envío de mensaje
    sendMessageButton.addEventListener('click', sendMessage);

    // Recuperar mensajes cada 3 segundos
    setInterval(fetchMessages, 3000);

    // Cargar los mensajes cuando la página se carga
    window.onload = fetchMessages;
</script>

</body>
</html>