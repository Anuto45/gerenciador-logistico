-- Esquema equivalente em SQLite (usado apenas para testes automatizados sem MySQL)
CREATE TABLE IF NOT EXISTS produtos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL, descricao TEXT,
    peso_kg REAL NOT NULL DEFAULT 0, valor REAL NOT NULL DEFAULT 0
);
CREATE TABLE IF NOT EXISTS enderecos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    apelido TEXT NOT NULL, cep TEXT NOT NULL, logradouro TEXT NOT NULL, numero TEXT NOT NULL,
    complemento TEXT, bairro TEXT NOT NULL, cidade TEXT NOT NULL, uf TEXT NOT NULL
);
CREATE TABLE IF NOT EXISTS transportadores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL, cnpj TEXT NOT NULL, telefone TEXT, placa_veiculo TEXT
);
CREATE TABLE IF NOT EXISTS fretes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    transportador_id INTEGER NOT NULL REFERENCES transportadores(id),
    origem_id INTEGER NOT NULL REFERENCES enderecos(id),
    destino_id INTEGER NOT NULL REFERENCES enderecos(id),
    data_frete TEXT NOT NULL, observacao TEXT,
    valor_total REAL NOT NULL DEFAULT 0, peso_total REAL NOT NULL DEFAULT 0
);
CREATE TABLE IF NOT EXISTS frete_produtos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    frete_id INTEGER NOT NULL REFERENCES fretes(id) ON DELETE CASCADE,
    produto_id INTEGER NOT NULL REFERENCES produtos(id),
    quantidade INTEGER NOT NULL DEFAULT 1
);
