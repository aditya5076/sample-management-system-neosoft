<?php namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait GenericCloudComputing
{
    protected $adapter;
    protected $command;
    protected $preSignedRequest;
    /**
    * This is a generic trait which will be used overall in project for cloud computing related common functions.
    * @param folderName : Folder name on cloud
    * @param imageName : Image name on cloud
    * @return Pre-Signed : URL with 30 minutes validity.
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function generic_image_retrieval($folderName=null,$imageName=null,$wholePath=null)
    {
        $this->adapter = Storage::disk('do_spaces')->getDriver()->getAdapter(); 
        $this->command = $this->adapter->getClient()->getCommand('GetObject', [
            'Bucket' => $this->adapter->getBucket(),
            'Key'    => ( (!empty($wholePath)) ? ($this->adapter->getPathPrefix().$wholePath) : ($this->adapter->getPathPrefix().$folderName.'/'.$imageName.'.jpg') )
        ]);
        $this->preSignedRequest = $this->adapter->getClient()->createPresignedRequest($this->command, '+200 minute');
        return (string) $this->preSignedRequest->getUri();      
    } // end : generic_image_retrieval
}