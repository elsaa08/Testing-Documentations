<?php

use PHPUnit\Framework\TestCase;

require_once 'app/edit-room.php'; // Pastikan path sesuai dengan struktur proyek Anda

class HotelEditRoomTest extends TestCase
{
    protected $con;

    protected function setUp(): void
    {
        // Inisialisasi koneksi database
        $this->con = new mysqli("localhost", "root", "", "hotel");
        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }
    }

    protected function tearDown(): void
    {
        // Tutup koneksi database
        $this->con->close();
    }



    public function testEditRoomSuccess()
    {
        $id = 1;
        $title = 'New Room Title';
        $description = 'New Room Description';
        $size = '1000 sq';
        $price = '300';
        $image1 = 'image1.jpg';
        $image2 = 'image2.jpg';
        $image3 = 'image3.jpg';
        $image4 = 'image4.jpg';

        // Simulasikan pada proses edit room
        $error = editRoom($this->con, $id, $title, $description, $size, $price, $image1, $image2, $image3, $image4);
        // Periksa apakah error yang dikembalikan adalah null, artinya tidak ada kesalahan
        $this->assertNull($error);
    }
    
    public function testEditRoomInvalidId()
    {
        $id = 999; // ID yang tidak ada dalam database
        $title = 'New Room';
        $description = 'New Room Description';
        $size = '1000 sq.';
        $price = '300';
        $image1 = 'image1.jpg';
        $image2 = 'image2.jpg';
        $image3 = 'image3.jpg';
        $image4 = 'image4.jpg';

        $error = editRoom($this->con, $id, $title, $description, $size, $price, $image1, $image2, $image3, $image4);

        $this->assertEquals("", $error);
    }

    public function testEditRoomInvalidData()
    {
        $id = 1;
        $title = ''; // Judul kosong
        $description = 'New Room Description';
        $size = '1000 sq';
        $price = '300';
        $image1 = 'image1.jpg';
        $image2 = 'image2.jpg';
        $image3 = 'image3.jpg';
        $image4 = 'image4.jpg';

        $error = editRoom($this->con, $id, $title, $description, $size, $price, $image1, $image2, $image3, $image4);
        $this->assertEquals("", $error);
    }
}
