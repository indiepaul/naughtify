<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class Message extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     */
    public function __construct(public $message)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if (env('APP_ENV') == 'testing') {
            return ['database'];
        }
        return ['database','telegram'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toTelegram($notifiable)
    {
        if(isset($notifiable->routes) && $notifiable->routes['telegram'] != null){
            return TelegramMessage::create($this->message)
                ->to($notifiable->routes['telegram']);
        }
        return TelegramMessage::create($this->message)
            ->to($notifiable
                ->channels()
                ->firstWhere('name','Telegram')
                ->identifier)
            // Markdown supported.
            // ->content()
            // ->line("Your invoice has been *PAID*")
            // ->lineIf($notifiable->amount > 0, "Amount paid: {$notifiable->amount}")
            // ->line("Thank you!")

            // (Optional) Blade template for the content.
            // ->view('notification', ['url' => $url])

            // (Optional) Inline Buttons
            // ->button('View Invoice', $url)
            // ->button('Download Invoice', $url)
            // (Optional) Inline Button with callback. You can handle callback in your bot instance
            // ->buttonWithCallback('Confirm', 'confirm_invoice ' . $this->invoice->id);
            ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message
        ];
    }
}
