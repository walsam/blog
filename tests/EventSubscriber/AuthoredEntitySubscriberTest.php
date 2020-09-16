<?php


namespace App\Tests\EventSubscriber;


use App\EventSubscriber\AuthoredEntitySubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthoredEntitySubscriberTest extends TestCase
{
    public function testConfiguration(){
        $result = AuthoredEntitySubscriber::getSubscribedEvents();

        $this->assertArrayHasKey(KernelEvents::VIEW, $result);
    }

}