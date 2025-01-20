import {
    Button,
    Input,
    Select,
    SelectItem,
    useDisclosure,
} from "@nextui-org/react";
import { useCampaigns } from "./campaigns.context";
import CampaignModal from "./components/campaign.modal.tsx";
import CampaignCard from "./components/campaign.card.tsx";
import { JSX } from "react";

const CampaignsScreen = () => {
    const { isOpen, onOpen, onOpenChange } = useDisclosure();
    const {
        campaignsData,
        campaignsDataSize,
        setCampaignsDataSize,
        campaignFilters,
        setCampaignFilters,
    } = useCampaigns();

    return (
        <div className="space-y-6">
            <div className="flex flex-col-reverse sm:flex-row justify-between items-center gap-2">
                <div className="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                    <div className="min-w-64 w-full">
                        <Input
                            value={campaignFilters?.search}
                            onValueChange={(value) =>
                                setCampaignFilters((prevState) => ({
                                    ...prevState,
                                    search: value,
                                }))
                            }
                            placeholder="Search campaigns..."
                            className="w-full"
                            size="lg"
                        />
                    </div>
                    <Select
                        size="sm"
                        onSelectionChange={(keys) =>
                            setCampaignFilters((prevState) => ({
                                ...prevState,
                                activity_status: keys?.values()?.next()?.value,
                            }))
                        }
                        className="min-w-36"
                        label="Filter by status"
                    >
                        {["active", "paused"].map(
                            (status): JSX.Element => (
                                <SelectItem key={status}>
                                    {status.toUpperCase()}
                                </SelectItem>
                            ),
                        )}
                    </Select>
                </div>

                <Button
                    onPress={onOpen}
                    className="bg-primary w-full sm:w-auto"
                >
                    + Create Campaign
                </Button>
            </div>

            <div className="grid gap-4">
                {campaignsData?.map((campaigns) =>
                    campaigns?.map((campaign) => (
                        <CampaignCard key={campaign.id} campaign={campaign} />
                    )),
                )}
                <Button
                    isDisabled={
                        campaignsData &&
                        campaignsData[campaignsData.length - 1]?.length < 5
                    }
                    onPress={() => setCampaignsDataSize(campaignsDataSize + 1)}
                >
                    Load More
                </Button>
            </div>
            <CampaignModal isOpen={isOpen} onOpenChange={onOpenChange} />
        </div>
    );
};

export default CampaignsScreen;
