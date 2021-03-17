import apiFetch from "@wordpress/api-fetch";

export const fetchSites = ()=>{
    console.debug("FETCH")
    const {apiNamespace} = ContentSync;
    return apiFetch({path:`/${apiNamespace}/sites`}).then(data=>{
        console.log(data);
        return data;
    });
}