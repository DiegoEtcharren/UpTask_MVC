<?php
namespace Model;
use Model\ActiveRecord;
 
class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = [
        'id',
        'nombre',
        'email',
        'password',
        'token',
        'confirmado'
    ];
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmado;

    public function __construct($args = []) 
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // Validacion Cuentas nuevas: 

    public function validarNuevaCuenta(){
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre del usuario es necesario';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es necesario';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El password es necesario';
        } else if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe ser mayor de 6 o mas  caracteres';
        } else if ($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los password deben coincidir';
        }

        return self::$alertas;
    }

    // Hashea el password: 
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    // Generar un Token:
    public function crearToken() {
        $this->token = uniqid();
    }
}

