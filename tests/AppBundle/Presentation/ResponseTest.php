<?php

namespace AppBundle\Presentation;

use GuzzleHttp\Psr7;
use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Resource\Item;
use League\Fractal\Scope;
use Psr\Http\Message\StreamInterface;

/**
 * @group unit
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function return ()
    {
        $fractalScope = $this->getFractalScopeMock(['toArray']);
        $fractalScope
            ->expects($this->once())
            ->method('toArray')
            ->willReturn(['id' => 123]);

        $fractalManager = $this->getFractalManagerMock(['createData']);
        $fractalManager
            ->expects($this->once())
            ->method('createData')
            ->willReturn($fractalScope);


        /** @var Response $response */
        $response = new class($fractalManager) extends Response
        {

            protected function createResource() : ResourceInterface
            {
                $resource = new Item($this->getContent());
                $eTag = $this->getETag();
                if (!empty($eTag)) {
                    $resource->setMetaValue('ETag', $eTag);
                }

                return $resource;
            }
        };

        $httpMessageStreamMock = $this->getHttpMessageStreamMock(['getContent']);
        $psr7ResponseMock = $this->getPsr7ResponseMock(['getBody', 'getHeader']);
        $psr7ResponseMock
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($httpMessageStreamMock);
        $psr7ResponseMock
            ->expects($this->exactly(2))
            ->method('getHeader')
            ->with('ETag')
            ->willReturn(['"05a6ac2c75c10f981f2348303f5e51c12"']);

        $actualResponse = $response->return($psr7ResponseMock);
        $expectedContent = '{"id":123}';

        $this->assertSame($expectedContent, $actualResponse->getContent());
    }


    private function getPsr7ResponseMock(array $methods = null): \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getMockBuilder(Psr7\Response::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    private function getFractalManagerMock(array $methods = null): \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    private function getFractalScopeMock(array $methods = null): \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getMockBuilder(Scope::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    private function getHttpMessageStreamMock(array $methods = null): \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getMockBuilder(StreamInterface::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }

}
