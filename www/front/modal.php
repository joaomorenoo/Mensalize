<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Exemplo de Modal</title>
  <style>
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal-content {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      width: 300px;
      text-align: center;
    }

    .close-button {
      background-color: #f44336;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 4px;
      cursor: pointer;
    }

    .open-button {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <h1>Exemplo de Modal</h1>
  <button class="open-button" onclick="openModal()">Abrir Modal</button>

  <div class="modal-overlay" id="modal">
    <div class="modal-content">
      <h2>Olá!</h2>
      <p>Este é um modal simples.</p>
      <button class="close-button" onclick="closeModal()">Fechar</button>
    </div>
  </div>

  <script>
    function openModal() {
      document.getElementById('modal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('modal').style.display = 'none';
    }

    window.onclick = function(event) {
      const modal = document.getElementById('modal');
      if (event.target === modal) {
        closeModal();
      }
    }
  </script>
</body>
</html>
