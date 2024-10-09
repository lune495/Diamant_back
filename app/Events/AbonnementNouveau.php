<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AbonnementNouveau
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    use SerializesModels;

    public $utilisateur;
    public $auteur;

    public function __construct($utilisateur, $auteur)
    {
        $this->utilisateur = $utilisateur;
        $this->auteur = $auteur;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('abonnement-nouveau.' . $this->utilisateur->id);
    }
}
