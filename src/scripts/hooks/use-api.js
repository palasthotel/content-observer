import {useEffect, useState} from "react";
import {fetchSites} from "../data/api";

export const useSites = () => {

    const [fetchState, setFetchState] = useState(1);
    const [isFetching, setIsFetching] = useState(false);
    const [state, setState] = useState([]);

    useEffect(() => {
        setIsFetching(true);
        fetchSites().then(sites => {
            setIsFetching(false);
            setState(sites);
        });
    }, [fetchState]);

    return [
        state,
        isFetching,
        ()=> setFetchState(f=>f+1),
    ];
}