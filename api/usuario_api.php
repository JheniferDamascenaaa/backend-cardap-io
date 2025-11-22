<?php

header('Content-Type: application/json');

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../controllers/usuario_controller.php';


$banco = new Banco();
$conexao = $banco->getConexao();
$usuarioController = new UsuarioController($conexao);

// Método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Caminho da URL (ex: /cardapio-back/api/usuario/1)
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($path, '/'));

// O ID SEM query string
$id = end($parts);
$id = is_numeric($id) ? intval($id) : null;

// Lê JSON para POST/PUT/DELETE
$input = json_decode(file_get_contents("php://input"), true) ?? [];

switch ($method) {

    case 'GET':
        if ($id) {
            echo json_encode($usuarioController->buscarPorId($id));
        } else {
            echo json_encode($usuarioController->listar());
        }
        break;

/*    case 'POST':
        echo json_encode($usuarioController->adicionar($_POST ?: $input));
        break; */

    case 'POST':
        // Se vier email e senha, mas sem nomeUsuario → login
        if (isset($input['email'], $input['senha']) && !isset($input['nomeUsuario'])) {
            echo json_encode($usuarioController->login($input['email'], $input['senha']));
            exit; // importante para não executar o restante
        }

        // Se vier nomeUsuario, email e senha → cadastro
        if (isset($input['nomeUsuario'], $input['email'], $input['senha'])) {
            echo json_encode($usuarioController->adicionar($input));
            exit;
        }

        // Se os dados forem insuficientes para login ou cadastro
        echo json_encode(['error' => 'Dados insuficientes para login ou cadastro']);
        break;

    case 'PUT':
        if ($id) {
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
