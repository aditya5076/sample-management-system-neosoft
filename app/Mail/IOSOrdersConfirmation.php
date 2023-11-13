<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderConfirmationAttachment;

class IOSOrdersConfirmation extends Mailable
{
    /**
     * This class is used to throw mails when orders are confirmed from IOS devices by customers.
     * @access Rights : Code-Level
     * @param Array : Validation Declaration.
     * @param Constant : Declaration.
     * @param Parameter : Declaration.
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public $ordersData;
    public $subject;
    public $customerName;
    public $productPrice;
    public $orderID;
    public $orderDate;
    public $orderNote;
    public $orderCreatedBy;
    public $customerEmail;
    public $customerContactPerson;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ordersData,$subject,$customerName,$productPrice,$orderID,$orderDate,$orderNote,$orderCreatedBy,$customerEmail,$customerContactPerson)
    {
        $this->ordersData = $ordersData;
        $this->subject = $subject;
        $this->customerName = $customerName;
        $this->productPrice = sprintf('%0.2f', $productPrice);;
        $this->orderID = $orderID;
        $this->orderDate = $orderDate;
        $this->orderNote = $orderNote;
        $this->orderCreatedBy = $orderCreatedBy;
        $this->customerEmail = $customerEmail;
        $this->customerContactPerson = $customerContactPerson;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('Emails.ios-order-confirmation-emails')->attach(
            Excel::download(
                new OrderConfirmationAttachment($this->ordersData,$this->productPrice), 
                'order_confirmation_'.$this->orderID.'.xlsx'
            )->getFile(), ['as' => 'order_confirmation_'.$this->orderID.'.xlsx']
        );
    }
}
