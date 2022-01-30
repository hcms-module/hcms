<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use App\Application\Admin\Lib\Render;
use App\Application\Admin\Lib\RenderParam;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class HttpExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @Inject()
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject()
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @inject()
     * @var RequestInterface
     */
    protected $request;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $description = $throwable->getMessage();
        $app_env = $this->config->get('app_env', 'dev');
        if ($app_env === 'dev') {
            $location = sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile());
            $content = $throwable->getTraceAsString();
        } else {
            $location = '';
            $content = '';
        }

        $this->stopPropagation();
        $render = new Render($this->container, $this->config);
        $renderParam = new RenderParam(compact('description', 'location', 'content'));

        return $render->render('error', $renderParam->getData());
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof NotFoundException || $throwable instanceof HttpException;
    }
}
