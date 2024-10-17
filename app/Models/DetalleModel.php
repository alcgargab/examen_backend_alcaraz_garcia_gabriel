<?php 
  namespace App\Models;
  use CodeIgniter\Model;
  class DetalleModel extends Model
  {
  	protected $table = "detalles_usuario";
  	protected $primaryKey = "du_id";
  	protected $allowedFields = ["u_id", "direccion", "telefono", "fNacimiento"];
  }