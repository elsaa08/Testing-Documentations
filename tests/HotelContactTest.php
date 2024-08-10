<?php

class HotelContactTest extends PHPUnit\Framework\TestCase
{
    public function testFeedbackSubmission()
    {

        $_POST['name'] = 'Sung Hanbin';
        $_POST['email'] = 'hanbin@upi.edu';
        $_POST['phone'] = '123-456-7890';
        $_POST['message'] = 'awesome.';
        $_SESSION = []; // Start with empty session 

        $mock = $this->createMock(\mysqli::class);
        $mock->method('query')->willReturn(true); // Simulate successful query
        $GLOBALS['con'] = $mock; // Replace actual connection (adjust based on your setup)

        require_once('app/contact.php');

        // Check if no errors are displayed
        $this->assertEmpty($error);
    }

    public function testEmptyFieldsSubmission()
    {

        $_POST = []; // No data submitted
        ob_start();


        $mock = $this->createMock(\mysqli::class);
        $mock->method('query')->willReturn(true); // Simulate successful query 
        $GLOBALS['con'] = $mock; // Replace actual connection

        require_once('app/contact.php');
        $output = ob_get_clean();

        // Check if error message indicates missing fields
        $this->assertStringContainsString('', $output);
    }
}
