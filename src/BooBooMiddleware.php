<?php
/**
 * Slim Framework BooBoo middleware (https://github.com/juliangut/slim-booboo-middleware)
 *
 * @link https://github.com/juliangut/slim-booboo-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-booboo-middleware/master/LICENSE
 */

namespace Jgut\Slim\Middleware;

use Slim\Middleware;
use League\BooBoo\Formatter\FormatterInterface;
use League\BooBoo\Formatter\CommandLineFormatter;
use League\BooBoo\Formatter\HtmlFormatter;
use League\BooBoo\Formatter\HtmlTableFormatter;
use League\BooBoo\Formatter\JsonFormatter;
use League\BooBoo\Formatter\NullFormatter;
use League\BooBoo\Handler\HandlerInterface;
use League\BooBoo\Runner;

/**
 * Session handler middleware.
 */
class BooBooMiddleware extends Middleware
{
    /**
     * Available BooBoo formatters.
     *
     * @var array
     */
    protected $formatterTypes = [
        'command-line' => 'League\BooBoo\Formatter\CommandLineFormatter',
        'html'         => 'League\BooBoo\Formatter\HtmlFormatter',
        'html-table'   => 'League\BooBoo\Formatter\HtmlTableFormatter',
        'json'         => 'League\BooBoo\Formatter\JsonFormatter',
        'null'         => 'League\BooBoo\Formatter\NullFormatter',
    ];

    /**
     * BooBoo formatters.
     *
     * @var array
     */
    protected $formatters = [];

    /**
     * BooBoo handlers.
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Get BooBoo formatters.
     *
     * @return League\BooBoo\Formatter\FormatterInterface[]
     */
    public function getFormatters()
    {
        return $this->formatters;
    }

    /**
     * Add BooBoo formatter.
     *
     * @param mixed $formatter
     * @param int $errorLimit
     * @return $this
     */
    public function addFormatter($formatter, $errorLimit = E_ALL)
    {
        if (is_string($formatter)) {
            $formatter = $this->createFormatter($formatter);
        }

        if (! $formatter instanceof FormatterInterface) {
            throw new \InvalidArgumentException('Formatter provided is not a valid BooBoo formatter');
        }

        if ($formatter->getErrorLimit() === E_ALL && $errorLimit !== E_ALL) {
            $formatter->setErrorLimit($errorLimit);
        }

        $this->formatters[] = $formatter;

        return $this;
    }

    /**
     * Create a BooBoo formatter.
     *
     * @param string $formatter
     * @return League\BooBoo\Formatter\FormatterInterface
     */
    protected function createFormatter($formatter)
    {
        if (in_array(trim($formatter, '\\'), $this->formatterTypes)) {
            $formatter = array_search(trim($formatter, '\\'), $this->formatterTypes);
        } elseif (!in_array($formatter, array_keys($this->formatterTypes))) {
            throw new \InvalidArgumentException(sprintf('Formatter "%s" is not supported', $formatter));
        }

        $formatterName = sprintf(
            'League\BooBoo\Formatter\\%s',
            implode('', array_map('ucfirst', explode('-', $formatter))) . 'Formatter'
        );

        return new $formatterName();
    }

    /**
     * Remove all BooBoo formatters.
     *
     * @return $this
     */
    public function emptyFormatters()
    {
        $this->formatters = [];

        return $this;
    }

    /**
     * Get BooBoo formatters.
     *
     * @return League\BooBoo\Formatter\HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * Add BooBoo handler.
     *
     * @param League\BooBoo\Formatter\HandlerInterface $handler
     * @return $this
     */
    public function addHandler(HandlerInterface $handler)
    {
        $this->handlers[] = $handler;

        return $this;
    }

    /**
     * Remove all BooBoo handlers.
     *
     * @return $this
     */
    public function emptyHandlers()
    {
        $this->handlers = [];

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function call()
    {
        $this->register();
        $this->next->call();
    }

    /**
     * Register BooBoo error handler.
     */
    public function register()
    {
        $runner = new Runner();

        foreach ($this->formatters as $formatter) {
            $runner->pushFormatter($formatter);
        }

        if (count($this->formatters) === 0) {
            $runner->pushFormatter(new NullFormatter());
        }

        foreach ($this->handlers as $handler) {
            $runner->pushHandler($handler);
        }

        $runner->register();
    }
}
