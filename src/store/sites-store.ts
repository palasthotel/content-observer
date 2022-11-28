import {ModificationsQuery, ModificationsResponse, SiteResponse} from "../@types/Modification";
import apiFetch from "@wordpress/api-fetch";
import {getApiKey} from "./global";

export const fetchSites = async () => {
    return await apiFetch<SiteResponse[]>({
        path: "/content-sync/v1/sites",
    });
}