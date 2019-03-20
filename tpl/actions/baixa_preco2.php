<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $by_user = $_SESSION['user_id'];
    $produto_espelho = "";
    $data = filter_input(INPUT_POST, 'data_baixa_preco', FILTER_SANITIZE_STRING);
    $data_baixa_preco = implode("-", array_reverse(explode("/", $data))) . " " . date("H:i:s");
    $angariacao_id = filter_input(INPUT_POST, 'angariacao_id', FILTER_SANITIZE_NUMBER_INT);
    $valor_ant = filter_input(INPUT_POST, 'valor_antigo_imv', FILTER_SANITIZE_STRING);
    $valor_antigo = str_replace(".","",$valor_ant);
    $valor_antigo_imv = str_replace(",",".",$valor_antigo);
    $novo_val = filter_input(INPUT_POST, 'novo_valor_imv', FILTER_SANITIZE_STRING);
    $novo_valor = str_replace(".","",$novo_val);
    $novo_valor_imv = str_replace(",",".",$novo_valor);
    $tipo_ang = filter_input(INPUT_POST, 'tipo_ang', FILTER_SANITIZE_NUMBER_INT);
    $tipo_neg = filter_input(INPUT_POST, 'tipo_neg', FILTER_SANITIZE_NUMBER_INT);

//    echo "by_user: " . $by_user . "<br>";
//    echo "data_baixa_preco: " . $data_baixa_preco . "<br>";
//    echo "angariacao_id: " . $angariacao_id . "<br>";
//    echo "valor_antigo: " . $valor_antigo_imv . "<br>";
//    echo "novo_valor_imv: " . $novo_valor_imv . "<br>";
//    echo "tipo_ang: " . $tipo_ang . "<br>";
//    echo "tipo_neg: " . $tipo_neg . "<br>";
//    echo "produto_espelho: " . $produto_espelho . "<br>";
//    echo "<br><hr><br>";
    
    if ($tipo_ang == 1) {
        // habitação
        // echo "É Habitação <br>";
        if ($tipo_neg == 1) {
            // venda
            // echo "É Venda <br>";
            if ($valor_antigo_imv >= 100000.00) {
                // maior ou igual a 100 mil 
                // echo "É maior ou igual a 100 mil euros <br>";
                if ($novo_valor_imv <= $valor_antigo_imv - $valor_antigo_imv * 5 / 100) {
                    // é produto e vai para o espelho
                    // echo "Vai para o espelho porque o novo valor tem um abatimento MAIOR que 5% <br>";
                    $produto_espelho = 1;
                } else {
                    // não é produto e não vai para o espelho
                    // echo "Não vai para o espelho porque o novo valor tem um abatimento MENOR que 5%  <br>";
                    $produto_espelho = 0;
                }
            } else {
                // menor que 100 mil
                // echo "É menor que 100 mil euros <br>";
                if ($novo_valor_imv <= $valor_antigo_imv - 5000.00) {
                    // é produto e vai para o espelho
                    // echo "Vai para o espelho porque o novo valor tem um abatimento MAIOR que 5 mil euros <br>";
                    
                    $produto_espelho = 1;
                } else {
                    // não é produto e não vai para o espelho
                    // echo "Não vai para o espelho porque o novo valor tem um abatimento MENOR que 5 mil euros <br>";
                    $produto_espelho = 0;
                }
            }
        } else {
            // arrendamento
            // echo "É Arrendamento <br>";
            if ($valor_antigo_imv >= 500.00) {
                // maior ou igual a 500
                // echo "É Maior ou Igual a 500 <br>";
                if ($novo_valor_imv <= $valor_antigo_imv - 100.00) {
                    // é produto e vai para o espelho
                    // echo "Vai para o espelho porque o novo valor tem um abatimento MAIOR ou IGUAL a 100 euros <br>";
                    $produto_espelho = 1;
                } else {
                    // não é produto e não vai para o espelho
                    // echo "Não vai para o espelho porque o novo valor tem um abatimento MENOR que 100 euros <br>";
                    $produto_espelho = 0;
                }
            } else {
                // menor que 500
                //echo "É Menor que 500 euros <br>";
                if ($novo_valor_imv <= $valor_antigo_imv - 50.00) {
                    // é produto e vai para o espelho
                    // echo "Vai para o espelho porque o novo valor tem um abatimento MAIOR ou IGUAL a 50 euros <br>";
                    $produto_espelho = 1;
                } else {
                    // não é produto e não vai para o espelho
                    // echo "Não vai para o espelho porque o novo valor tem um abatimento MENOR que 50 euros <br>";
                    $produto_espelho = 0;
                }
            }
        }
    } else {
        // não habitação
        //echo "Não é Habitação <br>";
        $produto_espelho = NULL;
    }

    $ins_nova_baixa = "INSERT INTO baixa_preco (data, angariacao_id, ultimo_valor_imovel, novo_valor_imovel, tipo_angariacao, tipo_negocio, produto_espelho, by_user) 
    VALUES ('$data_baixa_preco', '$angariacao_id', '$valor_antigo_imv', '$novo_valor_imv', '$tipo_ang', '$tipo_neg', '$produto_espelho', '$by_user')";

    if ($data_baixa_preco != "" && $angariacao_id != "" && $valor_antigo_imv != "" && $novo_valor_imv != "" && $tipo_ang != "" && $tipo_neg != "" && $by_user != "") {
        include_once("conexao.php");
        if ($conn->query($ins_nova_baixa) === TRUE) {

            switch ($produto_espelho) {
                case 1:
                    $_SESSION['msg_baixa_preco'] = "<div class='note note-success font-green-haze'><h4 class='block bold'>✔ Baixa de Preço Adicionada com Sucesso!</h4><p> Esta Baixa é Produto.</p></div>";
                    break;
                case 0:
                    $_SESSION['msg_baixa_preco'] = "<div class='note note-success font-green-haze'><h4 class='block bold'>✔ Baixa de Preço Adicionada com Sucesso!</h4><p> Esta Baixa Não é Produto.</p></div>";
                    break;
                default:
                    $_SESSION['msg_baixa_preco'] = "<div class='note note-success font-green-haze'><h4 class='block bold'>✔ Baixa de Preço Adicionada com Sucesso!</h4><p> Esta Baixa Não se Enquadra em Produtos.</p></div>";
            }
            header("Location: ../../coordenacao/baixa-de-preco");
        } else {
            $_SESSION['msg_baixa_preco'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ ERRO NO SISTEMA!</h4><p> Fale com o Programador.</p></div>";
            header("Location: ../../coordenacao/baixa-de-preco");
        }
    } else {
        echo 'caixao';
        $_SESSION['msg_baixa_preco'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ ERRO NO SISTEMA!</h4><p> Só mesmo o Programador.</p></div>";
        header("Location: ../../coordenacao/baixa-de-preco");
    }
    
}