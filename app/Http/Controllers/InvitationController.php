<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TeamInvitation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InvitationController extends Controller
{
    /**
     * Display the invitation details.
     */
    public function show(Request $request, TeamInvitation $invitation): Response
    {
        $invitation->load(['team', 'inviter']);

        return Inertia::render('invitations/show', [
            'invitation' => [
                'code' => $invitation->code,
                'teamName' => $invitation->team->name,
                'inviterName' => $invitation->inviter->name,
                'email' => $invitation->email,
                'role' => $invitation->role->value,
                'roleLabel' => $invitation->role->label(),
                'expiresAt' => $invitation->expires_at?->toISOString(),
                'isExpired' => $invitation->isExpired(),
                'isAccepted' => $invitation->isAccepted(),
            ],
        ]);
    }
}
