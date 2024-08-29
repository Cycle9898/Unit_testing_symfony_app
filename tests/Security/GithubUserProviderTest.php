<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Security\GithubUserProvider;
use PHPUnit\Framework\TestCase;

class GithubUserProviderTest extends TestCase
{
    public function testLoadUserByUsernameThatReturnAUser()
    {
        /** @var \GuzzleHttp\Client&\PHPUnit\Framework\MockObject\MockObject $client */
        $client = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \JMS\Serializer\SerializerInterface&\PHPUnit\Framework\MockObject\MockObject $serializer */
        $serializer = $this->getMockBuilder('JMS\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $userData = [
            'login' => 'a login',
            'name' => 'a username',
            'email' => 'email@address.com',
            'avatar_url' => 'https://avatar.url.com',
            'html_url' => 'https://profile.url.com'
        ];
        $serializer->expects($this->once())->method('deserialize')->willReturn($userData);

        // $streamedResponse = $this->getMockBuilder('Psr\Http\Message\StreamInterface')
        //     ->getMock();
        // $streamedResponse->method('getContents')->willReturn('foo');

        $response = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->getMock();
        //$response->method('getBody')->willReturn($streamedResponse);

        $client->expects($this->once())->method('get')->willReturn($response);

        $githubUserProvider = new GithubUserProvider($client, $serializer);

        $user = $githubUserProvider->loadUserByUsername('a_user_access_token');
        $expectedUser = new User($userData['login'], $userData['name'], $userData['email'], $userData['avatar_url'], $userData['html_url']);

        $this->assertEquals($expectedUser, $user);
        $this->assertEquals('App\Entity\User', get_class($user));
    }
}
