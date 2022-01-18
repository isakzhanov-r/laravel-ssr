<?php

namespace IsakzhanovR\Ssr\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use IsakzhanovR\Ssr\Engines\Node;
use IsakzhanovR\Ssr\Exceptions\NodeErrorException;
use IsakzhanovR\Ssr\Exceptions\NodeNotFoundException;

class Renderer
{
    protected array $data = [];

    protected array $env = [];

    protected string $fallback = '';

    protected $entry;

    protected $stringify;

    public function __construct(
        protected Node $node
    )
    {
    }

    public function entry(string $file_path)
    {
        [$path] = explode('?', $file_path);

        $this->entry = public_path($path);

        return $this;
    }

    public function fallback(string $fallback)
    {
        $this->fallback = $fallback;

        return $this;
    }

    public function setData(array $items)
    {
        foreach ($items as $key => $value) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    public function render(bool $appendData = true)
    {
        try {
            $serverScript = implode(';', [
                $this->dispatchScript(),
                $this->applicationData(),
                $this->applicationScript(),
            ]);
            $result       = json_decode($this->node->run($serverScript));
        } catch (Exception $exception) {
            if (config('app.debug') === false) {
                return $this->defaultResult($exception);
            }

            throw new NodeErrorException($exception->getMessage(), $exception->getCode());
        }
        if (!$appendData) {
            return $result;
        }

        return $this->appendData($result);
    }

    protected function applicationScript(): string
    {
        if (!file_exists($this->entry)) {
            throw new NodeNotFoundException('Server js file not found', 400);
        }

        return file_get_contents($this->entry);
    }

    protected function dispatchScript(): string
    {
        return 'var dispatch = function (result) {
        return console.log(JSON.stringify(result))}';
    }

    protected function applicationData()
    {
        $stringify = sprintf('var url = %s;', json_encode(['path' => request()->getRequestUri()]));
        $context   = empty($this->data) ? [] : $this->data;

        foreach ($context as $key => $value) {
            $stringify .= sprintf("var {$key} = %s; ", json_encode($value));
        }
        $this->stringify = $stringify;

        return $this->stringify;
    }

    protected function appendData(&$result)
    {
        return $result .= '<script type="application/javascript">' . $this->stringify . '</script>';
    }

    protected function defaultResult(Exception $exception)
    {
        Log::debug($exception->getMessage());

        return $this->appendData($this->fallback);
    }
}
