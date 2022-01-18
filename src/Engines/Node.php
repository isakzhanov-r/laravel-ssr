<?php

namespace IsakzhanovR\Ssr\Engines;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use IsakzhanovR\Ssr\Exceptions\NodeErrorException;
use IsakzhanovR\Ssr\Exceptions\NodeNotFoundException;
use Symfony\Component\Process\Process;

class Node
{
    protected string $node_path;

    protected string $path;

    protected string $disk;

    public function __construct(array $temp_storage)
    {
        $this->path = Arr::get($temp_storage, 'path');
        $this->disk = Arr::get($temp_storage, 'disk');
        $this->nodePath();
    }

    public function nodePath(): void
    {
        if ($node_path = config('ssr.node_path')) {
            $this->node_path = $node_path;
        } else {
            $this->getNodePath();
        }
    }

    /**
     * @param $serverScript
     *
     * @return string
     * @throws \Exception
     */
    public function run($serverScript)
    {
        $file_name = $this->createTempFile($serverScript);
        $temp_file = Storage::disk($this->disk)->path($file_name);

        $process = new Process([$this->node_path, $temp_file]);

        try {
            return $process->mustRun()->getOutput();
        } catch (Exception $exception) {
            throw new NodeErrorException($exception->getMessage(), 400);
        } finally {
            Storage::disk($this->disk)->delete($file_name);
        }
    }

    protected function getNodePath(): void
    {
        $process = new Process(['which', 'node']);

        try {
            $process->mustRun();
            $this->node_path = trim($process->getOutput());
        } catch (Exception $exception) {
            throw new NodeNotFoundException($exception->getMessage(), 127);
        }
    }

    /**
     * @throws \Exception
     */
    protected function createTempFile($serverScript)
    {
        $file_name = implode(DIRECTORY_SEPARATOR, [$this->path, md5(intval(microtime(true) * 1000) . random_bytes(5)) . '.js']);

        Storage::disk($this->disk)->put($file_name, $serverScript);

        return $file_name;
    }
}
