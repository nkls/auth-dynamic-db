<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Message;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tests\TestCase;

class MessageTest extends TestCase
{

    public function test_get_message_by_status()
    {
        $this->assertEquals(Message::HTTP_STATUS[200], Message::getMessageByStatus(200));
        $this->assertEquals(Message::HTTP_STATUS[400], Message::getMessageByStatus(400));
        $this->assertEquals(Message::UNKNOWN, Message::getMessageByStatus(100));
    }

    public function test_get_exception()
    {
        $this->expectException(HttpResponseException::class);
        Message::get();
    }

    public function test_get_message()
    {
        try {
            Message::get(200);
        } catch (HttpResponseException $e) {
            $this->assertEquals(200, $e->getResponse()->getStatusCode());
        }

        try {
            Message::get(400, 'Test message');
        } catch (HttpResponseException $e) {
            $this->assertEquals(400, $e->getResponse()->getStatusCode());
            $this->assertEquals(json_encode(['message' => 'Test message']), $e->getResponse()->getContent());
        }

        try {
            Message::get(400);
        } catch (HttpResponseException $e) {
            $this->assertEquals(400, $e->getResponse()->getStatusCode());
            $this->assertEquals(json_encode(['message' => Message::HTTP_STATUS[400]]), $e->getResponse()->getContent());
        }

        $errors = ['123', 'test', 'error'];
        try {
            Message::get(403, 'Message', $errors);
        } catch (HttpResponseException $e) {
            $this->assertEquals(403, $e->getResponse()->getStatusCode());
            $this->assertEquals(
                json_encode(['message' => 'Message', 'errors' => $errors]),
                $e->getResponse()->getContent()
            );
        }
    }
}
