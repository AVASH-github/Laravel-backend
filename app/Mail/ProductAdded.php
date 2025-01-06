<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductAdded extends Mailable
{


    use Queueable, SerializesModels;

    public $productName;
    public $productCategory;
    public $productSubcategory;
    public $productDescription;
    public $productStatus;

    /**
     * Create a new message instance.
     */
    public function __construct($productName,$productCategory,$productSubcategory,$productDescription,$productStatus)
    {
        $this->productName=$productName;
        $this->productCategory=$productCategory;
        $this->productSubcategory=$productSubcategory;
        $this->productDescription=$productDescription;
        $this->productStatus=$productStatus;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Product Added',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.product_added',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
