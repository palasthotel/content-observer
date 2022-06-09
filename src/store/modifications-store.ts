import {ModificationsQuery, ModificationsResponse} from "../@types/Modification";
import apiFetch from "@wordpress/api-fetch";
import {getApiKey} from "./global";

export const fetchModifications = async (query: ModificationsQuery = {}) => {
    const params = new URLSearchParams({
        ...(query as Record<string, string>),
        ...getApiKey(),
    });
    return await apiFetch<ModificationsResponse>({
        path: "/content-sync/v1/modifications?"+params.toString(),
    });
}