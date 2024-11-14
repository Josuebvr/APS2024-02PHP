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

    link.click();

    // Redireciona para a página de meio ambiente após o download
    setTimeout(() => {
        window.location.href = 'conteudo/meioambiente.php';
    }, 1000); // Ajuste o tempo de delay conforme necessário

});

