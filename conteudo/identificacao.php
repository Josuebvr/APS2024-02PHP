<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    die("Erro: Usuário não autenticado.");
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $imageData = $_POST['image']; // tira a foto

    
    $imagePath = 'C:/wamp64/www/APS2024-02PHP/temp/' . uniqid() . '.jpg';
    if (!file_put_contents($imagePath, base64_decode($imageData))) {
        die("Erro ao salvar a imagem. Verifique o caminho e as permissões.");
    }

    // aqui chama o python (ta com erro)
    $url = 'http://localhost:5000/conteudo/comparador';
    $data = [
        'new_image' => curl_file_create($imagePath),
        'email' => $email 
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    
    if ($result && isset($result['sucesso']) && $result['sucesso'] && $result['correspondencia'] === true) {
        header("Location: meioambiente.php");
    } else {
        echo "As fotos não correspondem. Tente novamente."; //tem que arrumar depois
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Identificação Biométrica</title>
</head>
<body>
    <video autoplay></video>
    <canvas style="display: none;"></canvas>
    <button id="captureButton">Tirar foto</button>

    <form method="POST" id="photoForm">
        <input type="hidden" name="image" id="imageInput">
        <button type="submit" id="redirectButton" style="display: none;">Ir para Meio Ambiente</button>
    </form>

    <script>
        var video = document.querySelector('video');
        var captureButton = document.getElementById('captureButton');
        var redirectButton = document.getElementById('redirectButton');
        var imageInput = document.getElementById('imageInput');

        navigator.mediaDevices.getUserMedia({video: true})
        .then(stream => {
            video.srcObject = stream;
            video.play();
        })
        .catch(error => {
            console.log(error);
        });

        captureButton.addEventListener('click', () => {
            var canvas = document.querySelector('canvas');
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            var context = canvas.getContext('2d');
            context.drawImage(video, 0, 0);

           
            var dataURL = canvas.toDataURL('image/jpeg').replace(/^data:image\/jpeg;base64,/, '');
            imageInput.value = dataURL;

           
            redirectButton.style.display = 'inline-block';
        });
    </script>
</body>
</html>
