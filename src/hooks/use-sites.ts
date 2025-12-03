import {useEffect, useState} from "react";
import {SiteResponse} from "../@types/Modification";
import {fetchSites} from "../store/sites-store";

type UseSites = SiteResponse[]

export const useSites = (): UseSites => {
    const [state, setState] = useState<SiteResponse[]>([]);

    useEffect(()=>{
        fetchSites().then(setState);
    }, []);

    return state;
}

export const useSelectSite = () => {
    const cacheKey = "content-observer:modifications(selected_site_id)"
    const [state, setState] = useState(localStorage.getItem(cacheKey) ?? "0");

    return [
        state,
        (id: string)=>{
            localStorage.setItem(cacheKey, id)
            setState(id);
        }
    ] as const
}
