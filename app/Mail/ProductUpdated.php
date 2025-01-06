<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductUpdated extends Mailable
{
    use Queueable, SerializesModels;

   public $productName;
   public $productCategory;
   public $productSubcategory;
   public $productDescription;

   public $productStatus;

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
            subject: 'Product Updated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.product_updated',
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
