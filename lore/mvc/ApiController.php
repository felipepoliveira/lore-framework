<?php
namespace lore\mvc;


use lore\Lore;
use lore\web\Response;

abstract class ApiController extends AbstractController
{
    /**
     * This method is not required in ApiController implementations, but is required
     * when using some methods like ApiController.sendModel() or AbstractController.putModelAsArrayInResponse()
     * @return Model|null
     */
    public function createNewModelInstance()
    {
        return null;
    }

    /**
     * Format an array to be sent into the response
     * @param array|object $data the array
     */
    protected function formatToArray(&$data){
        //Get each value of the $data array. If the value is an Model format to an array
        if(is_array($data)){
            foreach ($data as $key => $value){
                if(is_subclass_of($value, "lore\\mvc\\Model")){
                    $data[$key] = $value->toArray();
                }
            }
        }else{
            if(is_subclass_of($data, "lore\\mvc\\Model")){
                $data = $data->toArray();
            }
        }

    }

    /**
     * Send data to the client. This method has to be used in api services
     * @param mixed $data - The data that will be send
     * @param int $code - The status code
     */
    public function send($data = null, $code = 200){
        $this->response->setRedirect(false);
        $this->response->setCode($code);
        $this->response->setContentType(Lore::app()->getResponseManager()->getDataFormatter()->formatAsContentType());

        if(isset($data) && $data !== null){
            $this->formatToArray($data);
            $this->response->setData($data);
        }
    }

    public function sendModel($code = 200){
        $this->response->setData($this->model->toArray());
        $this->send(null, $code);
    }

    public function putModelAsArrayInResponse()
    {
        $this->response->add($this->model->toArray());
    }
}