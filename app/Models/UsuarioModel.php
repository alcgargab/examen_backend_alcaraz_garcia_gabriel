<?php 
  namespace App\Models;
  use CodeIgniter\Model;
  class UsuarioModel extends Model
  {
  	protected $table = "usuarios";
  	protected $primaryKey = "ID";
  	protected $allowedFields = ["Name", "Email", "Password"];
    //---------- FUNCIÓN PARA INSERTAR DATOS ----------//
    public function __insert($tabla = NULL, $data = NULL)
    {
      $db = \Config\Database::connect();
      $query = $db -> table($tabla) -> insert($data);
      if ($query) {
        return TRUE;
      } else {
        return FALSE;
      }
      $query -> close;
      $query = NULL;
    }
    //---------- FUNCIÓN PARA ACTUALIZAR | EL REGISTRO | COMPARANDO SOLO UN CAMPO----------//
    public function __updateAll($tabla = NULL, $campo1 = NULL, $operation1 = NULL, $valor1 = NULL, $data = NULL)
    {
      $db = \Config\Database::connect();
      $where = $campo1." ".$operation1." ".$valor1;
      $query = $db -> table($tabla) -> where($where) -> update($data);
      if ($query) {
        return "true";
      } else {
        return "false";
      }
      $query = null;
      $query -> close();
    }
    //---------- FUNCIÓN PARA OBTENER | TODOS LOS REGISTROS | DE VARIAS TABLAS | COMPARANDO UNO O ALGUNOS CAMPOS | ORDENADOS O NO ----------//
    public function __getAllInner($select = NULL, $tabla1 = NULL, $tabla2 = NULL, $campo1Tabla = NULL, $campo2Tabla = NULL, $campo1 = NULL, $operation1 = NULL, $valor1 = NULL, $campoOrderBy = NULL, $order = NULL)
    {
      try {
        $db = \Config\Database::connect();
        $query = $db -> table($tabla1);
        $query -> select($select);
        $where = $campo1." ".$operation1." '".$valor1."'";
        // CON ORDER BY
        if (!empty($campoOrderBy)) {
          $query = $db -> table($tabla1) -> select($select) -> join($tabla2, $campo1Tabla.' = '.$campo2Tabla, 'inner') -> where($where) -> orderBy($campoOrderBy, $order) -> get();
          // SIN ORDER BY
        } else {
          $query = $db -> table($tabla1) -> select($select) -> join($tabla2, $campo1Tabla.' = '.$campo2Tabla, 'inner') -> where($where) -> get();
        }
        $resultquery = $query -> getResult();
        if ($resultquery > 0) {
          return $query -> getResult();
        } else {
          return FALSE;
        }
        $query = $db -> close;
        $query = $db -> NULL;
      } catch (Exception $e) {
        echo "Error: " . $e -> getMessage();
      }
    }
    //---------- FUNCIÓN PARA OBTENER | SOLO UN REGISTRO | DE UNA TABLA | COMPARANDO UNO O ALGUNOS CAMPOS ----------//
    public function __getRow($select = NULL, $tabla = NULL, $numeroCampos = NULL, $campo1 = NULL, $operation1 = NULL, $valor1 = NULL)
    {
      $db = \Config\Database::connect();
      $where = $campo1." ".$operation1." '".$valor1."'";
      $query = $db -> table($tabla) -> select($select) -> where($where) -> get();
      $resultquery = $query -> getResult();
      if ($resultquery > 0) {
        return $query -> getRow();
      } else{
        return FALSE;
      }
      $query -> close;
      $query = NULL;
    }
     //---------- FUNCIÓN PARA OBTENER | SOLO UN REGISTRO | DE VARIAS TABLAS | COMPARANDO UNO O ALGUNOS CAMPOS ----------//
    public function __getRowInner($select = NULL, $tabla1 = NULL, $tabla2 = NULL, $campo1Tabla = NULL, $campo2Tabla = NULL, $campo1 = NULL, $operation1 = NULL, $valor1 = NULL)
    {
      try {
        $db = \Config\Database::connect();
        $query = $db -> table($tabla1);
        $query -> select($select);
        $where = $campo1." ".$operation1." '".$valor1."'";
        // CON ORDER BY
        if (!empty($campoOrderBy)) {
          $query = $db -> table($tabla1) -> select($select) -> join($tabla2, $campo1Tabla.' = '.$campo2Tabla, 'inner') -> where($where) -> orderBy($campoOrderBy, $order) -> get();
          // SIN ORDER BY
        } else {
          $query = $db -> table($tabla1) -> select($select) -> join($tabla2, $campo1Tabla.' = '.$campo2Tabla, 'inner') -> where($where) -> get();
        }
        $resultquery = $query -> getResult();
        if ($resultquery > 0) {
          return $query -> getRow();
        } else {
          return FALSE;
        }
        $query = $db -> close;
        $query = $db -> NULL;
      } catch (Exception $e) {
        echo "Error: " . $e -> getMessage();
      }
    }
  }