<?php

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $user_id_loja = $_SESSION['user_id_loja'];

    $num_contrato = filter_input(INPUT_POST, 'num_contrato', FILTER_SANITIZE_STRING);
    $data_contrato = date_format(date_create(str_replace("/", "-", filter_input(INPUT_POST, 'data_contrato', FILTER_SANITIZE_STRING))), "Y-m-d") . date(' H:i:s');
    $estado_contrato = filter_input(INPUT_POST, 'estado_contrato', FILTER_SANITIZE_NUMBER_INT);
    $tipo_regime = filter_input(INPUT_POST, 'tipo_regime', FILTER_SANITIZE_NUMBER_INT);
    $tipo_negocio = filter_input(INPUT_POST, 'tipo_negocio', FILTER_SANITIZE_NUMBER_INT);
    $dur_contrato = filter_input(INPUT_POST, 'dur_contrato', FILTER_SANITIZE_NUMBER_INT);
    $consultor = filter_input(INPUT_POST, 'consultor', FILTER_SANITIZE_NUMBER_INT);
    $nome_cliente = filter_input(INPUT_POST, 'nome_cliente', FILTER_SANITIZE_STRING);
    $nif_cliente = filter_input(INPUT_POST, 'nif_cliente', FILTER_SANITIZE_NUMBER_INT);
    if ($nif_cliente == "") {
        $nif_cliente = 0;
    }
    $email_cliente = filter_input(INPUT_POST, 'email_cliente', FILTER_SANITIZE_STRING);
    if ($email_cliente == "") {
        $email_cliente = 0;
    }
    $telefone_cliente = filter_input(INPUT_POST, 'telefone_cliente', FILTER_SANITIZE_NUMBER_INT);
    if ($telefone_cliente == "") {
        $telefone_cliente = 0;
    }
    $arruamento_cliente = filter_input(INPUT_POST, 'arruamento_cliente', FILTER_SANITIZE_STRING);
    $num_porta_cliente = filter_input(INPUT_POST, 'num_porta', FILTER_SANITIZE_NUMBER_INT);
    $complemento_cliente = filter_input(INPUT_POST, 'complemento', FILTER_SANITIZE_STRING);
    $cod_postal_cliente = filter_input(INPUT_POST, 'cod_postal', FILTER_SANITIZE_STRING);
    $freguesia_cliente = filter_input(INPUT_POST, 'freguesia', FILTER_SANITIZE_NUMBER_INT);

    if (
            $num_contrato == "" ||
            $data_contrato == "" ||
            $estado_contrato == "" ||
            $tipo_regime == "" ||
            $tipo_negocio == "" ||
            $dur_contrato == "" ||
            $consultor == "" ||
            $nome_cliente == "" ||
            $arruamento_cliente == "" ||
            $num_porta_cliente == "" ||
            $cod_postal_cliente == "" ||
            $freguesia_cliente == ""
    ) {
        $_SESSION['msg_cliente_comprador'] = "<div class='alert alert-danger'><strong>Atenção!</strong> Preencha Todos os Campos Obrigatórios. (Marcados com *) </div>";
        header("Location: ../../coordenacao/registar-contrato-cliente-comprador");
    } else {
        include_once("conexao.php");
        $sql_gc = "SELECT gst.gestor id_gst, col.nome nome_gst, colaboradores.id id_cst, colaboradores.nome nome_cst, user_loja.loja loja_cst
        FROM gestor gst
        INNER JOIN colaboradores ON colaboradores.id = gst.user
        INNER JOIN colaboradores col ON col.id = gst.gestor
        INNER JOIN user_loja ON user_loja.user = colaboradores.id
        WHERE gst.user = $consultor and user_loja.loja = $user_id_loja";
        $result_gc = $conn->query($sql_gc);
        if ($result_gc->num_rows > 0) {
            while ($row_gc = $result_gc->fetch_assoc()) {
                $id_gst = $row_gc['id_gst'];
            }
            $conn->autocommit(FALSE);
            $ins_ang = "INSERT INTO angariacoes (data_insercao_ilist, freguesia_imovel, arruamento_imovel, num_porta_imovel, complemento_imovel, cod_postal_imovel, tipo_angariacao, tipo_negocio, tipo_imovel, valor_inicial_imovel, espelho) 
            VALUES (NOW(), '$freguesia_cliente', '$arruamento_cliente', '$num_porta_cliente', '$complemento_cliente', '$cod_postal_cliente', '1', '4', '1', '0.00', '0')";
            if ($conn->query($ins_ang) === TRUE) {
                $last_id_ang = $conn->insert_id;
                $ins_lvr = "INSERT INTO livro_registo (estado_imovel, num_contrato, data_cmi, regime, id_inicial_imovel, angariacao_id, nome_proprietario, nif_proprietario, email_proprietario, telefone_proprietario, arruamento_proprietario, num_porta_proprietario, complemento_proprietario, cod_postal_proprietario, freguesia_proprietario, conservatoria, ficha_crp, matriz_cpu, fracao, tipo_comissao, valor_comissao, data_negocio, data_recisao, valor_negocio, comissao_recebida, duracao_contrato) 
                VALUES ('$estado_contrato', '$num_contrato', '$data_contrato', '$tipo_regime', '$last_id_ang', $last_id_ang, '$nome_cliente', '$nif_cliente', '$email_cliente', '$telefone_cliente', '$arruamento_cliente', '$num_porta_cliente', '$complemento_cliente', '$cod_postal_cliente', '$freguesia_cliente', '0', '0', '0', '0', '2', '0.00', NULL, NULL, NULL, NULL, '$dur_contrato')";
                if ($conn->query($ins_lvr) === TRUE) {
                    $ins_id_ang = "INSERT INTO id_angariacao (data, angariacao_id, id_imovel, consultor, gestor, loja, an_15, an_60, an_120, an_130) 
                    VALUES (NOW(), '$last_id_ang', '$last_id_ang', '$consultor', '$id_gst', '$user_id_loja', 0, 0, 0, 0)";
                    if ($conn->query($ins_id_ang) === TRUE) {
                        $ins_baixa_preco = "INSERT INTO baixa_preco (data, angariacao_id, ultimo_valor_imovel, novo_valor_imovel, tipo_angariacao, tipo_negocio, produto_espelho, by_user) 
                        VALUES (NOW(), '$last_id_ang', '0.00', '0.00', '1', '$tipo_negocio', NULL, '$user_id')";
                        if ($conn->query($ins_baixa_preco) === TRUE) {
                            $conn->commit();
                            $_SESSION['msg_cliente_comprador'] = "<div class='alert alert-success'><strong>Parabéns!</strong> Cliente Comprador inserido com sucesso. </div>";
                            header("Location: ../../coordenacao/registar-contrato-cliente-comprador");
                        } else {
                            $conn->rollback();
                            $_SESSION['msg_cliente_comprador'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ Não inserida!</h4><p>Problema na Tabela baixa_preco.</p></div>";
                            header("Location: ../../coordenacao/registar-contrato-cliente-comprador");
                        }
                    } else {
                        $conn->rollback();
                        $_SESSION['msg_cliente_comprador'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ Não inserida!</h4><p>Problema na Tabela id_angariacao.</p></div>";
                        header("Location: ../../coordenacao/registar-contrato-cliente-comprador");
                    }
                } else {
                    $conn->rollback();
                    $_SESSION['msg_cliente_comprador'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ Não inserida!</h4><p>Problema na Tabela livro_de_registo.</p></div>";
                    header("Location: ../../coordenacao/registar-contrato-cliente-comprador");
                }
            } else {
                $conn->rollback();
                $_SESSION['msg_cliente_comprador'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ Não inserida!</h4><p>Problema na Tabela angariacoes.</p></div>";
                header("Location: ../../coordenacao/registar-contrato-cliente-comprador");
            }
        } else {
            $_SESSION['msg_cliente_comprador'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ Não inserida!</h4><p>Este consultor está sem gestor no sistema, informe o Marketing.</p></div>";
            header("Location: ../../coordenacao/registar-contrato-cliente-comprador");
        }
    }
}