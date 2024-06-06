<?php
// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos de nome e senha estão definidos
    if (isset($_POST['nome'], $_POST['senha'])) {
        // Obtenha as credenciais enviadas pelo formulário
        $nome = $_POST['nome'];
        $senha = $_POST['senha'];
        
        // Exemplo de conexão com o banco de dados
        $conn = new mysqli("localhost", "root", "", "cadastro_usuarios");
        
        // Verifica se houve erro na conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }
        
        // Prepara a instrução SQL para verificar as credenciais
        $sql = "SELECT * FROM cadastros WHERE nome_usuario = ?";
        $stmt = $conn->prepare($sql);
        
        // Verifica se a preparação foi bem sucedida
        if ($stmt) {
            // Liga os parâmetros com os valores do formulário
            $stmt->bind_param("s", $nome);
            
            // Executa a consulta
            $stmt->execute();
            
            // Verifica se o usuário foi encontrado
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Obtém a linha associada ao usuário
                $user = $result->fetch_assoc();
                // Verifica se a senha corresponde
                if (password_verify($senha, $user['senha'])) {
                    echo "Login bem-sucedido!"; // Autenticação bem-sucedida
                } else {
                    echo "Nome de usuário ou senha inválidos."; // Credenciais inválidas
                }
            } else {
                echo "Nome de usuário ou senha inválidos."; // Credenciais inválidas
            }
            
            // Fecha a instrução
            $stmt->close();
        } else {
            echo "Erro na preparação da instrução: " . $conn->error;
        }
        
        // Fecha a conexão com o banco de dados
        $conn->close();
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }
}
?>
