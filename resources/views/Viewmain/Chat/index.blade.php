<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Solicitud</title>
  <style>
    /* Colores Azul */
    :root {
      --color-principal: #007BFF;
      --color-secundario: #0056b3;
      --color-fondo: #f0f8ff;
      --color-texto: #333;
      --color-boton: #0069d9;
      --color-boton-hover: #0056b3;
      --color-boton-flotante: #28a745;
      --color-boton-flotante-hover: #218838;
    }

    /* Resetear márgenes */
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
      padding: 20px;
    }

    /* Estilos del modal de carga */
    #loadingModal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
      display: none;
    }

    #loadingModal div {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      text-align: center;
      font-size: 18px;
    }

    /* Contenedor del formulario */
    .form-container {
      max-width: 600px;
      margin: auto;
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: var(--color-principal);
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin: 10px 0 5px;
    }

    input, textarea, button {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    input[type="file"] {
      padding: 0;
    }

    button {
      background-color: var(--color-boton);
      color: white;
      cursor: pointer;
      border: none;
      font-size: 16px;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: var(--color-boton-hover);
    }

    /* Estilo del botón flotante */
    .floating-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: var(--color-boton-flotante);
      border: none;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      font-size: 30px;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      transition: background-color 0.3s;
      z-index: 1000;
    }

    .floating-btn:hover {
      background-color: var(--color-boton-flotante-hover);
    }

    /* Estilo responsive */
    @media screen and (max-width: 768px) {
      body {
        padding: 10px;
      }

      .form-container {
        padding: 20px;
      }

      /* Aseguramos que el botón flotante sea accesible en pantallas pequeñas */
      .floating-btn {
        bottom: 15px;
        right: 15px;
        width: 50px;
        height: 50px;
        font-size: 24px;
      }
    }

  </style>
</head>
<body>

  <!-- Modal de carga -->
  <div id="loadingModal">
    <div>
      <p>La solicitud de mensaje está siendo receptada...</p>
    </div>
  </div>

  <!-- Formulario -->
  <div class="form-container">
    <h1>Formulario de Solicitud</h1>
    <form id="myForm">
      <label for="message">Mensaje:</label>
      <textarea id="message" name="message" rows="4" required></textarea>

      <label for="file">Adjuntar archivo:</label>
      <input type="file" id="file" name="file" required>

      <label for="gps">Coordenadas GPS:</label>
      <input type="text" id="gps" name="gps" placeholder="Latitud, Longitud" required>

      <button type="submit">Enviar Solicitud</button>
    </form>
  </div>

  <!-- Botón flotante -->
  <button class="floating-btn" onclick="alert('¡Botón flotante clickeado!')">
    +
  </button>

  <script>
    // Mostrar el modal al cargar la página
    window.onload = function() {
      document.getElementById("loadingModal").style.display = "flex";
      // Simulamos un retraso para mostrar el mensaje de carga
      setTimeout(function() {
        document.getElementById("loadingModal").style.display = "none";
      }, 3000); // 3 segundos
    };

    // Enviar formulario
    document.getElementById("myForm").addEventListener("submit", function(e) {
      e.preventDefault();
      alert("Formulario enviado con éxito!");
    });
  </script>

</body>
</html>