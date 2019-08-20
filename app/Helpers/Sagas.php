<?php


namespace App\Helpers;


use App\services\PromiseService;
use Illuminate\Support\Collection;
use GuzzleHttp\Promise;
class Sagas
{
    private $process;
    private $promiseSrv;

    /**
     * Sagas constructor.
     * @param $process
     * @param $promiseSrv
     */

    public function __construct(Collection $process)
    {
        $this->process = $process;
        $this->promiseSrv = new PromiseService();
    }

    private function toPromise($maybePromise){

        if(gettype($maybePromise) != 'object'){
            return $this->promiseSrv->getFulfillPromise($maybePromise);
        }
        if(is_subclass_of($maybePromise, 'Promise') || is_a($maybePromise, "GuzzleHttp\Promise\Promise")){
            return $maybePromise;
        }else{
            if(is_subclass_of($maybePromise, 'Exception')){
                return $this->promiseSrv->getRejectedPromise($maybePromise->getMessage());
            }
            else{
                return $this->promiseSrv->getFulfillPromise($maybePromise);
            }
        }
    }

    public function execute()
    {
        $promises = $this->process->map(function ($process) {
            return $this->toPromise($process->executeCommand());
        });
        try{
            return (object)[ 'data' => $this->promiseSrv->all($promises), 'status' => 200];
        }
        catch (\Exception $exception){

            $this->process->each(
                function ($process) use ($exception) {
                    $process->executeCompensation();
                }
            );
            $code = $exception->getCode() !== 0 ? $exception->getCode() : 500;
            return (object)[ "data" => $exception->getMessage(), 'status' => $code];
        }
    }
}
