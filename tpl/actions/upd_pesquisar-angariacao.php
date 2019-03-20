<?php

session_start();

if (!isset($_SESSION['pesq_ang_id_imv'])) {
    unset($_SESSION['show_form']);
    header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel");
} else {
    $agora = date('d/m/Y - H:i:s');
    $historico_antes = $_SESSION['historico_antes'];
    //unset($_SESSION['historico_antes']);
    //Campos escondidos
    $angariacao_id = filter_input(INPUT_POST, 'angariacao_id', FILTER_SANITIZE_NUMBER_INT);
    $id_imovel_procurado = filter_input(INPUT_POST, 'id_imovel_procurado', FILTER_SANITIZE_STRING);
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $user_nome = filter_input(INPUT_POST, 'user_nome', FILTER_SANITIZE_STRING);
    $user_id_loja = filter_input(INPUT_POST, 'user_id_loja', FILTER_SANITIZE_NUMBER_INT);
    $user_nome_loja = filter_input(INPUT_POST, 'user_nome_loja', FILTER_SANITIZE_STRING);

    //Dados contratuais
    $num_contrato = filter_input(INPUT_POST, 'num_contrato', FILTER_SANITIZE_STRING);
    $id_atual_imovel = filter_input(INPUT_POST, 'id_atual_imovel', FILTER_SANITIZE_STRING);
    $id_inicial_imovel = filter_input(INPUT_POST, 'id_inicial_imovel', FILTER_SANITIZE_STRING);
    $id_consultor = filter_input(INPUT_POST, 'id_consultor', FILTER_SANITIZE_NUMBER_INT);
    include_once './conexao.php';

    $sql_gc = "
        SELECT gst.gestor id_gst, col.nome nome_gst, colaboradores.id id_cst, colaboradores.nome nome_cst, user_loja.loja loja_cst
        FROM gestor gst
        INNER JOIN colaboradores ON colaboradores.id = gst.user
        INNER JOIN colaboradores col ON col.id = gst.gestor
        INNER JOIN user_loja ON user_loja.user = colaboradores.id
        WHERE gst.user = $id_consultor and user_loja.loja = $user_id_loja
    ";
    $result_gc = mysqli_query($conn, $sql_gc);
    if (mysqli_num_rows($result_gc) > 0) {
        while ($row_gc = mysqli_fetch_assoc($result_gc)) {
            $id_gestor = $row_gc["id_gst"];
            $nome_gestor = $row_gc["nome_gst"];
            $nome_consultor = $row_gc["nome_cst"];
            $id_loja_consultor = $row_gc["loja_cst"];
        }
    } else {
        echo "Erro fatal 999, informe o Marketing";
        //echo $sql_gc;
        exit();
    }
    $espelho_angariacao = filter_input(INPUT_POST, 'espelho_angariacao', FILTER_SANITIZE_NUMBER_INT);
    $duracao_contrato = filter_input(INPUT_POST, 'dur_contrato', FILTER_SANITIZE_NUMBER_INT);
    $data_insercao_ilist = date_format(date_create(str_replace("/", "-", filter_input(INPUT_POST, 'insercao_ilist', FILTER_SANITIZE_STRING))), "Y-m-d") . date(' H:i:s');
    $data_cmi = date_format(date_create(str_replace("/", "-", filter_input(INPUT_POST, 'data_cmi', FILTER_SANITIZE_STRING))), "Y-m-d") . date(' H:i:s');
    $regime = filter_input(INPUT_POST, 'regime', FILTER_SANITIZE_NUMBER_INT);
    $tipo_comissao = filter_input(INPUT_POST, 'tipo_comissao', FILTER_SANITIZE_NUMBER_INT);
    $valor_comi = filter_input(INPUT_POST, 'valor_comissao', FILTER_SANITIZE_STRING);
    $valor_comiss = str_replace(".", "", $valor_comi);
    $valor_comissao = str_replace(",", ".", $valor_comiss);
    $tipo_angariacao = filter_input(INPUT_POST, 'tipo_angariacao', FILTER_SANITIZE_NUMBER_INT);
    $tipo_negocio = filter_input(INPUT_POST, 'tipo_negocio', FILTER_SANITIZE_NUMBER_INT);
    $estado_imovel = filter_input(INPUT_POST, 'estado_imovel', FILTER_SANITIZE_NUMBER_INT);
    $data_negocio = str_replace("/", "-", filter_input(INPUT_POST, 'data_negocio', FILTER_SANITIZE_STRING));
    if (!empty($data_negocio)) {
        $data_negocio2 = date_format(date_create($data_negocio), "Y-m-d") . date(' H:i:s');
        $data_negocio = "'$data_negocio2'";
    } else {
        $data_negocio = "NULL";
    }
    $data_rescisao = str_replace("/", "-", filter_input(INPUT_POST, 'data_rescisao', FILTER_SANITIZE_STRING));
    if (!empty($data_rescisao)) {
        $data_rescisao2 = date_format(date_create($data_rescisao), "Y-m-d") . date(' H:i:s');
        $data_rescisao = "'$data_rescisao2'";
    } else {
        $data_rescisao = "NULL";
    }
    $valor_neg = filter_input(INPUT_POST, 'valor_negocio', FILTER_SANITIZE_STRING);
    if (!empty($valor_neg)) {
        $valor_negoc = str_replace(".", "", $valor_neg);
        $valor_negocio = str_replace(",", ".", $valor_negoc);
    } else {
        $valor_negocio = "0.00";
    }
    $comissao_rec = filter_input(INPUT_POST, 'comissao_recebida', FILTER_SANITIZE_STRING);
    if (!empty($comissao_rec)) {
        $comissao_receb = str_replace(".", "", $comissao_rec);
        $comissao_recebida = str_replace(",", ".", $comissao_receb);
    } else {
        $comissao_recebida = "0.00";
    }

    //dados do imóvel
    $arruamento_imv = filter_input(INPUT_POST, 'arruamento_imv', FILTER_SANITIZE_STRING);
    $num_porta_imv = filter_input(INPUT_POST, 'num_porta_imv', FILTER_SANITIZE_STRING);
    $complemento_imv = filter_input(INPUT_POST, 'complemento_imv', FILTER_SANITIZE_STRING);
    $cod_postal_imv = filter_input(INPUT_POST, 'cod_postal_imv', FILTER_SANITIZE_STRING);
    $id_freg_imv = filter_input(INPUT_POST, 'id_freg_imv', FILTER_SANITIZE_NUMBER_INT);
    $id_tipo_imovel = filter_input(INPUT_POST, 'tipo_imovel', FILTER_SANITIZE_NUMBER_INT);
    $valor_atual_imo = filter_input(INPUT_POST, 'valor_atual_imovel', FILTER_SANITIZE_STRING);
    $valor_atual_imov = str_replace(".", "", $valor_atual_imo);
    $valor_atual_imovel = str_replace(",", ".", $valor_atual_imov);
    $valor_inicial_imo = filter_input(INPUT_POST, 'valor_inicial_imovel', FILTER_SANITIZE_STRING);
    $valor_inicial_imov = str_replace(".", "", $valor_inicial_imo);
    $valor_inicial_imovel = str_replace(",", ".", $valor_inicial_imov);
    $conservatoria = filter_input(INPUT_POST, 'conservatoria', FILTER_SANITIZE_STRING);
    $ficha_crp = filter_input(INPUT_POST, 'ficha_crp', FILTER_SANITIZE_STRING);
    $matriz_cpu = filter_input(INPUT_POST, 'matriz_cpu', FILTER_SANITIZE_STRING);
    $fracao = filter_input(INPUT_POST, 'fracao', FILTER_SANITIZE_STRING);

    //dados do proprietário
    $nome_prop = filter_input(INPUT_POST, 'nome_prop', FILTER_SANITIZE_STRING);
    $nif_prop = filter_input(INPUT_POST, 'nif_prop', FILTER_SANITIZE_NUMBER_INT);
    $email_prop = filter_input(INPUT_POST, 'email_prop', FILTER_SANITIZE_EMAIL);
    $telefone_prop = filter_input(INPUT_POST, 'telefone_prop', FILTER_SANITIZE_NUMBER_INT);
    $arruamento_prop = filter_input(INPUT_POST, 'arruamento_prop', FILTER_SANITIZE_STRING);
    $num_porta_prop = filter_input(INPUT_POST, 'num_porta_prop', FILTER_SANITIZE_STRING);
    $complemento_prop = filter_input(INPUT_POST, 'complemento_prop', FILTER_SANITIZE_STRING);
    $cod_postal_prop = filter_input(INPUT_POST, 'cod_postal_prop', FILTER_SANITIZE_STRING);
    $id_freg_prop = filter_input(INPUT_POST, 'id_freg_prop', FILTER_SANITIZE_NUMBER_INT);

    //variável histórico depois da alteração
    $historico_depois = "
        Informações do dia $agora DEPOIS de ser alterado por $user_nome - Colaborador(a) com o id $user_id na loja $user_nome_loja<br>
        angariacao_id: $angariacao_id<br>
        id_atual_imovel: $id_atual_imovel<br>
        id_inicial_imovel: $id_inicial_imovel<br>
        data_insercao_ilist: $data_insercao_ilist<br>
        id_consultor: $id_consultor<br>
        nome_consultor: $nome_consultor<br>
        id_gestor: $id_gestor<br>
        nome_gestor: $nome_gestor<br>
        loja: $id_loja_consultor<br>
        id_tipo_angariacao: $tipo_angariacao<br>
        id_tipo_negocio: $tipo_negocio<br>
        arruamento_imovel: $arruamento_imv<br>
        num_porta_imovel: $num_porta_imv<br>
        complemento_imovel: $complemento_imv<br>
        cod_postal_imovel: $cod_postal_imv<br>
        id_freguesia_imovel: $id_freg_imv<br>
        id_tipo_imovel: $id_tipo_imovel<br>
        valor_inicial_imovel: $valor_inicial_imovel<br>
        valor_atual_imovel: $valor_atual_imovel<br>
        id_estado_imovel: $estado_imovel<br>
        num_contrato: $num_contrato<br>
        data_cmi: $data_cmi<br>
        id_regime: $regime<br>
        duracao_contrato: $duracao_contrato<br>
        conservatoria: $conservatoria<br>
        ficha_crp: $ficha_crp<br>
        matriz_cpu: $matriz_cpu<br>
        fracao: $fracao<br>
        id_tipo_comissao: $tipo_comissao<br>
        valor_comissao: $valor_comissao<br>
        data_negocio: $data_negocio2<br>
        data_recisao: $data_rescisao2<br>
        valor_negocio: $valor_negocio<br>
        comissao_recebida: $comissao_recebida<br>
        nome_proprietario: $nome_prop<br>
        nif_proprietario: $nif_prop<br>
        email_proprietario: $email_prop<br>
        telefone_proprietario: $telefone_prop<br>
        arruamento_proprietario: $arruamento_prop<br>
        num_porta_proprietario: $num_porta_prop<br>
        complemento_proprietario: $complemento_prop<br>
        cod_postal_proprietario: $cod_postal_prop<br>
        id_freguesia_proprietario: $id_freg_prop<br>
        espelho: $espelho_angariacao<br><br>
    ";

//    echo "Numero de contrato: " . $num_contrato . "<br>";
//    echo "angariação id: " . $angariacao_id . "<br>";
//    echo "id atual: " . $id_atual_imovel . "<br>";
//    echo "id inicial: " . $id_inicial_imovel . "<br>";
//    echo "id consultor: " . $id_consultor . "<br>";
//    echo "id do gestor: " . $id_gestor . "<br>";
//    echo "loja do consultor: " . $user_id_loja . "<br>";
//    echo "espelho: " . $espelho_angariacao . "<br>";
//    echo "dur contrato: " . $duracao_contrato . "<br>";
//    echo "data ilist: " . $data_insercao_ilist . "<br>";
//    echo "data cmi: " . $data_cmi . "<br>";
//    echo "regime: " . $regime . "<br>";
//    echo "tipo comissao: " . $tipo_comissao . "<br>";
//    echo "valor comissao: " . $valor_comissao . "<br>";
//    echo "tipo angariacao: " . $tipo_angariacao . "<br>";
//    echo "tipo negocio: " . $tipo_negocio . "<br>";
//    echo "estado imovel: " . $estado_imovel . "<br>";
//    echo "data negocio: " . $data_negocio . "<br>";
//    echo "data rescisao: " . $data_rescisao . "<br>";
//    echo "valor do negocio: " . $valor_negocio . "<br>";
//    echo "comissao recebida: " . $comissao_recebida . "<br>";
//    echo "arruamento do imovel: " . $arruamento_imv . "<br>";
//    echo "num porta imovel: " . $num_porta_imv . "<br>";
//    echo "complemento imovel: " . $complemento_imv . "<br>";
//    echo "Código Postal imovel: " . $cod_postal_imv . "<br>";
//    echo "id freguesia imovel: " . $id_freg_imv . "<br>";
//    echo "id tipo imovel: " . $id_tipo_imovel . "<br>";
//    echo "valor atual imovel: " . $valor_atual_imovel . "<br>";
//    echo "valor inicial imovel: " . $valor_inicial_imovel . "<br>";
//    echo "conservatoria imovel: " . $conservatoria . "<br>";
//    echo "ficha imovel: " . $ficha_crp . "<br>";
//    echo "matriz imovel: " . $matriz_cpu . "<br>";
//    echo "fracao imovel: " . $fracao . "<br>";
//    echo "Nome do proprietario: " . $nome_prop . "<br>";
//    echo "NIF do proprietario: " . $nif_prop . "<br>";
//    echo "Email do proprietario: " . $email_prop . "<br>";
//    echo "Telefone do proprietario: " . $telefone_prop . "<br>";
//    echo "Arruamento do proprietario: " . $arruamento_prop . "<br>";
//    echo "Porta do proprietario: " . $num_porta_prop . "<br>";
//    echo "Complemento do proprietario: " . $complemento_prop . "<br>";
//    echo "Código Postal do proprietario: " . $cod_postal_prop . "<br>";
//    echo "Id Freguesia do proprietario: " . $id_freg_prop . "<br>";
//    echo "Histórico antes da alteração<br>" . $historico_antes . "<br>";
    //INICIA OS UPDATES
    if (
            $user_id == "" ||
            $angariacao_id == "" ||
            $num_contrato == "" ||
            $id_atual_imovel == "" ||
            $id_inicial_imovel == "" ||
            $id_consultor == "" ||
            $espelho_angariacao == "" ||
            $duracao_contrato == "" ||
            $data_insercao_ilist == "" ||
            $data_cmi == "" ||
            $regime == "" ||
            $tipo_comissao == "" ||
            $valor_comi == "" ||
            $tipo_angariacao == "" ||
            $tipo_negocio == "" ||
            $estado_imovel == "" ||
            $arruamento_imv == "" ||
            $num_porta_imv == "" ||
            $cod_postal_imv == "" ||
            $id_freg_imv == "" ||
            $id_tipo_imovel == "" ||
            $valor_atual_imo == "" ||
            $valor_inicial_imo == "" ||
            $conservatoria == "" ||
            $ficha_crp == "" ||
            $matriz_cpu == "" ||
            $nome_prop == "" ||
            $nif_prop == "" ||
            $email_prop == "" ||
            $telefone_prop == "" ||
            $arruamento_prop == "" ||
            $num_porta_prop == "" ||
            $cod_postal_prop == "" ||
            $id_freg_prop == ""
    ) {
        $_SESSION['msg_form'] = "<div class='alert alert-danger'><strong>ATENÇÃO!</strong> Campos obrigatórios em falta, informe o Marketing </div>";
        header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel/$id_imovel_procurado");
    } else {
        $conn->autocommit(FALSE);

        $upd_ang = "UPDATE angariacoes SET data_insercao_ilist='$data_insercao_ilist', freguesia_imovel='$id_freg_imv', 
        arruamento_imovel='$arruamento_imv', num_porta_imovel='$num_porta_imv', complemento_imovel='$complemento_imv', 
        cod_postal_imovel='$cod_postal_imv', tipo_angariacao='$tipo_angariacao', tipo_negocio='$tipo_negocio', 
        tipo_imovel='$id_tipo_imovel', valor_inicial_imovel='$valor_inicial_imovel', espelho='$espelho_angariacao' WHERE id='$angariacao_id'";

        if ($conn->query($upd_ang) === TRUE) {
            $upd_lvr = "UPDATE livro_registo SET estado_imovel='$estado_imovel', num_contrato='$num_contrato', 
            data_cmi='$data_cmi', regime='$regime', id_inicial_imovel='$id_inicial_imovel', angariacao_id='$angariacao_id', 
            nome_proprietario='$nome_prop', nif_proprietario='$nif_prop', email_proprietario='$email_prop', 
            telefone_proprietario='$telefone_prop', arruamento_proprietario='$arruamento_prop', num_porta_proprietario='$num_porta_prop', 
            complemento_proprietario='$complemento_prop', cod_postal_proprietario='$cod_postal_prop', freguesia_proprietario='$id_freg_prop', 
            conservatoria='$conservatoria', ficha_crp='$ficha_crp', matriz_cpu='$matriz_cpu', fracao='$fracao', tipo_comissao='$tipo_comissao', 
            valor_comissao='$valor_comissao', data_negocio=$data_negocio, data_recisao=$data_rescisao, valor_negocio='$valor_negocio', 
            comissao_recebida='$comissao_recebida' WHERE angariacao_id='$angariacao_id'";

            if ($conn->query($upd_lvr) === TRUE) {
                $upd_id_ang = "UPDATE id_angariacao SET data=NOW(), angariacao_id='$angariacao_id', id_imovel='$id_atual_imovel', 
                consultor='$id_consultor', gestor='$id_gestor', loja='$id_loja_consultor' WHERE angariacao_id='$angariacao_id'";

                if ($conn->query($upd_id_ang) === TRUE) {
                    $upd_baixa_preco = "UPDATE baixa_preco SET data=NOW(), angariacao_id='$angariacao_id', 
                    ultimo_valor_imovel='$valor_atual_imovel', novo_valor_imovel='$valor_atual_imovel', 
                    tipo_angariacao='$tipo_angariacao', tipo_negocio='$tipo_negocio', produto_espelho=NULL, 
                    by_user='$user_id' WHERE angariacao_id='$angariacao_id' AND produto_espelho IS NULL";

                    if ($conn->query($upd_baixa_preco) === TRUE) {
                        $ins_hist_info_ang = "INSERT INTO hist_info_angariacao 
                        (info_antes, info_depois, data, by_user, loja) VALUES 
                        ('$historico_antes', '$historico_depois', NOW(), '$user_id', '$user_id_loja')";

                        if ($conn->query($ins_hist_info_ang) === TRUE) {
                            $conn->commit();
                            if (isset($_SESSION['pesq_ang_consultor'])) {
                                unset($_SESSION['pesq_ang_consultor']);
                                $_SESSION['final_consultor'] = "session_final_consultor";
                                $_SESSION['msg_busca_consultor'] = "<div class='alert alert-success'><strong>Parabéns!</strong> Você atualizou todas as informações corretamente. </div>";
                                header("Location: ../../coordenacao/pesquisar-angariacao/consultor");
                            } else {
                                $_SESSION['msg_form'] = "<div class='alert alert-success'><strong>Parabéns!</strong> Você atualizou todas as informações corretamente. </div>";
                                header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel/$id_imovel_procurado");
                                //unset($_SESSION['pesq_ang_id_imv']);
                            }
                        } else {
                            $conn->rollback();
                            $_SESSION['msg_form'] = "<div class='alert alert-danger'><strong>Impossível Atualizar!</strong> Falha na tabela Hist_Info_Angariacao </div>";
                            header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel/$id_imovel_procurado");
                        }
                    } else {
                        $conn->rollback();
                        $_SESSION['msg_form'] = "<div class='alert alert-danger'><strong>Impossível Atualizar!</strong> Falha na tabela Baixa de Preço </div>";
                        header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel/$id_imovel_procurado");
                    }
                } else {
                    $conn->rollback();
                    $_SESSION['msg_form'] = "<div class='alert alert-danger'><strong>Impossível Atualizar!</strong> Falha na tabela ID_Angariacao </div>";
                    header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel/$id_imovel_procurado");
                }
            } else {
                $conn->rollback();
                $_SESSION['msg_form'] = "<div class='alert alert-danger'><strong>Impossível Atualizar!</strong> Falha na tabela Livro de Registo </div>";
                header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel/$id_imovel_procurado");
            }
        } else {
            $conn->rollback();
            $_SESSION['msg_form'] = "<div class='alert alert-danger'><strong>Impossível Atualizar!</strong> Falha na tabela Angariações </div>";
            header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel/$id_imovel_procurado");
        }
    }
}