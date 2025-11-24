<?php
declare(strict_types=1);


class Cliente
{
    private string $cliente;
    private string $cpf;
    
    public function __construct(string $cliente, string $cpf)
    {
        $this->cliente = $cliente;
        $this->cpf = $cpf;
    }
    
    public function getResult(){
        return "Cliente é: ".$this->cliente."\nCPF: ".$this->cpf;
    }
}

function validaCliente(string $texto): string
{
    while(true){
        $valor = trim(readline($texto));
        
        if($valor !== ""){
            return (string) $valor;
        }
        
        echo "Valor invalido, digite novamene \n";
        
    }
}

function validaCpf(string $mensagem): string
{
    while (true) {
        $valor = trim(readline($mensagem));

        // Verifica se não está vazio
        if ($valor !== "") {
            // Verifica se tem 14 caracteres
            if (strlen($valor) === 14) {
                // Verifica se tem 2 pontos e 1 hífen
                if (substr_count($valor, ".") === 2 && substr_count($valor, "-") === 1) {
                    // Remove pontos e hífen e checa se só restam números
                    $somenteNumeros = str_replace([".", "-"], "", $valor);
                    if (ctype_digit($somenteNumeros)) {
                        return $valor; // CPF válido
                    } else {
                        echo "CPF deve conter apenas números, pontos e hífen.\n";
                    }
                } else {
                    echo "CPF deve ter o formato 000.000.000-00 (2 pontos e 1 hífen).\n";
                }
            } else {
                echo "CPF deve conter exatamente 14 caracteres (incluindo pontos e hífen).\n";
            }
        } else {
            echo "CPF inválido, digite novamente.\n";
        }
    }
}
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


// Execução

$cliente = validaCliente("Digite o nome do Cliente: ");
$cpf = validaCpf("Digite o CPF do Cliente: ");

$quest = new Cliente($cliente, $cpf);

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
echo $quest->getResult();
