<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoteImportFailure extends Mailable
{   
    /**
     * This class is used to throw mails when remote import of MS-SQL fails.
     * @access Rights : Code-Level
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    use Queueable, SerializesModels;
    public $importTable;
    public $importDescription;
    public $status;
    public $importDate;
    public $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($importTable,$importDescription,$status,$importDate,$subject)
    {
        $this->importTable = $importTable;
        $this->importDescription = $importDescription;
        $this->status = $status;
        $this->importDate = $importDate;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('Emails.remote-import-failure');
    }
}
