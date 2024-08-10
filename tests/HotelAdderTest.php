<?php

use PHPUnit\Framework\TestCase;

class HotelAdderTest extends TestCase
{
    protected $con;

    protected function setUp(): void
    {
        // Establish a mock database connection or use an in-memory SQLite database
        $this->con = new mysqli("localhost:3306", "root", "", "hotel");
        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }
    }

    protected function tearDown(): void
    {
        // Clean up the mock database connection
        $this->con->close();
    }

    public function testAddRoomSuccess()
    {
        // Mock form data
        $_POST = [
            'submit' => true,
            'title' => 'Test Room',
            'post-data' => 'This is a test room',
            'size' => '100',
            'price' => '1000'
        ];

        // Mock file data
        $_FILES = [
            'image1' => [
                'name' => 'image1.jpg',
                'tmp_name' => 'path/to/tmp/image1.jpg'
            ],
            'image2' => [
                'name' => 'image2.jpg',
                'tmp_name' => 'path/to/tmp/image2.jpg'
            ],
            'image3' => [
                'name' => 'image3.jpg',
                'tmp_name' => 'path/to/tmp/image3.jpg'
            ],
            'image4' => [
                'name' => 'image4.jpg',
                'tmp_name' => 'path/to/tmp/image4.jpg'
            ]
        ];

        ob_start();
        include 'app/add-room.php';
        ob_end_clean();

        // Check if the room is added to the database
        $result = $this->con->query("SELECT * FROM rooms ORDER BY id DESC LIMIT 1");
        $room = $result->fetch_assoc();

        // Assertions
        $this->assertEquals('Test Room', $room['title']);
    }
    public function testAddRoomFailureMissingFields()
    {
        $_POST = [];
        $_FILES = [];


        ob_start();
        include 'app/add-room.php';
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertStringContainsString('', $output);
    }
}
