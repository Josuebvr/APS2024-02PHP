<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redirect'])) {
    header('Location: meioambiente.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport"content="width=device-width, initial-scale=1.0">
        <title>Identificação Biométrica</title>
    </head>
    <body>
        <video autoplay></video>
        <canvas></canvas>
        <button>Tirar foto</button>
        <form method="POST">
        <button id="redirectButton" name="redirect" style="display: none;">Ir para Meio Ambiente</button>
        </form>

        <script>
            var video = document.querySelector('video');

            navigator.mediaDevices.getUserMedia({video:true})
            .then(stream => {
                video.srcObject = stream;
                video.play();
            })
            .catch(error => {
                console.log(error);
            })

            document.querySelector('button').addEventListener('click', () => {
                var canvas = document.querySelector('canvas');
                canvas.height = video.videoHeight;
                canvas.width = video.videoWidth;
                var context = canvas.getContext('2d');
                context.drawImage(video, 0, 0);
                var link = document.createElement('a');
                link.download = 'identificacao.png';
                link.href = canvas.toDataURL();
                link.textContent = 'Clique para baixar a imagem';
                document.body.appendChild(link);

                redirectButton.style.display = 'inline-block';
                
            });


        </script>
        
    </body>
</html>