<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de cadastro</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

        /* http://meyerweb.com/eric/tools/css/reset/ 
        v2.0b1 | 201101 
        NOTE: WORK IN PROGRESS
        USE WITH CAUTION AND TEST WITH ABANDON */

        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, figcaption, figure, 
        footer, header, hgroup, menu, nav, section, summary,
        time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }
        /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure, 
        footer, header, hgroup, menu, nav, section {
            display: block;
        }
        body {
            line-height: 1;
            font-family: 'Roboto', sans-serif;
        }
        ol, ul {
            list-style: none;
        }
        blockquote, q {
            quotes: none;
        }
        blockquote:before, blockquote:after,
        q:before, q:after {
            content: '';
            content: none;
        }
        
        /* remember to define visible focus styles! 
        :focus {
            outline: ?????;
        } */
        
        /* remember to highlight inserts somehow! */
        ins {
            text-decoration: none;
        }
        del {
            text-decoration: line-through;
        }
        
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        body {
            background: #0F1022;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        div {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(315deg, #383745, #161525);
            border-radius: 15px;
            padding: 30px 20px;
            box-shadow: 14px 14px #0A0B1F;
            gap: 16px;
        }
        
        input {
            width: 246px;
            height: 34px;
            background: rgb(90,90,100);
            background: linear-gradient(315deg, rgba(90,90,100,1) 0%, rgba(82,82,94,1) 100%);
            border-radius: 8px;
            border: none;
            color: #C8C8D2;
            padding-left: 21px;
        }

        /*  button {
            width: 246px;
            height: 38px;
            font-weight: medium;
            font-size: 20px;
            letter-spacing: 0.1em;
            cursor: pointer;
            text-align: center;
            padding: 0;  
            margin-top: 5px;
        } */

        button {
            width: 246px;
            height: 38px;
            font-weight: medium;
            font-size: 20px;
            letter-spacing: 0.1em;
            cursor: pointer;
            text-align: center;
            padding: 0;  
            margin-top: 5px;
            text-decoration: none;
            cursor: pointer;
            color: #C8C8D2;
            background: rgb(90,90,100);
            background: linear-gradient(315deg, rgba(90,90,100,1) 0%, rgba(82,82,94,1) 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

    </style>
</head>
<body>

<?php
// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifica se os campos obrigatórios estão presentes e não estão vazios
    $campos_obrigatorios = array("nome", "email", "cpf", "telefone", "senha", "confirmacao_senha", "genero", "data_nascimento", "cidade", "estado", "endereco");
    $campos_vazios = array();
    foreach ($campos_obrigatorios as $campo) {
        if (empty($_POST[$campo])) {
            $campos_vazios[] = $campo;
        }
    }

    // Se houver campos vazios, exibe mensagem de erro
    if (!empty($campos_vazios)) {
        echo '<div style="color: white;">Os seguintes campos são obrigatórios e estão vazios:';
        foreach ($campos_vazios as $campo) {
            echo ' ' . $campo;
        }
        echo '. Por favor, preencha todos os campos obrigatórios. <button onclick="window.history.back();" style="margin-top: 10px;">Voltar</button></div>';
        exit; // Encerra o script
    }

    // Verifica se a senha e a confirmação de senha são iguais
    if ($_POST['senha'] !== $_POST['confirmacao_senha']) {
        echo '<div style="color: white;">As senhas não são iguais. <button onclick="window.history.back();" style="margin-top: 10px;">Voltar</button></div>';
        exit; // Encerra o script se as senhas não forem iguais
    }

    // Conecta ao banco de dados
    $conn = new mysqli("localhost", "root", "", "cadastro_usuarios");

    // Verifica se houve erro na conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Verifica se o CPF já está cadastrado
    $cpf = $_POST['cpf'];
    $check_cpf_query = "SELECT cpf FROM cadastros WHERE cpf = ?";
    $check_stmt = $conn->prepare($check_cpf_query);
    $check_stmt->bind_param("s", $cpf);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        echo '<div style="color: white;">CPF já cadastrado. <button onclick="window.history.back();" style="margin-top: 10px;">Voltar</button></div>';
        exit; // Encerra o script se o CPF já estiver cadastrado
    }

    // Criptografa a senha
    $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Prepara a instrução SQL para inserção
    $sql = "INSERT INTO cadastros (nome, email, cpf, telefone, senha, genero, data_nascimento, cidade, estado, endereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verifica se a preparação foi bem sucedida
    if ($stmt) {
        // Liga os parâmetros com os valores do formulário
        $stmt->bind_param("ssssssssss", $_POST['nome'], $_POST['email'], $_POST['cpf'], $_POST['telefone'], $senha_hash, $_POST['genero'], $_POST['data_nascimento'], $_POST['cidade'], $_POST['estado'], $_POST['endereco']);
        
        // Executa a instrução SQL
        if ($stmt->execute()) {
            echo '<div id="mensagem" style="color: white;">Cadastro realizado com sucesso!</div>';
            echo '<script>
                    // Função para redirecionar para a página de início após 10 segundos
                    setTimeout(function() {
                        window.location.href = "http://localhost/kleyton-acelera-saude_M2/index.html";
                    }, 10000); // 10 segundos
                  </script>';
        } else {
            echo '<div id="mensagem" style="color: white;">Erro ao cadastrar: ' . $conn->error . '</div>';
        }

        // Fecha a instrução
        $stmt->close();
    } else {
        echo '<div style="color: white;">Erro na preparação da instrução: ' . $conn->error . '<button onclick="window.history.back();" style="margin-top: 10px;">Voltar</button></div>';
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>

</body>
</html>
