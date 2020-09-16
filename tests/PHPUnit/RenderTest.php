<?php

namespace Tests\PHPUnit;

use IsakzhanovR\Ssr\Exceptions\NodeErrorException;
use Tests\TestCase;

class RenderTest extends TestCase
{
    public function testRenderJavaScript()
    {
        $strings = $this->getTestData();
        $result = ssr()
            ->entry('js/server.js')
            ->setData(compact('strings'))
            ->render();

        $this->assertEquals($this->expectedResult(), $result);
    }

    public function testRenderErrorFile()
    {
        $strings = $this->getTestData();
        $this->expectException(NodeErrorException::class);
        ssr()
            ->entry('js/server-broken.js')
            ->setData(compact('strings'))
            ->render();
    }

    public function testRenderErrorFileOnProduction()
    {
        app()->config->set('app.debug', false);
        $strings = $this->getTestData();
        $result = ssr()
            ->entry('js/server-broken.js')
            ->fallback('<div class="wrapper" id="wrapper"></div>') //this is mast to render client , if ssr is broken
            ->setData(compact('strings'))
            ->render();

        $this->assertEquals($this->expectedDefaultResult(), $result);
    }

    private function getTestData()
    {
        return collect([
            'h1' => 'this is test sting with key H1',
            'h2' => 'this is test sting with key H2',
            'h3' => 'this is test sting with key H3',
        ]);
    }

    private function expectedDefaultResult(): string
    {
        return '<div class="wrapper" id="wrapper"></div><script>var url = {"url":"\/"};var strings = {"h1":"this is test sting with key H1","h2":"this is test sting with key H2","h3":"this is test sting with key H3"}; </script>';
    }

    private function expectedResult(): string
    {
        return '<div id="wrapper" data-server-rendered="true" class="wrapper" data-v-f0eac044><div class="container" data-v-14a52c56 data-v-f0eac044><div class="card" data-v-14a52c56><div class="card-header" data-v-14a52c56><h2 data-v-14a52c56>Index page for client render with SSR </h2> <div data-v-14a52c56><ul data-v-14a52c56><li class="h1" data-v-14a52c56>this is test sting with key H1</li><li class="h2" data-v-14a52c56>this is test sting with key H2</li><li class="h3" data-v-14a52c56>this is test sting with key H3</li></ul></div></div></div></div></div><script>var url = {"url":"\/"};var strings = {"h1":"this is test sting with key H1","h2":"this is test sting with key H2","h3":"this is test sting with key H3"}; </script>';
    }
}
