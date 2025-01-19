import { Card, Switch } from "@nextui-org/react";
import { JSX, useCallback } from "react";
import { CampaignResource, useCampaigns } from "../campaigns.context.tsx";
import { toast } from "sonner";

type CampaignCardType = {
    campaign: CampaignResource;
};
const CampaignCard: ({ campaign }: CampaignCardType) => JSX.Element = ({
    campaign,
}) => {
    const { updateCampaignStatus } = useCampaigns();

    const handleSwitchToggle = useCallback(
        (campaignId: string, isSelected: boolean) => {
            updateCampaignStatus(campaignId, isSelected ? "active" : "paused")
                .then(() => toast.success("Status updated!"))
                .catch((error: any) =>
                    toast.error("Failed to update status!", {
                        description:
                            error?.response?.data?.message ??
                            error?.message ??
                            "Unknown error",
                    }),
                );
        },
        [updateCampaignStatus],
    );

    return (
        <Card key={campaign.id} className="p-6">
            <div className="flex items-start gap-4">
                <div className="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center text-lg">
                    🚀
                </div>
                <div className="flex-1">
                    <div className="flex items-start justify-between">
                        <div>
                            <h3 className="font-semibold">{campaign.title}</h3>
                            <p className="text-sm text-muted-foreground mt-1">
                                {campaign.landing_page_url}
                            </p>
                        </div>
                        <Switch
                            defaultSelected={
                                campaign.activity_status === "active"
                            }
                            onValueChange={(isSelected) =>
                                campaign.id &&
                                handleSwitchToggle(campaign.id, isSelected)
                            }
                            color="success"
                        ></Switch>
                    </div>
                    <div className="grid grid-cols-4 gap-4 mt-6">
                        {campaign?.payouts?.map((payout) => (
                            <div key={`payout-${payout?.id}`}>
                                <div className="text-xl font-semibold">
                                    {payout?.amount_per_interaction} $
                                </div>
                                <div className="flex items-center gap-2 mt-1">
                                    <span className="text-sm text-muted-foreground">
                                        {payout?.country?.name}
                                    </span>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </Card>
    );
};

export default CampaignCard;
