import { router } from '@inertiajs/react';
import { Mail } from 'lucide-react';
import { useState } from 'react';
import TeamInvitationController from '@/actions/App/Http/Controllers/Teams/TeamInvitationController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { DashboardInvitation } from '@/types';

type Props = {
    invitations: DashboardInvitation[];
};

export default function PendingInvitationsWidget({ invitations }: Props) {
    const [processingCode, setProcessingCode] = useState<string | null>(null);

    const acceptInvitation = (invitation: DashboardInvitation) => {
        router.visit(TeamInvitationController.accept(invitation), {
            onStart: () => setProcessingCode(invitation.code),
            onFinish: () => setProcessingCode(null),
        });
    };

    if (invitations.length === 0) {
        return null;
    }

    return (
        <Card className="h-full">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">
                    Pending Invitations
                </CardTitle>
                <Mail className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
                <div className="space-y-3">
                    {invitations.slice(0, 3).map((invitation) => (
                        <div
                            key={invitation.code}
                            className="flex items-center justify-between rounded-lg border p-3"
                        >
                            <div className="space-y-1">
                                <p className="font-medium">
                                    {invitation.team.name}
                                </p>
                                <p className="text-xs text-muted-foreground">
                                    {invitation.inviterName} invited you
                                </p>
                            </div>
                            <div className="flex gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    disabled={
                                        processingCode === invitation.code
                                    }
                                    onClick={() => acceptInvitation(invitation)}
                                >
                                    Accept
                                </Button>
                            </div>
                        </div>
                    ))}
                    {invitations.length > 3 && (
                        <div className="text-center">
                            <Badge variant="outline">
                                +{invitations.length - 3} more
                            </Badge>
                        </div>
                    )}
                </div>
                <p className="mt-4 text-xs text-muted-foreground">
                    You have {invitations.length} pending team invitation
                    {invitations.length !== 1 ? 's' : ''}
                </p>
            </CardContent>
        </Card>
    );
}
