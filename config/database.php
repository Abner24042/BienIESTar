<?php
class Database {
    private static $conn;

    public static function getConnection() {
        if (!self::$conn) {
            self::$conn = new mysqli("localhost", "root", "", "sistema_usuarios");
            if (self::$conn->connect_error) {
                die("Error de conexión: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }
}
?>
