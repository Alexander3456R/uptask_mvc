<?php
namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    // Declarar visibilidad
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? NULL;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
        
    }

    // Validar el login de los usuarios
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'] [] = 'El E-mail es Obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'E-mail no válido';
        }

        if(!$this->password) {
            self::$alertas['error'] [] = 'La Contraseña es Obligatoria';
        }
        return self::$alertas;
    }
    // Validacion para cuentas nuevas
    public function validarCuentaNueva() {
        if(!$this->nombre) {
            self::$alertas['error'] [] = 'El Nombre es Obligatorio';
        }

        if(!$this->email) {
            self::$alertas['error'] [] = 'El E-mail es Obligatorio';
        }

        if(!$this->password) {
            self::$alertas['error'] [] = 'La Contraseña es Obligatoria';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'] [] = 'La contraseña debe contener al menos 6 caracteres';
        }

        if($this->password !== $this->password2) {
        self::$alertas['error'] [] = 'La contraseña no es igual, escribela nuevamente';

        }
        return self::$alertas;
    }

    // Valida un email
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El e-mail es obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'E-mail no valido';

        }
        return self::$alertas;
    }

    // Validar password
    public function validarPassword() {
         if(!$this->password) {
            self::$alertas['error'] [] = 'La Contraseña es Obligatoria';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'] [] = 'La contraseña debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function validar_perfil() {
        if(!$this->nombre) {
            self::$alertas['error'] [] = 'El Nombre es Obligatorio';
        }

        if(!$this->email) {
            self::$alertas['error'] [] = 'El E-mail es Obligatorio';
        }
        return self::$alertas;
    }

    public function nuevoPassword() : array {
        if(!$this->password_actual) {
            self::$alertas['error'] [] = 'La contraseña actual no puede ir vacia';
        }

         if(!$this->password_nuevo) {
            self::$alertas['error'] [] = 'La contraseña nueva no puede ir vacia';
        }

        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'] [] = 'La contraseña nueva debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function comprobarPassword() : bool {
        return password_verify($this->password_actual, $this->password);
    }


    // Hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un token

    public function crearToken() : void {
        $this->token = uniqid();
    }
}