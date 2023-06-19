<?php
	include "functions.php";

	isset($_POST["atividade"]) ? $atividade = $_POST["atividade"] : $atividade = 0;
	isset($_POST["status"]) ? $status = $_POST["status"] : $status = "N";

	$conexao = new mysql_conexao();
	$conexao->conectar();

	$conexao->executar("UPDATE ET_ATIVIDADES_DIAS SET SIT_ATIVIDADE_DIA = '" . $status . "' WHERE COD_ATIVIDADE_DIA = " . $atividade . ";");

	$conexao->fechar();

	header("Location: .?m=2");
?>