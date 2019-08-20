<?php


namespace App\Helpers;

class SagasProcess
{
    private $command;
    private $compensation;
    private $parameters;

    /**
     * Sagas constructor.
     * @param $command
     * @param $compensation
     * @param $parameters
     */
    public function __construct($command, $compensation, $parameters)
    {
        $this->command = $command;
        $this->compensation = $compensation;
        $this->parameters = $parameters;
    }

    public function executeCommand()
    {
        $subject = $this->command[0];
        $method = $this->command[1];
        try {
            return $subject->$method($this->parameters);
        } catch (\Exception $e) {
            return $e;
        }

    }

    public function executeCompensation()
    {
        $subject = $this->compensation[0];
        $method = $this->compensation[1];
        try {
            return $subject->$method($this->parameters);
        } catch (\Exception $e) {
            return $e;
        }
    }


}