<?php 

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->passwordActual = $args['passwordActual'] ?? '';
        $this->passwordNuevo = $args['passwordNuevo'] ?? '';

        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;

    }

    //Validacion cuentas nuevas
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del usuario es obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El email del usuario es obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El password no puede ir vacio';
        }elseif(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los password son distintos';
        }
        return self::$alertas;
    }

    //Hasea el Password 
    public function hassPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    //Generar un Token
    public function crearToken() {
        $this->token = uniqid();
    }

    //Validar email 
    public function valdiarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email no valido';
        }
        return self::$alertas;
    }

    //valida el password
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El password no puede ir vacio';
        }elseif(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function validarPerfil() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del usuario es obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El email del usuario es obligatorio';
        }
        return self::$alertas;

    }

    //Validar el login del usuario
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email del usuario es obligatorio';
        }elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email no valido';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El password no puede ir vacio';
        }

        return self::$alertas;
    }

    public function nuevoPassword() {
        if(!$this->passwordActual) {
            self::$alertas['error'][] = 'El password Actual no puede ir vacio';
        }
        if(!$this->passwordNuevo) {
            self::$alertas['error'][] = 'El password Nuevo no puede ir vacio';
        }elseif(strlen($this->passwordNuevo) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    //Comprobar Password
    public function comprobarPassword() : bool {
        return password_verify($this->passwordActual, $this->password);
    }
}