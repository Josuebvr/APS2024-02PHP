from flask import Flask, request, jsonify
import cv2
import numpy as np
import os
import mysql.connector

app = Flask(__name__)

# Função para comparar imagens
def comparar_imagens(caminho_imagem1, caminho_imagem2):
    img1 = cv2.imread(caminho_imagem1, cv2.IMREAD_GRAYSCALE)
    img2 = cv2.imread(caminho_imagem2, cv2.IMREAD_GRAYSCALE)
    img1 = cv2.resize(img1, (256, 256))
    img2 = cv2.resize(img2, (256, 256))
    mse = np.mean((img1 - img2) ** 2)
    limite_similaridade = 1000
    return mse < limite_similaridade

# Função para obter o caminho da imagem com base no email do banco de dados
def obter_caminho_imagem_usuario(email_usuario):
    print(f"Buscando imagem para o email: {email_usuario}")  # Log para verificar o email recebido
    try:
        conexao = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="aps2024-02php"
        )
        cursor = conexao.cursor()
        consulta = "SELECT imagem FROM usuario WHERE email = %s"
        cursor.execute(consulta, (email_usuario,))
        resultado = cursor.fetchone()
        cursor.close()
        conexao.close()
        
        if resultado:
            # Verifica se o caminho já é completo ou se precisa de ajuste
            caminho_relativo_imagem = resultado[0]
            if not caminho_relativo_imagem.startswith("C:/wamp64/www/APS2024-02PHP/uploads"):
                caminho_completo_imagem = os.path.join('C:/wamp64/www/APS2024-02PHP/uploads', caminho_relativo_imagem)
            else:
                caminho_completo_imagem = caminho_relativo_imagem
            print(f"Caminho completo da imagem recuperado: {caminho_completo_imagem}")
            return caminho_completo_imagem
        else:
            print("Nenhuma imagem encontrada para o email fornecido.")
            return None
    except mysql.connector.Error as err:
        print(f"Erro de conexão ao banco de dados: {err}")
        return None

@app.route('/conteudo/comparador', methods=['POST'])
def comparar_fotos():
    nova_imagem = request.files['new_image']
    email_usuario = request.form['email']  # Usando o email ao invés do ID

    diretorio_temp = 'C:/wamp64/www/APS2024-02PHP/temp/'
    if not os.path.exists(diretorio_temp):
        os.makedirs(diretorio_temp)
    
    caminho_nova_imagem = os.path.join(diretorio_temp, f"{email_usuario}_new.jpg")
    nova_imagem.save(caminho_nova_imagem)

    caminho_imagem_armazenada = obter_caminho_imagem_usuario(email_usuario)
    if not caminho_imagem_armazenada or not os.path.exists(caminho_imagem_armazenada):
        print("Erro: Imagem armazenada não encontrada.")
        return jsonify({"sucesso": False, "erro": "Imagem armazenada não encontrada"}), 400

    try:
        correspondencia = comparar_imagens(caminho_nova_imagem, caminho_imagem_armazenada)
        print(f"Resultado da comparação: {correspondencia}")
        # Converte o resultado para bool do Python antes de retornar no JSON
        return jsonify({"sucesso": True, "correspondencia": bool(correspondencia)})
    except Exception as e:
        print(f"Erro durante a comparação: {e}")
        return jsonify({"sucesso": False, "erro": str(e)}), 400

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
