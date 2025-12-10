import {SiteResponse} from "../@types/Modification";
import apiFetch from "@wordpress/api-fetch";

export const fetchSites = async () => {
    return await apiFetch<SiteResponse[]>({
        path: "/content-sync/v1/sites",
    });
}