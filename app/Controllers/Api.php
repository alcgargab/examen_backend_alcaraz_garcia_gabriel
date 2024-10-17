<?php
    namespace App\Controllers;
    use App\Models\UsuarioModel;
    use App\Models\DetalleModel;
    use CodeIgniter\RESTful\ResourceController;
    class Api extends ResourceController
    {
        protected $modelName = 'App\Models\UsuarioModel';
        protected $format = 'json';
        //---------- TABLAS ---------- //
        static $tbl_u = "usuarios";
        static $tbl_du = "detalles_usuario";
        //---------- CAMPOS ----------//
        static $th_u_i = "ID";
        static $th_u_p = "Password";
        static $th_u_e = "Email";
        static $th_du_u = "u_id";
        //---------- VALORES ----------//
        static $var_1 = 1;
        static $var_16 = 16;
        static $var_100 = 100;
        static $true = TRUE;
        static $equal = "=";
        static $different = "!=";
        static $empty = "";
        static $DESC = "DESC";
        //---------- FUNCIÓN PARA CREAR UNA RESPUESTA ----------//
        private function generateResponse($data = NULL, $msj = NULL, $code = NULL)
        {
            if ($code == 200) {
                return $this -> respond(array(
                    "code" => $code,
                    "data" => $data
                ));
            } else {
                return $this -> respond(array(
                    "code" => $code,
                    "msj" => $msj
                ));
            }
        }
        //---------- FUNCIÓN PARA GENERAR UN CODIGO ALEATORIO ----------//
        public function __generatorCode($items = NULL, $letters = NULL, $numbers = NULL, $capitalLetters = NULL, $specialCharacters = NULL)
        { 
            $opcLetras = $letters;
            $opcNumeros = $numbers;
            $opcLetrasMayus = $capitalLetters;
            $opcEspeciales = $specialCharacters;
            $longitud = $items;
            $code = "";
            $letras ="abcdefghijklmnopqrstuvwxyz";
            $numeros = "1234567890";
            $letrasMayus = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $especiales ="|@#~$%()=^*+[]{}-_";
            $listado = "";
            if ($opcLetras == TRUE) {
                $listado .= $letras;
            }
            if ($opcNumeros == TRUE) {
                $listado .= $numeros;
            }
            if($opcLetrasMayus == TRUE) {
                $listado .= $letrasMayus;
            }
            if($opcEspeciales == TRUE) {
                $listado .= $especiales;
            }
            str_shuffle($listado);
            $max = strlen($listado)-1;
            for ($i=1; $i<=$longitud; $i++) {
                $code[$i] = $listado[mt_rand(0,$max)];
                str_shuffle($listado);
            }
            return $code;
        }
        //---------- FUNCIÓN PRINCIPAL ----------//
        public function index()
        {
            $migrate = \Config\Services::migrations();
            try {
                $migrate -> latest();
            } catch (Exception $e) {
                return $this -> generateResponse($e, "", 500);            }
            $usuarioM = new UsuarioModel();
            $detalleM = new DetalleModel();
            $select = "ID, Name, Email, direccion, telefono, fNacimiento";
            $queryResult = $usuarioM -> __getAllInner($select, self::$tbl_u, self::$tbl_du, self::$th_u_i, self::$th_du_u, self::$th_u_i, self::$different, self::$empty, self::$th_u_i, self::$DESC);
            if (!empty($queryResult)) {
                return $this -> generateResponse($queryResult, "", 200);
            } else {
                for ($i=self::$var_1; $i <= self::$var_100; $i++) { 
                    $queryUsuarios['Name'] = "user".$i;
                    $queryUsuarios['Email'] = "correo".$queryUsuarios['Name']."@correo.com";
                    do {
                        $queryPassword = $this -> __generatorCode(self::$var_16, self::$true, self::$true, self::$true, self::$true);
                        $select = "ID";
                        $queryUsuarioPassword = $usuarioM -> __getRow($select, self::$tbl_u, self::$var_1, self::$th_u_p, self::$equal, $queryPassword);
                    } while (!empty($queryUsuarioPassword)); 
                    $queryUsuarios['Password'] = hash('whirlpool', $queryPassword);
                    $insertUser = $usuarioM -> __insert(self::$tbl_u, $queryUsuarios);
                    if ($insertUser) {
                        $select = "ID";
                        $getUser = $usuarioM -> __getRow($select, self::$tbl_u, self::$var_1, self::$th_u_p, self::$equal, $queryUsuarios['Password']);
                        $queryDetalleUsuarios['du_id'] = $i;
                        $queryDetalleUsuarios['u_id'] = $getUser -> ID;
                        $queryDetalleUsuarios['direccion'] = "ubicacion".$i;
                        $queryDetalleUsuarios['telefono'] = "0123456789";
                        $queryDetalleUsuarios['fNacimiento'] = "1994-08-16";
                        $insertDetalleUser = $usuarioM -> __insert(self::$tbl_du, $queryDetalleUsuarios);
                    }
                }
                $select = "ID, Name, Email, direccion, telefono, fNacimiento";
                $queryResult = $usuarioM -> __getAllInner($select, self::$tbl_u, self::$tbl_du, self::$th_u_i, self::$th_du_u, self::$th_u_i, self::$different, self::$empty, self::$th_u_i, self::$DESC);
                return $this -> generateResponse($queryResult, "", 200);
            }
        }
        //---------- FUNCIÓN PARA CREAR UN USUARIO ----------//
        public function create()
        {
            $usuarioM = new UsuarioModel();
            $detalleM = new DetalleModel();
            $queryFormUser['Name'] = trim(mb_strtoupper($this -> request -> getPost('name'), "UTF-8"));
            $queryFormUser['Email'] = trim(mb_strtoupper($this -> request -> getPost('email'), "UTF-8"));
            $queryFormUserPassword = trim(mb_strtoupper($this -> request -> getPost('pass'), "UTF-8"));
            $queryFormUser['Password'] = hash('whirlpool', $queryFormUserPassword);;
            $queryFormDetalleUser['direccion'] = trim(mb_strtoupper($this -> request -> getPost('location'), "UTF-8"));
            $queryFormDetalleUser['telefono'] = trim($this -> request -> getPost('phone'));
            $queryFormDetalleUser['fNacimiento'] = trim($this -> request -> getPost('birthday'));
            if (!empty($queryFormUser['Name']) && !empty($queryFormUser['Email']) && !empty($queryFormUser['Password']) && !empty($queryFormDetalleUser['direccion']) && !empty($queryFormDetalleUser['telefono']) && !empty($queryFormDetalleUser['fNacimiento'])) {
                $select = "ID";
                $getUser = $usuarioM -> __getRow($select, self::$tbl_u, self::$var_1, self::$th_u_e, self::$equal, $queryFormUser['Email']);
                if (empty($getUser)) {
                    $insertUser = $usuarioM -> __insert(self::$tbl_u, $queryFormUser);
                    if ($insertUser) {
                        $select = "ID";
                        $getUser = $usuarioM -> __getRow($select, self::$tbl_u, self::$var_1, self::$th_u_e, self::$equal, $queryFormUser['Email']);
                        $queryFormDetalleUser['u_id'] = $getUser -> ID;
                        $insertDetalleUser = $usuarioM -> __insert(self::$tbl_du, $queryFormDetalleUser);
                        if (!empty($insertDetalleUser)) {
                            return $this -> generateResponse(null, null, 200);
                        } else {
                            return $this -> generateResponse(null, "Hubo un error en el servidor", 500);
                        }
                    } else {
                        return $this -> generateResponse(null, "Hubo un error en el servidor", 500);
                    }
                } else {
                    return $this -> generateResponse(null, "No se puede agregar el usuario porque el correo ya existe", 500);
                }
            } else {
                if (empty($queryFormUser['Name'])) {
                    return $this -> generateResponse(null, "Falta el nombre del usuario", 500);
                }
                if (empty($queryFormUser['Email'])) {
                    return $this -> generateResponse(null, "Falta el correo electrónico del usuario", 500);
                }
                if (empty($queryFormUser['Password'])) {
                    return $this -> generateResponse(null, "Falta la contraseña del usuario", 500);
                }
                if (empty($queryFormDetalleUser['direccion'])) {
                    return $this -> generateResponse(null, "Falta la dirección del usuario", 500);
                }
                if (empty($queryFormDetalleUser['telefono'])) {
                    return $this -> generateResponse(null, "Falta el número de teléfono del usuario", 500);
                }
                if (empty($queryFormDetalleUser['fNacimiento'])) {
                    return $this -> generateResponse(null, "Falta la fecha de nacimiento del usuario", 500);
                }
            }
        }
        //---------- FUNCIÓN PARA VER LA INFORMACIÓN DE UN USUARIO ----------//
        public function read($id = NULL)
        {
            $usuarioM = new UsuarioModel();
            if (!empty($id)) {
                $select = "ID, Name, Email, direccion, telefono, fNacimiento";
                $getUser = $usuarioM -> __getRowInner($select, self::$tbl_u, self::$tbl_du, self::$th_u_i, self::$th_du_u, self::$th_u_i, self::$equal, $id);
                if (!empty($getUser)) {
                    return $this -> generateResponse($getUser, "", 200);
                } else {
                    return $this -> generateResponse(null, "El usuario no existe", 500);
                }
            } else {
                return $this -> generateResponse(null, "El id no fue encontrado", 500);
            }
        }
        //---------- FUNCIÓN PARA ACTUALIZAR LA INFORMACIÓN DE UN USUARIO ----------//
        public function update($id = NULL)
        {
            $usuarioM = new UsuarioModel();
            $detalleM = new DetalleModel();
             if (!empty($id)) {
                $select = "ID";
                $getUser = $usuarioM -> __getRowInner($select, self::$tbl_u, self::$tbl_du, self::$th_u_i, self::$th_du_u, self::$th_u_i, self::$equal, $id);
                if (!empty($getUser)) {
                    $data = $this -> request -> getRawInput();
                    if (!empty($data['name']) && !empty($data['email']) && !empty($data['password']) && !empty($data['location']) && !empty($data['phone']) && !empty($data['birthday'])) {
                        $usuarioM -> update($id, [
                            'Name' => trim(mb_strtoupper($data['name'], "UTF-8")),
                            'Email' => trim(mb_strtoupper($data['email'], "UTF-8")),
                            'Password' =>  hash('whirlpool', trim(mb_strtoupper($data['password'], "UTF-8"))),
                        ]);
                        $updateDetalleUser = $detalleM -> where('u_id', $id) -> set([
                                'u_id' => $id,
                                'direccion' => trim(mb_strtoupper($data['location'], "UTF-8")),
                                'telefono' => trim($data['phone']),
                                'fNacimiento' => trim($data['birthday']),
                            ]) -> update();
                        if (!empty($updateDetalleUser)) {
                            return $this -> generateResponse($this -> model -> find($updateDetalleUser), null, 200);
                        } else {
                            return $this -> generateResponse(null, "Hubo un error en el servidor", 500);
                        }
                    } else {
                        if (empty($data['name'])) {
                            return $this -> generateResponse(null, "Falta el nombre del usuario", 500);
                        }
                        if (empty($data['email'])) {
                            return $this -> generateResponse(null, "Falta el correo electrónico del usuario", 500);
                        }
                        if (empty($data['password'])) {
                            return $this -> generateResponse(null, "Falta la contraseña del usuario", 500);
                        }
                        if (empty($data['location'])) {
                            return $this -> generateResponse(null, "Falta la dirección del usuario", 500);
                        }
                        if (empty($data['phone'])) {
                            return $this -> generateResponse(null, "Falta el número de teléfono del usuario", 500);
                        }
                        if (empty($data['birthday'])) {
                            return $this -> generateResponse(null, "Falta la fecha de nacimiento del usuario", 500);
                        }
                    }
                } else {
                    return $this -> generateResponse(null, "El usuario no existe", 500);
                }
            } else {
                return $this -> generateResponse(null, "El id no fue encontrado", 500);
            }
        }
        //---------- FUNCIÓN PARA ELIMINAR UN USUARIO ----------//
        public function delete($id = NULL)
        {
            $usuarioM = new UsuarioModel();
            if (!empty($id)) {
                $select = "ID";
                $getUser = $usuarioM -> __getRowInner($select, self::$tbl_u, self::$tbl_du, self::$th_u_i, self::$th_du_u, self::$th_u_i, self::$equal, $id);
                if (!empty($getUser)) {
                    $this -> model -> delete($id);
                    return $this -> generateResponse("El usuario $id se ha eliminado correctamente", "", 200);
                } else {
                    return $this -> generateResponse(null, "El usuario no existe", 500);
                }
            } else {
                return $this -> generateResponse(null, "El id no fue encontrado", 500);
            }
        }
    }