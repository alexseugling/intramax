<?php

session_start();
if (isset($_POST['send_nc'])) {
    $num_contrato = filter_input(INPUT_POST, 'num_contrato', FILTER_SANITIZE_STRING);
    if ($num_contrato == "") {
        $_SESSION['msg_upd_janeiro'] = "<div class='alert alert-block alert-danger fade in'><button type='button' class='close' data-dismiss='alert'></button><p>O Número de Contrato É Obrigatório.</p></div>";
        header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018");
    } else {
        include_once("conexao.php");
        $sql = "SELECT num_contrato FROM livro_registo WHERE num_contrato = '$num_contrato'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $nc = $row['num_contrato'];
            }
            $_SESSION['send'] = "session_send";
            $_SESSION['filtro'] = "hidden";
            $_SESSION['abas'] = "";
            $_SESSION['nc'] = $nc;
            header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018");
        } else {
            $_SESSION['msg_upd_janeiro'] = "<div class='alert alert-block alert-danger fade in'><button type='button' class='close' data-dismiss='alert'></button><p>Este Número de Contrato Não Existe!</p></div>";
            header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018");
        }
    }
} elseif (isset($_POST['first'])) {
    $idi = filter_input(INPUT_POST, 'idi', FILTER_SANITIZE_STRING);
    $ida = filter_input(INPUT_POST, 'ida', FILTER_SANITIZE_STRING);
    $_SESSION['g_id_consultor'] = filter_input(INPUT_POST, 'id_consultor', FILTER_SANITIZE_STRING);
    $_SESSION['g_data_insercao_ilist'] = filter_input(INPUT_POST, 'data_insercao_ilist', FILTER_SANITIZE_STRING);
    $_SESSION['g_tipo_angariacao'] = filter_input(INPUT_POST, 'tipo_angariacao', FILTER_SANITIZE_NUMBER_INT);
    $_SESSION['g_tipo_negocio'] = filter_input(INPUT_POST, 'tipo_negocio', FILTER_SANITIZE_NUMBER_INT);

    $_SESSION['session_lastone'] = "session_lastone";
    header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018/{$idi}/{$ida}");
} elseif (isset($_POST['upd_info'])) {

    $by_user = $_SESSION['user_id'];
    $tipo_angariacao = filter_input(INPUT_POST, 'tipo_angariacao', FILTER_SANITIZE_NUMBER_INT);
    $tipo_negocio = filter_input(INPUT_POST, 'tipo_negocio', FILTER_SANITIZE_NUMBER_INT);
    $ida = filter_input(INPUT_POST, 'ida', FILTER_SANITIZE_STRING);
    $idi = filter_input(INPUT_POST, 'idi', FILTER_SANITIZE_STRING);

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
    $valor_inicial_imovel = str_replace(".", "", $valor_inicial_imv);
    $estado_imovel = filter_input(INPUT_POST, 'estado_imovel', FILTER_SANITIZE_NUMBER_INT);
    $num_contrato = filter_input(INPUT_POST, 'num_contrato', FILTER_SANITIZE_STRING);

    $data_cmi = implode("-", array_reverse(explode("/", filter_input(INPUT_POST, 'data_cmi', FILTER_SANITIZE_STRING)))) . " " . date("H:i:s");
    $regime = filter_input(INPUT_POST, 'regime', FILTER_SANITIZE_STRING);
    $dur_contrato = filter_input(INPUT_POST, 'dur_contrato', FILTER_SANITIZE_NUMBER_INT);
    $conservatoria = filter_input(INPUT_POST, 'conservatoria', FILTER_SANITIZE_STRING);
    $ficha_crp = filter_input(INPUT_POST, 'ficha_crp', FILTER_SANITIZE_STRING);
    $matriz_cpu = filter_input(INPUT_POST, 'matriz_cpu', FILTER_SANITIZE_STRING);
    $tipo_comissao = filter_input(INPUT_POST, 'tipo_comissao', FILTER_SANITIZE_NUMBER_INT);
    $valor_c = filter_input(INPUT_POST, 'valor_comissao', FILTER_SANITIZE_STRING);
    $valor_com = str_replace(".", "", $valor_c);
    $valor_comissao = str_replace(",", ".", $valor_com);

    $nome_prop = filter_input(INPUT_POST, 'nome_prop', FILTER_SANITIZE_STRING);
    $nif_prop = filter_input(INPUT_POST, 'nif_prop', FILTER_SANITIZE_STRING);
    $email_prop = filter_input(INPUT_POST, 'email_prop', FILTER_SANITIZE_EMAIL);
    $telefone_prop = filter_input(INPUT_POST, 'telefone_prop', FILTER_SANITIZE_NUMBER_INT);
    $arruamento_pro = filter_input(INPUT_POST, 'arruamento_pro', FILTER_SANITIZE_STRING);
    $num_porta_prop = filter_input(INPUT_POST, 'num_porta_prop', FILTER_SANITIZE_STRING);
    $complemento_prop = filter_input(INPUT_POST, 'complemento_prop', FILTER_SANITIZE_STRING);
    $cod_postal_prop = filter_input(INPUT_POST, 'cod_postal_prop', FILTER_SANITIZE_STRING);
    $id_freg_prop = filter_input(INPUT_POST, 'id_freg_prop', FILTER_SANITIZE_NUMBER_INT);

//    echo "Tipo de Angariação: " . $tipo_angariacao . "<br>";
//    echo "Tipo de Negócio: " . $tipo_negocio . "<br>";
//    echo "Data de Inserção no Ilist: " . $data_insercao_ilist . "<br>";
//    echo "ID Inicial do Imóvel: " . $id_angariacao_inicial . "<br>";
//    echo "ID do Consultor: " . $id_consultor . "<br>";
//    echo "Loja do Consultor: " . $loja_consultor . "<br>";
//    echo "ID do Gestor: " . $id_gestor . "<br>";
//    echo "Arruamento do Imóvel: " . $arruamento_imv . "<br>";
//    echo "Número da Porta do Imóvel: " . $num_porta_imv . "<br>";
//    echo "Andar do Imóvel: " . $complemento_imv . "<br>";
//    echo "Código Postal do Imóvel: " . $cod_postal_imv . "<br>";
//    echo "ID Freguesia do Imóvel: " . $id_freg_imv . "<br>";
//    echo "Tipo do Imóvel: " . $tipo_imv . "<br>";
//    echo "Valor Inicial do Imóvel: " . $valor_inicial_imovel . "<br>";
//    echo "Estado do Imóvel: " . $estado_imovel . "<br>";
//    echo "Número do Contrato: " . $num_contrato . "<br>";
//    echo "Data do CMI: " . $data_cmi . "<br>";
//    echo "Regime: " . $regime . "<br>";
//    echo "Duração do Contrato: " . $dur_contrato . "<br>";
//    echo "Conservatória: " . $conservatoria . "<br>";
//    echo "Ficha CRP: " . $ficha_crp . "<br>";
//    echo "Matriz CPU: " . $matriz_cpu . "<br>";
//    echo "Tipo de Comissão: " . $tipo_comissao . "<br>";
//    echo "Valor da Comissão: " . $valor_comissao . "<br>";
//    echo "Nome do Proprietário: " . $nome_prop . "<br>";
//    echo "NIF do Proprietário: " . $nif_prop . "<br>";
//    echo "Email do Proprietário: " . $email_prop . "<br>";
//    echo "Telefone do Proprietário: " . $telefone_prop . "<br>";
//    echo "Aruamento do Proprietário: " . $arruamento_pro . "<br>";
//    echo "Número da Porta do Proprietário: " . $num_porta_prop . "<br>";
//    echo "Andar do Proprietário: " . $complemento_prop . "<br>";
//    echo "Código Postal do Proprietário: " . $cod_postal_prop . "<br>";
//    echo "ID Freguesia do Proprietário: " . $id_freg_prop . "<br>";
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
        $_SESSION['msg_upd_janeiro'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ Atenção ao Prenchimento!</h4><p>Preencha todos os campos obrigatórios.</p></div>";
        header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018");
    } else {
        include_once("conexao.php");

        if ($tipo_angariacao == 1) {
            $espelho = 1;
        }

        $conn->autocommit(FALSE);

        $upd_ang = "UPDATE angariacoes SET data_insercao_ilist='$data_insercao_ilist', freguesia_imovel='$id_freg_imv', 
        arruamento_imovel='$arruamento_imv', num_porta_imovel='$num_porta_imv', complemento_imovel='$complemento_imv', 
        cod_postal_imovel='$cod_postal_imv', tipo_angariacao='$tipo_angariacao', tipo_negocio='$tipo_negocio', 
        tipo_imovel='$tipo_imv', valor_inicial_imovel='$valor_inicial_imovel', espelho='$espelho' WHERE id='$ida'";

        if ($conn->query($upd_ang) === TRUE) {
            $upd_lvr = "UPDATE livro_registo SET estado_imovel='$estado_imovel', num_contrato='$num_contrato', 
            data_cmi='$data_cmi', regime='$regime', id_inicial_imovel='$id_angariacao_inicial', angariacao_id='$ida', 
            nome_proprietario='$nome_prop', nif_proprietario='$nif_prop', email_proprietario='$email_prop', 
            telefone_proprietario='$telefone_prop', arruamento_proprietario='$arruamento_pro', num_porta_proprietario='$num_porta_prop', 
            complemento_proprietario='$complemento_prop', cod_postal_proprietario='$cod_postal_prop', freguesia_proprietario='$id_freg_prop', 
            conservatoria='$conservatoria', ficha_crp='$ficha_crp', matriz_cpu='$matriz_cpu', tipo_comissao='$tipo_comissao', valor_comissao='$valor_comissao', 
            data_negocio=NULL, data_recisao=NULL, valor_negocio=NULL, comissao_recebida=NULL WHERE angariacao_id='$ida'";

            if ($conn->query($upd_lvr) === TRUE) {

                $upd_id_ang = "UPDATE id_angariacao SET data='$data_insercao_ilist', angariacao_id='$ida', id_imovel='$id_angariacao_inicial', 
                consultor='$id_consultor', gestor='$id_gestor', loja='$loja_consultor', an_15=0, an_60=0, an_120=0, an_130=0 WHERE angariacao_id='$ida'";

                if ($conn->query($upd_id_ang) === TRUE) {

                    $upd_baixa_preco = "UPDATE baixa_preco SET data='$data_insercao_ilist', angariacao_id='$ida', 
                    ultimo_valor_imovel='$valor_inicial_imovel', novo_valor_imovel='$valor_inicial_imovel', 
                    tipo_angariacao='$tipo_angariacao', tipo_negocio='$tipo_negocio', produto_espelho=NULL, 
                    by_user='$by_user' WHERE angariacao_id='$ida'";

                    if ($conn->query($upd_baixa_preco) === TRUE) {
                        $conn->commit();
                        $_SESSION['terminado'] = "session_terminado";
                        $_SESSION['msg_upd_janeiro'] = "<div class='note note-success font-green-haze'><h4 class='block bold'>✔ Angariação Atualizada com Sucesso!</h4><p>Parabéns por ter Atualizado todos os campos corretamente.</p></div>";
                        header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018/{$id_angariacao_inicial}/{$ida}");

//                        echo "$upd_ang<br>";
//                        echo "$upd_lvr<br>";
//                        echo "$upd_id_ang<br>";
//                        echo "$upd_baixa_preco<br>";
                    } else {
                        $conn->rollback();
                        $_SESSION['msg_upd_janeiro'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ ERRO 283. </h4><p>Problema na Tabela baixa_preco.</p></div>";
                        header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018");
                    }
                } else {
                    $conn->rollback();
                    $_SESSION['msg_upd_janeiro'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ ERRO 961.</h4><p> Problema na Tabela id_angariacao.</p></div>";
                    header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018");
                }
            } else {
                $conn->rollback();
                $_SESSION['msg_upd_janeiro'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ ERRO 832.</h4><p> Problema na Tabela livro_registo.</p></div>";
                header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018");
            }
        } else {
            $conn->rollback();
            $_SESSION['msg_upd_janeiro'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ ERRO 44.</h4><p> Problema na Tabela angariacoes.</p></div>";
            header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018");
        }
    }
} else {
    $_SESSION['msg_upd_janeiro'] = "<div class='alert alert-block alert-danger fade in'><button type='button' class='close' data-dismiss='alert'></button><p>Hacker not allowed!</p></div>";
    header("Location: ../../coordenacao/atualizar-imoveis-janeiro-2018");
}
