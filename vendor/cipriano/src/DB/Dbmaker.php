<?php

namespace Cipriano\DB;

use \Cipriano\Config;
use PDO;
use PDOException;

class Dbmaker {

  private static $instancia;
  private $config;
  private $conexao;
  public $query;
  public $last_query;
  var $result;

  public static function instanciar() {
    if (!self::$instancia) {
      self::$instancia = new Dbmaker();
      self::$instancia->conectar();
    }

    return self::$instancia;
  }

  function __construct() {
    // Esta classe nao deve ser instanciada externamente
    $this->config = Config::load('../db.env');
    unset($this->error);
  }

  private function conectar() {
    try {
      $this->conexao = new PDO("odbc:{$this->config->DBMAKER_ODBC}", $this->config->DBMAKER_USERNAME, $this->config->DBMAKER_PASSWORD); // esse funciona
      $this->conexao->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
      return $this->conexao;
    } catch (PDOException $e) {
      echo "<hr>ODBC *BANCO_DBMAKER*: ".($e->getMessage())."<hr>";
    }
  }

  function desconectar() {
    odbc_close($this->conexao);
    //$this->conexao = null;
  }

  function buscar($tabela, $id) {
    try {
      $rs = $this->conexao->query("SELECT * FROM $tabela WHERE id='$id'");
      return $rs->fetch();
    } catch (PDOException $e) {
      echo $e->getCode();
    }
  }

  function inserir($tabela, $dados) {
    foreach ($dados as $campo => $valor) {
      $campos[] = $campo;
      $valores[] = $valor;
      $holders[] = '?';
    }
    $campos = implode(', ', $campos);
    $holders = implode(', ', $holders);

    $st = $this->conexao->prepare("INSERT INTO $tabela ($campos) VALUES ($holders)");
    return $st->execute($valores);
  }

  function alterar($tabela, $id, $dados, $retorna_erro = false) {
    try {
      foreach ($dados as $campo => $valor) {
        $set[] = "$campo=?";
        $valores[] = $valor;
      }
      $sets = implode(', ', $set);
      $st = $this->conexao->prepare("UPDATE $tabela SET $sets WHERE id='$id'");
      return $st->execute($valores);
    } catch (PDOException $e) {
      echo $e->getCode();
    }
  }

  function excluir($tabela, $id) {
    $st = $this->conexao->prepare("DELETE FROM $tabela WHERE id=?");
    return $st->execute(array($id));
  }

  function num_rows() {
    //return $this->num_rows;
    return $this->result->rowCount();
  }

  function error() {
    return $this->error;
  }

  function error_msg() {
    return $this->error_msg;
  }

  function get_erro() {
    $this->error = odbc_error();
    $this->error_msg = odbc_errormsg();
  }

  function exec($sql,$semretorno=false) {
    try {
      $this->result = $this->conexao->query($sql);
      $i = 0;
      if (!$semretorno) {
        if ($this->num_rows()) {
          return $this->result->fetchAll(PDO::FETCH_OBJ);
          // return $this->result;
        }
      }
    } catch (PDOException $e) {
      echo $e->getCode();
    }
  }

}

?>
