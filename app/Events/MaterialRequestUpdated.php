<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MaterialRequestUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $data;
    public $action;

    public function __construct($materialRequest, string $action)
    {
        // Kirim hanya id dan status, bukan seluruh object beserta relasi
        if (is_array($materialRequest) || $materialRequest instanceof \Illuminate\Support\Collection) {
            $this->data = collect($materialRequest)
                ->map(function ($mr) {
                    return [
                        'id' => $mr->id,
                        'status' => $mr->status,
                    ];
                })
                ->values()
                ->toArray();
        } else {
            $this->data = [
                'id' => $materialRequest->id,
                'status' => $materialRequest->status,
            ];
        }
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return new Channel('material-requests');
    }

    // Pastikan hanya data minimal yang dikirim ke frontend
    public function broadcastWith()
    {
        return [
            'data' => $this->data,
            'action' => $this->action,
        ];
    }
}
