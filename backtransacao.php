<?php
session_start();
include("conexao.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit();
}

// Só processa se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario   = $_SESSION["usuario_id"];
    $valor     = $_POST['valor'] ?? '';      // se não existir, pega vazio
    $descricao = $_POST['descricao'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $tipo      = $_POST['tipo'] ?? '';
    $data      = date("Y-m-d");

    if (empty($valor) || empty($categoria)) {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO tbl_transacao (usuario_id, valor, descricao, categoria_nome, data, tipo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("idssss", $usuario, $valor, $descricao, $categoria, $data, $tipo);

    if ($stmt->execute()) {
        echo "<script>alert('Adicionado Com Sucesso.'); window.history.back();</script>";
    } else {
        echo "<script>alert('Erro ao enviar os dados'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
