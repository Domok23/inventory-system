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

    public function __construct(MaterialRequest $materialRequest)
    {
        $this->materialRequest = $materialRequest->load('inventory', 'project');
        Log::info('MaterialRequestUpdated Event Data:', $this->materialRequest->toArray());
    }

    public function broadcastOn()
    {
        return new Channel('material-requests');
    }
}
