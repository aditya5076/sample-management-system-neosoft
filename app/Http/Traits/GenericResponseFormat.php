<?php namespace App\Http\Traits;

trait GenericResponseFormat
{
    /**
    * This is a generic trait which will be used overall in project as response format for API's.
    * @param Status : Boolean
    * @param Code : Status code of API
    * @param Message : Custom Message
    * @param Data : Data & Parameters
    * @param Pagination : Default(N)
    * @return Array
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function response_format($status,$code,$message=null,$data=null,$paginate='N')
    {   
        $resp['status'] = $status;
        $resp['code'] = $code;
        $resp['message'] = $message;
        if($paginate == 'N'){
            $resp['data'] = $data;
        }else{
            $resp = $data;
        }
        return response()->json($resp, $code);
    }
}