<?php
require_once('app/db.php');
require_once('app/login.php');

class HotelLoginTest extends PHPUnit\Framework\TestCase
{
    protected $con;

    protected function setUp(): void
    {
        // Menggunakan SQLite dalam memori untuk tes
        $this->con = new mysqli("localhost", "root", "", "hotel");
        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }
    }

    protected function tearDown(): void
    {
        // Bersihkan koneksi database
        $this->con->close();
    }
    public function testLoginSuccess()
    {
        $_POST = [
            'submit' => true,
            'username' => 'elsa',
            'password' => 'elsa'
        ];
        session_start();
        $error = login($this->con, $_POST['username'], $_POST['password']);

        $this->assertEquals('elsa', $_SESSION['username']);
        $this->assertEquals('admin', $_SESSION['role']);
        $this->assertEmpty($error);

        session_destroy();
    }
    public function testLoginFailureWrongPassword()
    {
        $_POST = [
            'submit' => true,
            'username' => 'elsa',
            'password' => 'wrongpassword'
        ];

        session_start();
        $error = login($this->con, $_POST['username'], $_POST['password']);

        $this->assertEquals('Wrong Username or Password', $error);
        $this->assertArrayNotHasKey('username', $_SESSION);
        $this->assertArrayNotHasKey('role', $_SESSION);

        session_destroy();
    }
    public function testLoginFailureNonExistentUser()
    {
        $_POST = [
            'submit' => true,
            'username' => 'nonexistent',
            'password' => 'password'
        ];

        session_start();
        $error = login($this->con, $_POST['username'], $_POST['password']);

        $this->assertEquals('Wrong Username or Password', $error);
        $this->assertArrayNotHasKey('username', $_SESSION);
        $this->assertArrayNotHasKey('role', $_SESSION);

        session_destroy();
    }
}
