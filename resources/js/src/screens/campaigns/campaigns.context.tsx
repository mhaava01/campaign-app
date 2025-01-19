import api, {route} from "../../api";
import {useAuth} from "../../contexts/auth.context";
import {createContext, JSX, useCallback, useContext, useMemo, useState} from "react";
import {components} from "../../schema";
import useSWRInfinite from "swr/infinite";

export type CampaignResource = components["schemas"]["CampaignResource"]

export type CampaignFiltersType = {
    search?: string
    activity_status?: 'active' | 'paused'
}

export type StoreCampaignDataType = {
    title?: string
    landing_page_url?: string
    activity_status?: 'active' | 'paused'
    payouts?: {
        country_id: string,
        amount_per_interaction: number
    }[]
}

type CampaignsContextType = {
    campaignsData?: CampaignResource[][]
    campaignsDataSize: number
    setCampaignsDataSize: (size: number) => Promise<any[] | undefined>
    storeCampaign: (data: StoreCampaignDataType) => Promise<void>
    updateCampaignStatus: (campaignId: string, status: ("active" | "paused")) => Promise<void>
    campaignFilters?: CampaignFiltersType,
    setCampaignFilters: (campaignFilters: (prevState: CampaignFiltersType) => CampaignFiltersType) => void
}

const CampaignsContext = createContext<CampaignsContextType>({
    campaignsData: [],
    campaignsDataSize: 0,
    setCampaignsDataSize: (campaignsDataSize) => Promise.resolve(undefined),
    storeCampaign: (data) => Promise.resolve(),
    updateCampaignStatus: (campaignId, status) => Promise.resolve(),
    campaignFilters: undefined,
    setCampaignFilters: (campaignFilters) => {}
})

export const CampaignsContextProvider: ({children}: { children: any }) => JSX.Element = ({ children }) => {
    const {currentWorkspace} = useAuth()

    const [campaignFilters, setCampaignFilters] = useState<CampaignFiltersType>()

    const campaignsRequest = useCallback((pageIndex: number, previousPageData?: CampaignResource[][]) => {
        if (previousPageData && !previousPageData.length) return null
        if (!currentWorkspace?.id) return null
        return [
            route('workspaces.campaigns.index', {
                workspaceId: currentWorkspace?.id,
                include: 'payouts.country',
                filter: campaignFilters,
                page: {
                    number: pageIndex + 1,
                    size: 5
                }
            }),
            false
        ]
    }, [currentWorkspace?.id, campaignFilters])

    const { data: campaignsData, size: campaignsDataSize, setSize: setCampaignsDataSize, mutate: refreshCampaigns, isLoading: isCampaignsLoading } = useSWRInfinite(campaignsRequest)

    const storeCampaign = useCallback(async (data: StoreCampaignDataType) => {
        await api.post(route('workspaces.campaigns.store', {workspaceId: currentWorkspace?.id}), data)
        await refreshCampaigns()
    }, [currentWorkspace?.id, refreshCampaigns])

    const updateCampaignStatus = useCallback(async (campaignId: string, status: 'active'|'paused') => {
        await api.patch(route('campaigns.update', {campaignId}), {activity_status: status})
    }, [])

    const contextValues = useMemo(
        () => ({
            campaignsData,
            campaignsDataSize,
            setCampaignsDataSize,
            storeCampaign,
            updateCampaignStatus,
            campaignFilters,
            setCampaignFilters
        }),
        [campaignsData, storeCampaign, updateCampaignStatus, campaignFilters, setCampaignsDataSize],
    )

    return <CampaignsContext.Provider value={contextValues}>{children}</CampaignsContext.Provider>
}

export const useCampaigns = () => {
    const campaignsContext = useContext(CampaignsContext)

    if (!campaignsContext) {
        throw new Error('useCampaigns must be used within an CampaignsProvider')
    }

    return campaignsContext
}
export default CampaignsContext
