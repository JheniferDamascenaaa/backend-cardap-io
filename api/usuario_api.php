<?php

header('Content-Type: application/json');

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../controllers/usuario_controller.php';

$banco = new Banco();
$conexao = $banco->getConexao();
$usuarioController = new UsuarioController($conexao);

$method = $_SERVER['REQUEST_METHOD'];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($path, '/'));

// Pega o ID SEM query string
$id = end($parts);
$id = is_numeric($id) ? intval($id) : null;

// Lê JSON do corpo da requisição
$input = json_decode(file_get_contents("php://input"), true) ?? [];

switch ($method) {

    case 'GET':
        if ($id) {
            echo json_encode($usuarioController->buscarPorId($id));
        } else {
            echo json_encode($usuarioController->listar());
        }
        break;

case 'POST':

    // UPLOAD DE FOTO (via FormData)
    if (isset($_POST['idUsuario']) && isset($_FILES['foto'])) {

        $idUsuario = intval($_POST['idUsuario']);
        $foto = $_FILES['foto'];

        if ($foto['error'] !== 0) {
            echo json_encode(['success' => false, 'error' => 'Erro ao enviar imagem']);
            exit;
        }

        // Pasta onde salvar as fotos
        $pasta = __DIR__ . "/uploads/fotos_perfil/";
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $nomeArquivo = "perfil_{$idUsuario}_" . time() . ".{$ext}";
        $caminhoCompleto = $pasta . $nomeArquivo;

        if (!move_uploaded_file($foto['tmp_name'], $caminhoCompleto)) {
            echo json_encode(['success' => false, 'error' => 'Falha ao mover arquivo']);
            exit;
        }

        // URL acessível publicamente
        $urlFoto = "/cardapio-back/api/uploads/fotos_perfil/" . $nomeArquivo;

        // Atualiza o banco
        $usuarioController->atualizar($idUsuario, ['fotoPerfil' => $urlFoto]);

        echo json_encode(['success' => true, 'foto' => $urlFoto]);
        exit;
    }

    // LOGIN → email + senha, sem nomeUsuario
    if (isset($input['email'], $input['senha']) && !isset($input['nomeUsuario'])) {
        echo json_encode($usuarioController->login($input['email'], $input['senha']));
        exit;
    }

    // CADASTRO → nomeUsuario, email e senha
    if (isset($input['nomeUsuario'], $input['email'], $input['senha'])) {
        echo json_encode($usuarioController->adicionar($input));
        exit;
    }

    // Dados insuficientes
    echo json_encode(['error' => 'Dados insuficientes para login ou cadastro']);
    break;    
    case 'PUT':
        if ($id) {
            // fotoPerfil pode vir string, null ou nem vir no JSON
            echo json_encode($usuarioController->atualizar($id, $input));
        } else {
            echo json_encode(['error' => 'ID não informado']);
        }
        break;

    case 'DELETE':
        if ($id) {
            echo json_encode($usuarioController->deletar($id));
        } else {
            echo json_encode(['error' => 'ID não informado']);
        }
        break;

    default:
        echo json_encode(['error' => 'Método não permitido']);
}


?>
