<?php

/*Integrantes
    Eduardo Nogueira da Silva
    Pedro Henrique Dutra
*/

if ($argc != 2) {
    echo "Uso: php converter.php (pessoa.json | pessoa.xml)\n";
    echo "Para converter inversamente os arquivos";
    exit(1);
}

function ler_xml(string $caminho): array {
    $xml = simplexml_load_file($caminho);
    $valores = [];
    $valores['nome'] = (string) $xml->nome;
    $valores['salario'] = floatval($xml->salario);
    $valores['nascimento'] = (string) $xml->nascimento;
    $valores['dependentes'] = [];

    foreach ($xml->dependentes->dependente as $d) {
        $novo = [];
        $novo['nome'] = (string) $d->nome;
        $novo['nascimento'] = (string) $d->nascimento;
        $valores['dependentes'][] = $novo;
    }
    return $valores;
}

function ler_json(string $caminho): array {
    $arquivo = file_get_contents($caminho);
    $pessoa = json_decode($arquivo, true, 512, JSON_THROW_ON_ERROR);
    return $pessoa;
}

function escrever_json(array $pessoa): string {
    $saida = json_encode($pessoa, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    return $saida;
}

function escrever_xml(array $pessoa): string {
    $w = new XMLWriter();
    $w->openMemory();
    $w->setIndent(true);
    $w->setIndentString(' ');

    $w->startDocument('1.0', 'UTF-8');

    $w->startElement('pessoa');
    $w->writeElement('nome', $pessoa['nome']);
    $w->writeElement('salario', $pessoa['salario']);
    $w->writeElement('nascimento', $pessoa['nascimento']);
    $w->startElement('dependentes');

    foreach ($pessoa['dependentes'] as $d) {
        $w->startElement('dependente');
        $w->writeElement('nome', $d['nome']);
        $w->writeElement('nascimento', $d['nascimento']);
        $w->endElement();
    }
    $w->endElement();
    $w->endElement();
    $w->endDocument();

    $resultado = $w->outputMemory();
    return $resultado;
}

$entrada = $argv[1];
$saida = '';

if (pathinfo($entrada, PATHINFO_EXTENSION) == 'json') {

    echo "\nJSON convertido em XML: \n\n";
    $pessoa = ler_json($entrada);
    $saida = escrever_xml($pessoa);

} elseif (pathinfo($entrada, PATHINFO_EXTENSION) == 'xml') {

    echo "\nXML convertido em JSON: \n\n";
    $pessoa = ler_xml($entrada);
    $saida = escrever_json($pessoa);

} else {
    echo "Este arquivo não é xml e nem json.\n";
    exit(1);
}

echo $saida;

