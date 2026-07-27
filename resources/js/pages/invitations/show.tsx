import { Head, router } from '@inertiajs/react';
import { Check, X } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { accept, decline } from '@/routes/invitations';

type Props = {
    invitation: {
        code: string;
        teamName: string;
        inviterName: string;
        email: string;
        role: string;
        roleLabel: string;
        expiresAt?: string;
        isExpired: boolean;
        isAccepted: boolean;
    };
};

export default function InvitationShow({ invitation }: Props) {
    const [processing, setProcessing] = useState(false);
    const [action, setAction] = useState<'accept' | 'decline' | null>(null);

    const handleAccept = () => {
        setProcessing(true);
        setAction('accept');
        router.visit(accept(invitation.code), {
            onFinish: () => {
                setProcessing(false);
                setAction(null);
            },
        });
    };

    const handleDecline = () => {
        setProcessing(true);
        setAction('decline');
        router.visit(decline(invitation.code), {
            onFinish: () => {
                setProcessing(false);
                setAction(null);
            },
        });
    };

    if (invitation.isAccepted) {
        return (
            <>
                <Head title="Invitation Accepted" />
                <div className="flex min-h-screen items-center justify-center p-4">
                    <Card className="w-full max-w-md">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <Check className="h-5 w-5 text-green-600" />
                                Invitation Accepted
                            </CardTitle>
                            <CardDescription>
                                You have already accepted this invitation
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <p className="text-sm text-muted-foreground">
                                You are now a member of the "
                                {invitation.teamName}" team.
                            </p>
                        </CardContent>
                    </Card>
                </div>
            </>
        );
    }

    if (invitation.isExpired) {
        return (
            <>
                <Head title="Invitation Expired" />
                <div className="flex min-h-screen items-center justify-center p-4">
                    <Card className="w-full max-w-md">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <X className="h-5 w-5 text-red-600" />
                                Invitation Expired
                            </CardTitle>
                            <CardDescription>
                                This invitation has expired
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <p className="text-sm text-muted-foreground">
                                Please contact {invitation.inviterName} for a
                                new invitation.
                            </p>
                        </CardContent>
                    </Card>
                </div>
            </>
        );
    }

    return (
        <>
            <Head title="Team Invitation" />
            <div className="flex min-h-screen items-center justify-center p-4">
                <Card className="w-full max-w-md">
                    <CardHeader>
                        <CardTitle>Team Invitation</CardTitle>
                        <CardDescription>
                            You've been invited to join a team
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="space-y-2">
                            <h3 className="text-lg font-semibold">
                                {invitation.teamName}
                            </h3>
                            <p className="text-sm text-muted-foreground">
                                {invitation.inviterName} has invited you to join
                                this team.
                            </p>
                        </div>

                        <Separator />

                        <div className="space-y-2">
                            <div className="flex justify-between">
                                <span className="text-sm font-medium">
                                    Email
                                </span>
                                <span className="text-sm">
                                    {invitation.email}
                                </span>
                            </div>
                            <div className="flex justify-between">
                                <span className="text-sm font-medium">
                                    Role
                                </span>
                                <span className="text-sm">
                                    {invitation.roleLabel}
                                </span>
                            </div>
                            {invitation.expiresAt && (
                                <div className="flex justify-between">
                                    <span className="text-sm font-medium">
                                        Expires
                                    </span>
                                    <span className="text-sm">
                                        {new Date(
                                            invitation.expiresAt,
                                        ).toLocaleDateString()}
                                    </span>
                                </div>
                            )}
                        </div>

                        <Separator />

                        <div className="rounded-lg bg-blue-50 p-4 dark:bg-blue-950">
                            <p className="text-sm text-blue-800 dark:text-blue-200">
                                To accept this invitation, you need to log in or
                                create an account. If you're not logged in,
                                you'll be redirected to the login page.
                            </p>
                        </div>
                    </CardContent>
                    <CardFooter className="flex flex-col gap-3">
                        <div className="flex w-full gap-3">
                            <Button
                                variant="outline"
                                className="flex-1"
                                onClick={handleDecline}
                                disabled={processing}
                            >
                                {processing && action === 'decline'
                                    ? 'Declining...'
                                    : 'Decline'}
                            </Button>
                            <Button
                                className="flex-1"
                                onClick={handleAccept}
                                disabled={processing}
                            >
                                {processing && action === 'accept'
                                    ? 'Accepting...'
                                    : 'Accept Invitation'}
                            </Button>
                        </div>
                        <p className="text-center text-xs text-muted-foreground">
                            By accepting, you'll be added to the team with the "
                            {invitation.roleLabel}" role.
                        </p>
                    </CardFooter>
                </Card>
            </div>
        </>
    );
}
