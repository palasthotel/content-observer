import apiFetch from "@wordpress/api-fetch";

export const fetchSites = ()=>{
    const {apiNamespace} = ContentSync;
    return apiFetch({path:`/${apiNamespace}/sites`}).then(data=>{
        console.debug(data);
        return data;
    });
}