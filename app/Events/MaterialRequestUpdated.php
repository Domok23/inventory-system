<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use App\Models\MaterialRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MaterialRequestUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $materialRequest;
    public $action; // Tambahkan properti action

    public function __construct(MaterialRequest $materialRequest, string $action)
    {
        $this->materialRequest = is_array($materialRequest)
            ? collect($materialRequest)->map->load('inventory', 'project')
            : $materialRequest->load('inventory', 'project');
        $this->action = $action;
        Log::info('MaterialRequestUpdated Event Data:', [
            'materialRequest' => $this->materialRequest->toArray(),
            'action' => $this->action,
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('material-requests');
    }
}
