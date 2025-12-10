import {ModificationsQuery, ModificationsResponse} from "../@types/Modification";
import apiFetch from "@wordpress/api-fetch";
import {getAjaxUrl, getApiKeyPair} from "./global";

export const fetchModifications = async (query: ModificationsQuery = {}) => {
    const params = new URLSearchParams({
        ...(query as Record<string, string>),
        ...getApiKeyPair(),
    });
    return await apiFetch<ModificationsResponse>({
        path: "/content-sync/v1/modifications?" + params.toString(),
    });
}

type FetchSiteModificationsResponse = {
    success: boolean
    data: {
        number_of_modifications: number
    }
}
export const fetchSiteModifications = (site_id: number) => {
    const params = new URLSearchParams({
        action: "content_observer_fetch_modifications",
        site_id: site_id.toString(),
    });
    return fetch(
        getAjaxUrl() + "?" + params.toString(),
        {
            credentials: "include",
        }
    )
        .then(res => res.json())
        .then(json => json as FetchSiteModificationsResponse)
        .catch(_ => ({success: false, data: {number_of_modifications: 0}}) as FetchSiteModificationsResponse);
}

type NotifyResponse = { success: true } | { success: false, data: { message: string } }

export const notify = (site_id: number) => {
    const params = new URLSearchParams({
        action: "content_observer_notify",
        site_id: site_id.toString(),
    });

    return fetch(
        getAjaxUrl() + "?" + params.toString(),
        {
            credentials: "include",
        }
    )
        .then(res => res.json())
        .then(json => json as NotifyResponse)
        .catch(reason => ({success: false, data: {message: reason.toString()}}) as NotifyResponse);
}

type ApplyModificationsResponse = {
    success: boolean
    data: {
        number_of_modifications: number
    }
}
export const applyModifications = (since: number) => {
    const params = new URLSearchParams({
        action: "content_observer_apply",
        since: since.toString(),
    });
    return fetch(
        getAjaxUrl() + "?" + params.toString(),
        {
            credentials: "include",
        }
    )
        .then(res => res.json())
        .then(json => json as ApplyModificationsResponse)
        .catch(reason => ({success: false, data: {number_of_modifications: 0}}) as ApplyModificationsResponse);
}
