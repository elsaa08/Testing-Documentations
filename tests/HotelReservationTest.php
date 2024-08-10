<?php

use PHPUnit\Framework\TestCase;

class HotelReservationTest extends TestCase
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

    public function testAddReservationSuccess()
    {
        $_POST = [
            'submit' => true,
            'name' => 'Nabiilah Elsa',
            'email' => 'nabiilah@upi.edu',
            'phone' => '08976xxxx',
            'day' => '4',
            'month' => 'july',
            'year' => '2025',
            'no_adults' => '2',
            'no_rooms' => '2',
            'message' => '1000'
        ];
        ob_start();
        include 'app/index.php';
        ob_end_clean();

        $result = $this->con->query("SELECT * FROM requests ORDER BY id DESC LIMIT 1"); //cek apakah masuk db
        $room = $result->fetch_assoc();

        $this->assertEquals('Nabiilah Elsa', $room['name']);
    }

    public function testReservationFailureMissingFields()
    {
        $_POST = [
            'submit' => true,
            'name' => '',
            'email' => '',
            'phone' => '',
            'day' => 'no',
            'month' => 'no',
            'year' => 'no',
            'no_adults' => 'no',
            'no_rooms' => 'no',
            'message' => ''
        ];

        ob_start();
        include 'app/index.php';
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertStringContainsString('All Feilds Required, Try Again', $output);
    }
    public function testReservationDatabaseInsertion()
    {
        $_POST = [
            'submit' => true,
            'name' => 'Kim Jennie',
            'email' => 'jennie@gmail.com',
            'phone' => '0987654321',
            'day' => '15',
            'month' => 'june',
            'year' => '2024',
            'no_adults' => '2',
            'no_rooms' => '1',
            'message' => 'Excited about the trip!'
        ];
        ob_start();
        include 'app/index.php';
        ob_end_clean();
        $query = "SELECT * FROM requests WHERE name='Kim Jennie' AND email='jennie@gmail.com'";
        $result = mysqli_query($this->con, $query);
        $this->assertTrue(mysqli_num_rows($result) > 0);
        $cleanupQuery = "DELETE FROM requests WHERE name='Kim Jennie' AND email='jennie@example.com'";
        mysqli_query($this->con, $cleanupQuery);
    }
}
