import {useEffect, useState} from "react";
import {fetchSites} from "../data/api";

export const useSites = () => {

    const [fetchState, setFetchState] = useState(1);
    const [state, setState] = useState([]);

    useEffect(() => {
        fetchSites().then(sites => {
            setState(sites);
        });
    }, [fetchState]);

    return [
        state,
        ()=> setFetchState(f=>f+1),
    ];
}