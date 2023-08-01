import {useEffect, useState} from "react";
import {ModificationsResponse} from "../@types/Modification";
import {applyModifications, fetchModifications, fetchSiteModifications, notify} from "../store/modifications-store";

const initialState: ModificationsResponse = {
    mods: [],
    page: -1,
    pages: 0,
    success: true
}

export const useModifications = (site_id: number) => {
    const [reload, setReload] = useState(0);
    const [page, setPage] = useState(1);
    const [state, setState] = useState<ModificationsResponse>({...initialState});

    useEffect(() => {
        setPage(1);
        setState({...initialState})
    }, [site_id]);

    useEffect(() => {
        fetchModifications({
            site_id,
            page,
        }).then(response => {
            if (response.success) {
                setState(prev => ({
                    ...prev,
                    page: response.page,
                    pages: response.pages,
                    mods: [
                        ...prev.mods,
                        ...response.mods,
                    ]
                }));
            }
        });
    }, [site_id, page, reload]);

    const doReload = () => {
        setState({
            ...state,
            mods: [],
        })
        setReload(prev => prev + 1);
        setPage(1);
    }

    return {
        modifications: state,
        canLoadMore: state.page < state.pages,
        loadMore: () => {
            setPage(page + 1);
        },
        fetch: async () => {
            const response = await fetchSiteModifications(site_id);
            if (response.success && response.data.number_of_modifications > 0) {
                // fetch update
                doReload();
            }
            return response;
        },
        notify: async () => {
            return await notify(site_id)
        },
        applySince: async (since: number) => {
            return applyModifications(since)
        }
    };
}
