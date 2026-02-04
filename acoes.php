<?php
session_start();
require 'conexao.php';

if (isset($_POST['create_usuario'])) {

    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($nome === '' || $email === '' || $data_nascimento === '' || $senha === '') {
        $_SESSION['mensagem'] = "Preencha todos os campos!";
        header('Location: index.php');
        exit();
    }

    $nome  = mysqli_real_escape_string($conexao, $nome);
    $email = mysqli_real_escape_string($conexao, $email);
    $data_nascimento = mysqli_real_escape_string($conexao, $data_nascimento);
    $senha = mysqli_real_escape_string(
        $conexao,
        password_hash($senha, PASSWORD_DEFAULT)
    );

    $sql = "INSERT INTO usuarios (nome, email, data_nascimento, senha)
            VALUES ('$nome', '$email', '$data_nascimento', '$senha')";

    if (mysqli_query($conexao, $sql)) {
        $_SESSION['mensagem'] = "Usuário criado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao criar usuário!";
    }

    header('Location: index.php');
    exit();
}

if (isset($_POST['update_usuario'])) {
    $usuario_id = mysqli_real_escape_string($conexao,$_POST['usuario_id']);
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    $nome  = mysqli_real_escape_string($conexao, $nome);
    $email = mysqli_real_escape_string($conexao, $email);
    $data_nascimento = mysqli_real_escape_string($conexao, $data_nascimento);
    $senha = mysqli_real_escape_string($conexao, trim($_POST['senha']));

    $sql = "UPDATE usuarios  SET nome='$nome', email='$email', data_nascimento='$data_nascimento'";
    if (!empty($senha)) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $senha_hash = mysqli_real_escape_string($conexao, $senha_hash);
    $sql .= ", senha='$senha_hash'";
}

    $sql.=" WHERE id = '$usuario_id'";

    if (mysqli_query($conexao, $sql)) {
            $_SESSION['mensagem'] = "Usuário atualizado com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao atualizar usuário!";
        }

    header('Location: index.php');
    exit();
}

if (isset($_POST['delete_usuario'])){
    $usuario_id=mysqli_real_escape_string($conexao, $_POST['delete_usuario']);
    $sql = "DELETE FROM usuarios WHERE id ='$usuario_id' ";
    mysqli_query($conexao, $sql);

    if(mysqli_affected_rows( $conexao) > 0){
        $_SESSION['message'] = "Usuário deletado com sucesso";
        header('Location: index.php');
        exit;
    }else{
        $_SESSION['message'] = "Usuário não deletado";
        header('Location: index.php');
        exit;
    }
}
