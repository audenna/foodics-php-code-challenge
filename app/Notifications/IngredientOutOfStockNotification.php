<?php

namespace App\Notifications;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IngredientOutOfStockNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param Ingredient $ingredient
     */
    public function __construct(protected Ingredient $ingredient) { }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->view('emails.notify', ['ingredient' => $this->ingredient])
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Ingredient availability Notification - '. $this->ingredient->getName());
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
