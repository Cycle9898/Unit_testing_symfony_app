<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Security\GithubUserProvider;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
//use Psr\Http\Message\StreamInterface;

class GithubUserProviderTest extends TestCase
{
    private MockObject | Client | null $client;
    private MockObject | SerializerInterface | null $serializer;
    //private MockObject | StreamInterface | null $streamedResponse;
    private MockObject | ResponseInterface | null $response;

    public function setup(): void
    {
        $this->client = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->serializer = $this->getMockBuilder('JMS\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        // $this->streamedResponse = $this->getMockBuilder('Psr\Http\Message\StreamInterface')
        //     ->getMock();

        $this->response = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->getMock();
    }

    public function tearDown(): void
    {
        $this->client = null;
        $this->serializer = null;
        // $this->streamedResponse = null;    
        $this->response = null;
    }

    public function testLoadUserByUsernameThatReturnAUser()
    {
        // $this->streamedResponse->expects($this->once())->method('getContents')->willReturn('foo');

        //$this->response->expects($this->once())->method('getBody')->willReturn($this->streamedResponse);

        $this->client->expects($this->once())->method('get')->willReturn($this->response);

        $userData = [
            'login' => 'a login',
            'name' => 'a username',
            'email' => 'email@address.com',
            'avatar_url' => 'https://avatar.url.com',
            'html_url' => 'https://profile.url.com'
        ];
        $this->serializer->expects($this->once())->method('deserialize')->willReturn($userData);

        $githubUserProvider = new GithubUserProvider($this->client, $this->serializer);

        $user = $githubUserProvider->loadUserByUsername('a_user_access_token');
        $expectedUser = new User($userData['login'], $userData['name'], $userData['email'], $userData['avatar_url'], $userData['html_url']);

        $this->assertEquals($expectedUser, $user);
        $this->assertEquals('App\Entity\User', get_class($user));
    }

    public function testLoadUserByUsernameThatThrowAnException()
    {
        // $this->streamedResponse->method('getContents')->willReturn('foo');

        //$this->response->method('getBody')->willReturn($this->streamedResponse);

        $this->client->expects($this->once())->method('get')->willReturn($this->response);

        $this->serializer->expects($this->once())->method('deserialize')->willReturn([]);

        $this->expectException('LogicException');
        $githubUserProvider = new GithubUserProvider($this->client, $this->serializer);
        $githubUserProvider->loadUserByUsername('a_user_access_token');
    }
}
