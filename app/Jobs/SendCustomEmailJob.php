<?php

namespace App\Jobs;

use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCustomEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $emailLogId;
    public int $tries = 5;
    public array $backoff = [2, 5, 10, 20, 30];

    public function __construct(int $emailLogId)
    {
        $this->emailLogId = $emailLogId;
    }

    public function handle(): void
    {
        $log = EmailLog::find($this->emailLogId);
        if (!$log) {
            return;
        }

        try {
            $name = 'Customer';
            if ($log->recipient_id) {
                $name = User::find($log->recipient_id)?->name ?? $name;
            }
            Mail::send('emails.custom_template', [
                'subject' => $log->subject,
                'body' => $log->body,
                'name' => $name,
            ], function ($message) use ($log) {
                $message->to($log->recipient_email)
                    ->subject($log->subject !== '' ? $log->subject : 'Message from Support');
            });

            $log->update([
                'status' => 'sent',
                'error_message' => null,
            ]);
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            Log::warning('SendCustomEmailJob failed: ' . $message);

            if ($this->isRateLimit($message) && $this->attempts() < $this->tries) {
                $delay = $this->backoff[min($this->attempts() - 1, count($this->backoff) - 1)];
                $log->update([
                    'status' => 'queued',
                    'error_message' => null,
                ]);
                $this->release($delay);
                return;
            }

            $log->update([
                'status' => 'failed',
                'error_message' => $message,
            ]);
        }
    }

    private function isRateLimit(string $message): bool
    {
        return str_contains($message, 'Too many emails per second')
            || str_contains($message, '550 5.7.0');
    }
}
