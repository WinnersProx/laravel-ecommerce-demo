<?php

namespace App\Mail;

use App\Models\ProductOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductOrderCompleted extends Mailable
{
    use Queueable, SerializesModels;


    public ProductOrder $productOrder;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProductOrder $productOrder)
    {
        $this->productOrder = $productOrder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('view.name');
    }
}
