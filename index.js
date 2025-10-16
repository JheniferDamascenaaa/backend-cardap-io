const express = require('express');
const app = express();
const port = 3000;

// Permite que o Express entenda JSON no body das requisições
app.use(express.json());

// Rota de teste
app.get('/', (req, res) => {
    res.send('API funcionando!');
});

// Exemplo de rota de GET
app.get('/usuarios', (req, res) => {
    const usuarios = [
        { id: 1, nome: 'Kamille' },
        { id: 2, nome: 'João' }
    ];
    res.json(usuarios);
});

// Exemplo de rota de POST
app.post('/usuarios', (req, res) => {
    const novoUsuario = req.body; // recebe os dados enviados no body
    res.status(201).json({ mensagem: 'Usuário criado', usuario: novoUsuario });
});

// Inicia o servidor
app.listen(port, () => {
    console.log(`Servidor rodando em http://localhost:${port}`);
});

let usuarios = [
    { id: 1, nome: 'Kamille' },
    { id: 2, nome: 'João' }
];

// Rota de UPDATE (PUT) – atualiza um usuário pelo id
app.put('/usuarios/:id', (req, res) => {
    const id = parseInt(req.params.id);
    const { nome } = req.body;

    const usuario = usuarios.find(u => u.id === id);
    if (!usuario) {
        return res.status(404).json({ mensagem: 'Usuário não encontrado' });
    }

    usuario.nome = nome; // atualiza o nome
    res.json({ mensagem: 'Usuário atualizado', usuario });
});

// Rota de DELETE – remove um usuário pelo id
app.delete('/usuarios/:id', (req, res) => {
    const id = parseInt(req.params.id);

    const index = usuarios.findIndex(u => u.id === id);
    if (index === -1) {
        return res.status(404).json({ mensagem: 'Usuário não encontrado' });
    }

    const usuarioRemovido = usuarios.splice(index, 1); // remove do array
    res.json({ mensagem: 'Usuário removido', usuario: usuarioRemovido[0] });
});
