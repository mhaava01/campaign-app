import {
    Button,
    Input,
    Modal,
    ModalBody,
    ModalContent,
    ModalFooter,
    ModalHeader,
    Switch,
} from "@nextui-org/react";
import {
    FormEventHandler,
    ReactNode,
    useCallback,
    useEffect,
    useState,
} from "react";
import { StoreCampaignDataType, useCampaigns } from "../campaigns.context.tsx";
import { toast } from "sonner";
import useSWR from "swr";
import { route } from "../../../api";
import { components } from "../../../schema";
type CountryResource = components["schemas"]["CountryResource"];

const CampaignModal = ({
    isOpen,
    onOpenChange,
}: {
    isOpen: boolean;
    onOpenChange: () => void;
}) => {
    const { storeCampaign } = useCampaigns();
    const [campaignData, setCampaignData] = useState<StoreCampaignDataType>({});

    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);

    const { data: countries } = useSWR<Partial<CountryResource>[]>([
        route("countries.index"),
        false,
    ]);

    const handleSubmit = useCallback<FormEventHandler<HTMLFormElement>>(
        (event) => {
            event.preventDefault();
            setIsSubmitting(true);
            storeCampaign(campaignData)
                .then(() => {
                    toast.success("Campaign created!");
                    onOpenChange();
                    setCampaignData({});
                })
                .catch((error: any) =>
                    toast.error("Failed to create campaign!", {
                        description:
                            error?.response?.data?.message ??
                            error?.message ??
                            "Unknown error",
                    }),
                )
                .finally(() => setIsSubmitting(false));
        },
        [storeCampaign, campaignData, onOpenChange],
    );

    const handlePayoutChange = useCallback(
        (value: string, countryId?: string) => {
            if (!countryId) return;

            setCampaignData((prevState) => {
                const payouts = prevState?.payouts ?? [];
                const countryIndex = payouts?.findIndex(
                    (payout) => payout.country_id === countryId,
                );
                const newPayout = {
                    country_id: countryId,
                    amount_per_interaction: parseFloat(value),
                };
                if (countryIndex === -1) {
                    payouts.push(newPayout);
                } else {
                    payouts[countryIndex] = newPayout;
                }

                return {
                    ...prevState,
                    payouts: payouts.filter(
                        (payout) => !isNaN(payout.amount_per_interaction),
                    ),
                };
            });
        },
        [],
    );

    useEffect(() => {
        if (isOpen) setCampaignData({});
    }, [isOpen]);

    return (
        <Modal isOpen={isOpen} onOpenChange={onOpenChange}>
            <ModalContent>
                {(onClose) =>
                    (
                        <>
                            <ModalHeader className="flex flex-col gap-1">
                                Create Campaign
                            </ModalHeader>
                            <ModalBody>
                                <form
                                    id="campaign-modal-form"
                                    className="flex flex-col gap-3"
                                    onSubmit={handleSubmit}
                                >
                                    <Input
                                        placeholder="Title"
                                        variant="bordered"
                                        onValueChange={(title) =>
                                            setCampaignData((prev) => ({
                                                ...prev,
                                                title,
                                            }))
                                        }
                                        maxLength={255}
                                    />
                                    <Input
                                        placeholder="Landing Page URL"
                                        variant="bordered"
                                        type="url"
                                        onValueChange={(landing_page_url) =>
                                            setCampaignData((prev) => ({
                                                ...prev,
                                                landing_page_url,
                                            }))
                                        }
                                        maxLength={255}
                                    />
                                    <Switch
                                        onValueChange={(isActive) =>
                                            setCampaignData((prev) => ({
                                                ...prev,
                                                activity_status: isActive
                                                    ? "active"
                                                    : "paused",
                                            }))
                                        }
                                        color="success"
                                    >
                                        {campaignData?.activity_status ===
                                        "active"
                                            ? "Active"
                                            : "Paused"}
                                    </Switch>
                                    <span className="text-lg text-gray-500">
                                        Payouts
                                    </span>
                                    {countries?.map((country) => (
                                        <div
                                            key={`country-${country?.id}`}
                                            className="grid grid-cols-2"
                                        >
                                            <span>{country?.name}</span>
                                            <Input
                                                onValueChange={(value) =>
                                                    handlePayoutChange(
                                                        value,
                                                        country?.id,
                                                    )
                                                }
                                                inputMode="decimal"
                                                type="number"
                                                placeholder="0.00"
                                                endContent={
                                                    <div className="pointer-events-none flex items-center">
                                                        <span className="text-default-400 text-small">
                                                            $
                                                        </span>
                                                    </div>
                                                }
                                            />
                                        </div>
                                    ))}
                                </form>
                            </ModalBody>
                            <ModalFooter>
                                <Button
                                    color="danger"
                                    variant="light"
                                    onPress={onClose}
                                >
                                    Close
                                </Button>
                                <Button
                                    isLoading={isSubmitting}
                                    type="submit"
                                    form="campaign-modal-form"
                                    color="primary"
                                >
                                    Create
                                </Button>
                            </ModalFooter>
                        </>
                    ) as ReactNode
                }
            </ModalContent>
        </Modal>
    );
};

export default CampaignModal;
