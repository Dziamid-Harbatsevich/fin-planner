import { Users } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

type Props = {
    count: number;
};

export default function TeamUsersCountWidget({ count }: Props) {
    return (
        <Card className="h-full">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">
                    Team Members
                </CardTitle>
                <Users className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
                <div className="text-2xl font-bold">{count}</div>
                <p className="text-xs text-muted-foreground">
                    Total users in your team
                </p>
            </CardContent>
        </Card>
    );
}
