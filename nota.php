<?php
declare(strict_types=1);

class Item
{
    private ArrayObject $lista;

    public function __construct()
    {
        $this->lista = new ArrayObject();
    }

    public function adicionar(
        string $codigo,
        string $descricao,
        float $quantidade,
        float $valor_unidade
    ): void {
        $this->lista->append([
            "codigo"        => $codigo,
            "descricao"     => $descricao,
            "quantidade"    => $quantidade,
            "valor_unidade" => $valor_unidade,
            "valor_total"   => $quantidade * $valor_unidade
        ]);
    }

    public function getLista(): ArrayObject
    {
        return $this->lista;
    }
}


//FUNÇÕES AUXILIARES 


function lerNumero(string $mensagem): float
{
    while (true) {
        $valor = readline($mensagem);

        if (is_numeric($valor)) {
            return (float)$valor;
        }

        echo "Valor inválido! Digite apenas números.\n";
    }
}

function lerTexto(string $mensagem): string
{
    while (true) {
        $valor = trim(readline($mensagem));

        if ($valor !== "") {
            return $valor;
        }

        echo "Entrada vazia. Tente novamente.\n";
    }
}

function desejaContinuar(string $mensagem): bool
{
    $resp = strtolower(trim(readline($mensagem)));
    return $resp === "s";
}


// EXECUÇÃO 


$carrinho = new Item();

while (desejaContinuar("Deseja cadastrar item? (S/N): ")) {

    $codigo        = lerTexto("Digite o código: ");
    $descricao     = lerTexto("Digite a descrição do produto: ");
    $quantidade    = lerNumero("Digite a quantidade: ");
    $valorUnidade  = lerNumero("Digite o valor unitário: ");

    $carrinho->adicionar($codigo, $descricao, $quantidade, $valorUnidade);
}


//IMPRESSÃO DA NOTA 


$lista = $carrinho->getLista();

echo "\n
    MERCADO SONHO DO POVO
    Rua A, Nº Y – Bairro B – Cidade/UF
    CNPJ: 00.000.000/0000-00
";
echo "=======================================================================\n";
echo "COD    DESCRIÇÃO                    QTD      VAL UNIT        TOTAL\n";
echo "-----------------------------------------------------------------------\n";

$valor_bruto = 0;

foreach ($lista as $item) {

    $cod  = str_pad(substr($item['codigo'], 0, 6), 6, " ");
    $desc = str_pad(substr($item['descricao'], 0, 22), 22, " ");
    $qtd  = str_pad(number_format($item['quantidade'], 2, ",", "."), 8, " ", STR_PAD_LEFT);

    $v_unit  = "R$ " . number_format($item['valor_unidade'], 2, ",", ".");
    $v_unit  = str_pad($v_unit, 14, " ", STR_PAD_LEFT);

    $v_total = "R$ " . number_format($item['valor_total'], 2, ",", ".");
    $v_total = str_pad($v_total, 14, " ", STR_PAD_LEFT);

    echo "$cod $desc $qtd $v_unit $v_total\n";

    $valor_bruto += $item['valor_total'];
}

echo "=======================================================================\n";
echo "Total da compra: R$ " . number_format($valor_bruto, 2, ",", ".") . "\n";
