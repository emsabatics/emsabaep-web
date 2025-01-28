<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat en Línea</title>
  <style>
    /* Colores y Variables */
    :root {
      --color-principal: #007BFF;
      --color-secundario: #0056b3;
      --color-fondo: #f4f4f4;
      --color-boton: #0069d9;
      --color-boton-hover: #0056b3;
      --color-mensaje-enviado: #d1e7ff;
      --color-mensaje-recibido: #f1f1f1;
      --color-texto: #333;
      --color-popup-fondo: rgba(0, 0, 0, 0.5);
      --color-popup: white;
      --color-boton-ayuda: #28a745;
    }

    /* Reset de estilos */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Estilos Generales */
    body {
      font-family: Arial, sans-serif;
      background-color: var(--color-fondo);
      color: var(--color-texto);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .chat-container {
      width: 100%;
      max-width: 500px;
      height: 80vh;
      background-color: white;
      display: flex;
      flex-direction: column;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Contenedor de mensajes */
    .chat-box {
      flex-grow: 1;
      overflow-y: auto;
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      background-color: #f9f9f9;
    }

    .message {
      max-width: 70%;
      padding: 10px;
      border-radius: 15px;
      font-size: 14px;
      word-wrap: break-word;
      line-height: 1.5;
    }

    .message.sent {
      background-color: var(--color-mensaje-enviado);
      align-self: flex-end;
    }

    .message.received {
      background-color: var(--color-mensaje-recibido);
      align-self: flex-start;
    }

    .message img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
    }

    /* Formulario de envío */
    .input-container {
      display: flex;
      border-top: 1px solid #ddd;
      padding: 10px;
      background-color: white;
      justify-content: space-between;
    }

    .input-container input[type="text"] {
      width: 80%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
      outline: none;
    }

    .input-container input[type="file"] {
      display: none;
    }

    .input-container button {
      background-color: var(--color-boton);
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s;
    }

    .input-container button:hover {
      background-color: var(--color-boton-hover);
    }

    .file-label {
      display: inline-block;
      margin-right: 10px;
      cursor: pointer;
      background-color: #ccc;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 14px;
    }

    /* Estilo del botón de ayuda */
    .help-button {
      position: fixed;
      bottom: 20px;
      left: 20px;
      background-color: var(--color-boton-ayuda);
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: background-color 0.3s;
    }

    .help-button:hover {
      background-color: #218838;
    }

    /* Estilo del popup de ayuda */
    #helpPopup {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: var(--color-popup-fondo);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .popup-content {
      background-color: var(--color-popup);
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      max-width: 80%;
      max-height: 80%;
      overflow-y: auto;
    }

    .popup-content img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
    }

    .close-btn {
      background-color: red;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
      font-size: 14px;
    }

    /* Estilo para pantallas pequeñas */
    @media screen and (max-width: 768px) {
      .chat-container {
        height: 90vh;
      }

      .input-container input[type="text"] {
        width: 70%;
      }

      .input-container button {
        font-size: 12px;
      }

      .help-button {
        bottom: 10px;
        left: 10px;
        padding: 8px 15px;
        font-size: 12px;
      }
    }

  </style>
</head>
<body>

  <div class="chat-container">
    <!-- Contenedor de mensajes -->
    <div class="chat-box" id="chat-box">
      <!-- Los mensajes aparecerán aquí -->
    </div>

    <!-- Formulario de envío -->
    <div class="input-container">
      <label for="file" class="file-label">Adjuntar archivo</label>
      <input type="file" id="file" name="file" accept=".jpg, .jpeg, .png, .pdf">
      <input type="text" id="message" placeholder="Escribe un mensaje..." required>
      <button id="send-btn">Enviar</button>
    </div>
  </div>

  <!-- Botón de ayuda -->
  <button class="help-button" id="helpBtn">Ayuda</button>

  <!-- Popup de ayuda -->
  <div id="helpPopup">
    <div class="popup-content">
      <h2>¿Necesitas ayuda?</h2>
      <p>Este es el chat de ayuda. Puedes escribir tus mensajes y adjuntar archivos.</p>
      <img src="https://via.placeholder.com/400" alt="Imagen de ayuda">
      <button class="close-btn" id="closePopup">Cerrar</button>
    </div>
  </div>

  <script>
    const chatBox = document.getElementById('chat-box');
    const sendBtn = document.getElementById('send-btn');
    const messageInput = document.getElementById('message');
    const fileInput = document.getElementById('file');
    const helpBtn = document.getElementById('helpBtn');
    const helpPopup = document.getElementById('helpPopup');
    const closePopup = document.getElementById('closePopup');

    // Simulación de conversación entre dos personas
    const messages = [
      { text: '¡Hola! ¿Cómo estás?', sender: 'received' },
      { text: '¡Hola! Todo bien, ¿y tú?', sender: 'sent' },
      { text: 'Todo bien, gracias por preguntar.', sender: 'received' }
    ];

    // Mostrar los mensajes existentes
    function displayMessages() {
      chatBox.innerHTML = '';
      messages.forEach(message => {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', message.sender);
        
        // Mostrar imágenes o PDF
        if (message.type === 'image') {
          const img = document.createElement('img');
          img.src = message.content;
          messageElement.appendChild(img);
        } else if (message.type === 'pdf') {
          const link = document.createElement('a');
          link.href = message.content;
          link.textContent = 'Ver PDF';
          messageElement.appendChild(link);
        } else {
          messageElement.textContent = message.content;
        }

        chatBox.appendChild(messageElement);
      });

      // Desplazar el chat hacia el final
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Enviar mensaje
    sendBtn.addEventListener('click', () => {
      const messageText = messageInput.value.trim();
      if (messageText) {
        messages.push({ content: messageText, sender: 'sent' });
        messageInput.value = '';
        displayMessages();
      }
    });

    // Adjuntar archivo
    fileInput.addEventListener('change', (event) => {
      const file = event.target.files[0];
      if (file) {
        const fileType = file.type;

        // Validar solo imágenes o PDFs
        if (fileType.startsWith('image/') || fileType === 'application/pdf') {
          const reader = new FileReader();

          reader.onload = function (e) {
            let messageContent = e.target.result;
            let messageType = 'image';

            // Si es un PDF, crear un enlace
            if (fileType === 'application/pdf') {
              messageContent = URL.createObjectURL(file);
              messageType = 'pdf';
            }

            // Agregar el mensaje con el archivo adjunto
            messages.push({ content: messageContent, sender: 'sent', type: messageType });
            displayMessages();
          };

          reader.readAsDataURL(file);
        } else {
          alert('Solo puedes adjuntar imágenes (PNG, JPG, JPEG) o PDFs.');
        }
      }
    });

    // Mostrar los mensajes al cargar
    displayMessages();

    // Mostrar el popup de ayuda
    helpBtn.addEventListener('click', () => {
      helpPopup.style.display = 'flex';
    });

    // Cerrar el popup de ayuda
    closePopup.addEventListener('click', () => {
      helpPopup.style.display = 'none';
    });

  </script>

</body>
</html>
