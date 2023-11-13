<?php namespace App\Http\Traits;

trait GenericRestfulData
{
    /**
    * This is a generic trait which will be used overall in project for static restful data.
    * @param Data : Data & Parameters
    * @return Array
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    protected $static_restful_data = [
        'success_code' => 200, 
        'error_code' => 400, 
        'validation_failure' => 422,
        'success_store_message' => 'Details saved successfully.', 
        'success_fetch_message' => 'Details fetched successfully.', 
        'server_error_message' => 'Something went wrong,Try again!', 
        'error_fetch_message' => 'No data found!',
        'invalid_credentials_message' => 'Invalid credentials. Please enter correct details.',
        'login_success_message' => 'Logged in successfully.',
        'snapshot_mail_send' => 'Snapshot mail has been sent successfully!'
    ];
}