<?php

session_start();

$by_user = $_SESSION['user_id'];
$tipo_angariacao = $_SESSION['tipo_angariacao'];
$tipo_negocio = $_SESSION['tipo_negocio'];
$espelho = 0;

$data_insercao_ilist = implode("-", array_reverse(explode("/", filter_input(INPUT_POST, 'insercao_ilist', FILTER_SANITIZE_STRING)))) . " " . date("H:i:s");
$id_angariacao_inicial = filter_input(INPUT_POST, 'id_angariacao', FILTER_SANITIZE_STRING);
$id_consultor = filter_input(INPUT_POST, 'id_cst', FILTER_SANITIZE_STRING);
$loja_consultor = filter_input(INPUT_POST, 'loja_cst', FILTER_SANITIZE_NUMBER_INT);
$id_gestor = filter_input(INPUT_POST, 'id_gst', FILTER_SANITIZE_STRING);


$arruamento_imv = filter_input(INPUT_POST, 'arruamento_imv', FILTER_SANITIZE_STRING);
$num_porta_imv = filter_input(INPUT_POST, 'num_porta_imv', FILTER_SANITIZE_STRING);
$complemento_imv = filter_input(INPUT_POST, 'complemento_imv', FILTER_SANITIZE_STRING);
$cod_postal_imv = filter_input(INPUT_POST, 'cod_postal_imv', FILTER_SANITIZE_STRING);
$id_freg_imv = filter_input(INPUT_POST, 'id_freg_imv', FILTER_SANITIZE_NUMBER_INT);
$tipo_imv = filter_input(INPUT_POST, 'tipo_imv', FILTER_SANITIZE_STRING);
$valor_inicial_imv = filter_input(INPUT_POST, 'valor_inicial_imovel', FILTER_SANITIZE_STRING);
$valor_inicial_imovel = str_replace(".","",$valor_inicial_imv);
$estado_imovel = filter_input(INPUT_POST, 'estado_imovel', FILTER_SANITIZE_NUMBER_INT);
$num_contrato = filter_input(INPUT_POST, 'num_contrato', FILTER_SANITIZE_STRING);

$data_cmi = implode("-", array_reverse(explode("/", filter_input(INPUT_POST, 'data_cmi', FILTER_SANITIZE_STRING)))) . " " . date("H:i:s");
$regime = filter_input(INPUT_POST, 'regime', FILTER_SANITIZE_STRING);
$dur_contrato = filter_input(INPUT_POST, 'dur_contrato', FILTER_SANITIZE_NUMBER_INT);
$conservatoria = filter_input(INPUT_POST, 'conservatoria', FILTER_SANITIZE_STRING);
$ficha_crp = filter_input(INPUT_POST, 'ficha_crp', FILTER_SANITIZE_STRING);
$matriz_cpu = filter_input(INPUT_POST, 'matriz_cpu', FILTER_SANITIZE_STRING);
$fracao = filter_input(INPUT_POST, 'fracao', FILTER_SANITIZE_STRING);
$tipo_comissao = filter_input(INPUT_POST, 'tipo_comissao', FILTER_SANITIZE_NUMBER_INT);
$valor_c = filter_input(INPUT_POST, 'valor_comissao', FILTER_SANITIZE_STRING);
$valor_com = str_replace(".","",$valor_c);
$valor_comissao = str_replace(",",".",$valor_com);



$nome_prop = filter_input(INPUT_POST, 'nome_prop', FILTER_SANITIZE_STRING);
$nif_prop = filter_input(INPUT_POST, 'nif_prop', FILTER_SANITIZE_STRING);
$email_prop = filter_input(INPUT_POST, 'email_prop', FILTER_SANITIZE_EMAIL);
$telefone_prop = filter_input(INPUT_POST, 'telefone_prop', FILTER_SANITIZE_NUMBER_INT);
$arruamento_pro = filter_input(INPUT_POST, 'arruamento_pro', FILTER_SANITIZE_STRING);
$num_porta_prop = filter_input(INPUT_POST, 'num_porta_prop', FILTER_SANITIZE_STRING);
$complemento_prop = filter_input(INPUT_POST, 'complemento_prop', FILTER_SANITIZE_STRING);
$cod_postal_prop = filter_input(INPUT_POST, 'cod_postal_prop', FILTER_SANITIZE_STRING);
$id_freg_prop = filter_input(INPUT_POST, 'id_freg_prop', FILTER_SANITIZE_NUMBER_INT);

if($tipo_angariacao == 1){
    $espelho = 1;
}

//  echo "Tipo de Angariação: ".$tipo_angariacao."<br>";
//  echo "Tipo de Negócio: ".$tipo_negocio."<br>";
//  echo "Data de Inserção no Ilist: ".$data_insercao_ilist."<br>";
//  echo "ID Inicial do Imóvel: ".$id_angariacao_inicial."<br>";
//  echo "ID do Consultor: ".$id_consultor."<br>";
//  echo "Loja do Consultor: ".$loja_consultor."<br>";
//  echo "ID do Gestor: ".$id_gestor."<br>";
//  echo "Arruamento do Imóvel: ".$arruamento_imv."<br>";
//  echo "Número da Porta do Imóvel: ".$num_porta_imv."<br>";
//  echo "Andar do Imóvel: ".$complemento_imv."<br>";
//  echo "Código Postal do Imóvel: ".$cod_postal_imv."<br>";
//  echo "ID Freguesia do Imóvel: ".$id_freg_imv."<br>";
//  echo "Tipo do Imóvel: ".$tipo_imv."<br>";
//  echo "Valor Inicial do Imóvel: ".$valor_inicial_imovel."<br>";
//  echo "Estado do Imóvel: ".$estado_imovel."<br>";
//  echo "Número do Contrato: ".$num_contrato."<br>";
//  echo "Data do CMI: ".$data_cmi."<br>";
//  echo "Regime: ".$regime."<br>";
//  echo "Duração do Contrato: ".$dur_contrato."<br>";
//  echo "Conservatória: ".$conservatoria."<br>";
//  echo "Ficha CRP: ".$ficha_crp."<br>";
//  echo "Matriz CPU: ".$matriz_cpu."<br>";
//  echo "Tipo de Comissão: ".$tipo_comissao."<br>";
//  echo "Valor da Comissão: ".$valor_comissao."<br>";
//  echo "Nome do Proprietário: ".$nome_prop."<br>";
//  echo "NIF do Proprietário: ".$nif_prop."<br>";
//  echo "Email do Proprietário: ".$email_prop."<br>";
//  echo "Telefone do Proprietário: ".$telefone_prop."<br>";
//  echo "Aruamento do Proprietário: ".$arruamento_pro."<br>";
//  echo "Número da Porta do Proprietário: ".$num_porta_prop."<br>";
//  echo "Andar do Proprietário: ".$complemento_prop."<br>";
//  echo "Código Postal do Proprietário: ".$cod_postal_prop."<br>";
//  echo "ID Freguesia do Proprietário: ".$id_freg_prop."<br>";

if (
        $tipo_angariacao == "" ||
        $tipo_negocio == "" ||
        $data_insercao_ilist == "" ||
        $id_angariacao_inicial == "" ||
        $id_consultor == "" ||
        $loja_consultor == "" ||
        $id_gestor == "" ||
        $arruamento_imv == "" ||
        $num_porta_imv == "" ||
        $cod_postal_imv == "" ||
        $id_freg_imv == "" ||
        $tipo_imv == "" ||
        $valor_inicial_imovel == "" ||
        $estado_imovel == "" ||
        $num_contrato == "" ||
        $data_cmi == "" ||
        $regime == "" ||
        $dur_contrato == "" ||
        $conservatoria == "" ||
        $ficha_crp == "" ||
        $matriz_cpu == "" ||
        $tipo_comissao == "" ||
        $valor_comissao == "" ||
        $nome_prop == "" ||
        $nif_prop == "" ||
        $email_prop == "" ||
        $telefone_prop == "" ||
        $arruamento_pro == "" ||
        $num_porta_prop == "" ||
        $cod_postal_prop == "" ||
        $id_freg_prop == ""
) {
    $_SESSION['msg_nova_angariacao'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ Atenção ao Prenchimento!</h4><p>Preencha todos os campos obrigatórios.</p></div>";
    header("Location: ../../coordenacao/nova-angariacao");
} else {
    include_once("conexao.php");

    $conn->autocommit(FALSE);

    $ins_ang = "INSERT INTO angariacoes (data_insercao_ilist, freguesia_imovel, arruamento_imovel, num_porta_imovel, complemento_imovel, cod_postal_imovel, tipo_angariacao, tipo_negocio, tipo_imovel, valor_inicial_imovel, espelho) 
    VALUES ('$data_insercao_ilist', '$id_freg_imv', '$arruamento_imv', '$num_porta_imv', '$complemento_imv', '$cod_postal_imv', '$tipo_angariacao', '$tipo_negocio', '$tipo_imv', '$valor_inicial_imovel', '$espelho')";

    if ($conn->query($ins_ang) === TRUE) {
        $last_id_ang = $conn->insert_id;
        $ins_lvr = "INSERT INTO livro_registo (estado_imovel, num_contrato, data_cmi, regime, id_inicial_imovel, angariacao_id, nome_proprietario, nif_proprietario, email_proprietario, telefone_proprietario, arruamento_proprietario, num_porta_proprietario, complemento_proprietario, cod_postal_proprietario, freguesia_proprietario, conservatoria, ficha_crp, matriz_cpu, fracao, tipo_comissao, valor_comissao, data_negocio, data_recisao, valor_negocio, comissao_recebida, duracao_contrato) 
        VALUES ('$estado_imovel', '$num_contrato', '$data_cmi', '$regime', '$id_angariacao_inicial', $last_id_ang, '$nome_prop', '$nif_prop', '$email_prop', '$telefone_prop', '$arruamento_pro', '$num_porta_prop', '$complemento_prop', '$cod_postal_prop', '$id_freg_prop', '$conservatoria', '$ficha_crp', '$matriz_cpu', '$fracao', '$tipo_comissao', '$valor_comissao', NULL, NULL, NULL, NULL, '$dur_contrato')";
        if ($conn->query($ins_lvr) === TRUE) {

            $ins_id_ang = "INSERT INTO id_angariacao (data, angariacao_id, id_imovel, consultor, gestor, loja, an_15, an_60, an_120, an_130) 
            VALUES (NOW(), '$last_id_ang', '$id_angariacao_inicial', '$id_consultor', '$id_gestor', '$loja_consultor', 0, 0, 0, 0)";

            if ($conn->query($ins_id_ang) === TRUE) {
                
                $ins_baixa_preco = "INSERT INTO baixa_preco (data, angariacao_id, ultimo_valor_imovel, novo_valor_imovel, tipo_angariacao, tipo_negocio, produto_espelho, by_user) 
                VALUES (NOW(), '$last_id_ang', '$valor_inicial_imovel', '$valor_inicial_imovel', '$tipo_angariacao', '$tipo_negocio', NULL, '$by_user')";

                if ($conn->query($ins_baixa_preco) === TRUE) {
                    $conn->commit();
                    $_SESSION['msg_nova_angariacao'] = "<div class='note note-success font-green-haze'><h4 class='block bold'>✔ Angariação Inserida com Sucesso!</h4><p>Parabéns por ter Atualizado todos os campos corretamente.</p></div>";
                    header("Location: ../../coordenacao/nova-angariacao");
                } else {
                    $conn->rollback();
                    $_SESSION['msg_nova_angariacao'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ ERRO DE MYSQL!</h4><p>Problema na Tabela baixa_preco.</p></div>";
                    header("Location: ../../coordenacao/nova-angariacao");
                }
            } else {
                $conn->rollback();
                $_SESSION['msg_nova_angariacao'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ ATENÇÃO AO AVISO!</h4><p>Este ID de Imóvel já existe, provavelmente o a sua angariação já foi inserida, por favor verifique.</p></div>";
                header("Location: ../../coordenacao/nova-angariacao");
            }
        } else {
            $conn->rollback();
            $_SESSION['msg_nova_angariacao'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ DADO DUPLICADO!</h4><p>Este ID de Imóvel ou o Número de Contrato Já Existe. Por favor verifique.</p></div>";
            header("Location: ../../coordenacao/nova-angariacao");
        }
    } else {
        $conn->rollback();
        $_SESSION['msg_nova_angariacao'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ ERRO DE MYSQL!</h4><p>Problema na Tabela angariacoes.</p></div>";
        header("Location: ../../coordenacao/nova-angariacao");
    }
}