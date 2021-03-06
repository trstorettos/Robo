<?php
namespace Robo\Task\Testing;

use Robo\Contract\CommandInterface;
use Robo\Contract\PrintedInterface;
use Robo\Task\BaseTask;

/**
 * Runs [atoum](http://atoum.org/) tests
 *
 * ``` php
 * <?php
 * $this->taskAtoum()
 *  ->file('path/to/test.php')
 *  ->configFile('config/dev.php')
 *  ->run()
 *
 * ?>
 * ```
 */
class Atoum extends BaseTask implements CommandInterface, PrintedInterface
{
    use \Robo\Common\ExecOneCommand;

    protected $command;

    public function __construct($pathToAtoum = null)
    {
        $this->command = $pathToAtoum;
        if (!$this->command) {
            $this->command = $this->findExecutable('atoum');
        }
        if (!$this->command) {
            throw new \Robo\Exception\TaskException(__CLASS__, "Neither local atoum nor global composer installation not found");
        }
    }

    /**
     * Tag or Tags to filter.
     *
     * @param string|array $tags
     *
     * @return $this
     */
    public function tags($tags)
    {
        return $this->addMultipleOption('tags', $tags);
    }

    /**
     * Display result using the light reporter.
     *
     * @return $this
     */
    public function lightReport()
    {
        $this->option("--use-light-report");

        return $this;
    }

    /**
     * Display result using the tap reporter.
     *
     * @return $this
     */
    public function tap()
    {
        $this->option("use-tap-report");

        return $this;
    }

    /**
     * Path to the bootstrap file.

     * @param $file
     *
     * @return $this
     */
    public function bootstrap($file)
    {
        $this->option("bootstrap", $file);

        return $this;
    }

    /**
     * Path to the config file.
     *
     * @param string $file
     *
     * @return $this
     */
    public function configFile($file)
    {
        $this->option('-c', $file);

        return $this;
    }

    /**
     * Use atoum's debug mode.
     *
     * @return $this
     */
    public function debug()
    {
        $this->option("debug");

        return $this;
    }

    /**
     * Test file ou test files to run.
     *
     * @param string|array
     *
     * @return $this
     */
    public function files($files)
    {
        return $this->addMultipleOption('f', $files);
    }

    /**
     * Test directory or directories to run.
     *
     * @param string|array A single directory or a list of directories.
     *
     * @return $this
     */
    public function directories($directories)
    {
        return $this->addMultipleOption('directories', $directories);
    }

    /**
     * @param string $option
     * @param string|array $values
     *
     * @return $this
     */
    protected function addMultipleOption($option, $values)
    {
        if (is_string($values)) {
            $values = [$values];
        }

        foreach ($values as $value) {
            $this->option($option, $value);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command . $this->arguments;
    }

    /**
     * @return \Robo\Result
     */
    public function run()
    {
        $this->printTaskInfo('Running atoum ' . $this->arguments);

        return $this->executeCommand($this->getCommand());
    }
}
