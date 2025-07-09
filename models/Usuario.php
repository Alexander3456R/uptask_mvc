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
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
        
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

    // Hashea el password
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un token

    public function crearToken() {
        $this->token = uniqid();
    }
}