<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'related_entity_id' => $this->related_entity_id,
            'subject' => $this->subject,
            'message' => $this->message,
            'channel' => $this->channel,
            'status' => $this->status,
            'sent_at' => $this->sent_at,
            'failed_reason' => $this->failed_reason,
            'retry_count' => $this->retry_count,
            'next_retry_at' => $this->next_retry_at,
            'created_at' => $this->created_at,
        ];
    }
}
