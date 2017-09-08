<?php
namespace lore\mvc;


use lore\Lore;

abstract class ApiController extends Controller
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
     * Put the identified errors in response. This method must be called after the AbstractController->validateModel
     * or AbstractController->loadAndValidateModel. and before the ApiController->send method
     * @see Controller
     */
    public function putErrorsInResponse(){
        //Check if the response contains e'rrors
        if($this->response->hasErrors()){
            $this->response->put("errors", $this->response->getErrors());
        }
    }

    /**
     * Send data to the client. This method has to be used in api services
     * @param mixed $data - The data that will be send
     * @param int $code - The status code
     * @param  int $responseType - The response type. Use the DataFormatter constants (JSON, XML, TXT)
     */
    public function send($data = null, $code = 200, $responseType = null){
        $this->response->setRedirect(false);
        $this->response->setCode($code);

        //If the response was not defined, set it as the default format type
        if(!isset($responseType)){
            $responseType = Lore::app()->getResponseManager()->getDataFormatter()->getDefaultFormatType();
        }

        //Define the format type and put the content type on response
        Lore::app()->getResponseManager()->getDataFormatter()->setFormatType($responseType);
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