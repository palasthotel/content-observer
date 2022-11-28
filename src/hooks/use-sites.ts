import {useEffect, useState} from "react";
import {ModificationsResponse, SiteResponse} from "../@types/Modification";
import {fetchModifications} from "../store/modifications-store";
import {fetchSites} from "../store/sites-store";

type UseSites = SiteResponse[]

export const useSites = (): UseSites => {
    const [state, setState] = useState<SiteResponse[]>([]);

    useEffect(()=>{
        fetchSites().then(setState);
    }, []);

    return state;
}