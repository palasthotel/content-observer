import {ModificationsQuery, ModificationsResponse} from "../@types/Modification";
import apiFetch from "@wordpress/api-fetch";
import {getAjaxNonce, getAjaxUrl} from "./global";

export const fetchModifications = async (query: ModificationsQuery = {}) => {
    const params = new URLSearchParams({
        ...(query as Record<string, string>),
    });
    return await apiFetch<ModificationsResponse>({
        path: "/content-sync/v1/modifications?" + params.toString(),
    });
}

// admin-ajax actions change data, so they are POSTed with a nonce
const postAjax = (action: string, data: Record<string, string>) => fetch(
    getAjaxUrl(),
    {
        method: "POST",
        credentials: "include",
        body: new URLSearchParams({
            action,
            _ajax_nonce: getAjaxNonce(),
            ...data,
        }),
    }
).then(res => res.json());

type FetchSiteModificationsResponse = {
    success: boolean
    data: {
        number_of_modifications: number
    }
}
export const fetchSiteModifications = (site_id: number) => {
    return postAjax("content_observer_fetch_modifications", {site_id: site_id.toString()})
        .then(json => json as FetchSiteModificationsResponse)
        .catch(_ => ({success: false, data: {number_of_modifications: 0}}) as FetchSiteModificationsResponse);
}

type NotifyResponse = { success: true } | { success: false, data: { message: string } }

export const notify = (site_id: number) => {
    return postAjax("content_observer_notify", {site_id: site_id.toString()})
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
    return postAjax("content_observer_apply", {since: since.toString()})
        .then(json => json as ApplyModificationsResponse)
        .catch(reason => ({success: false, data: {number_of_modifications: 0}}) as ApplyModificationsResponse);
}
