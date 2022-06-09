import {useEffect, useState} from "react";
import {ModificationsResponse} from "../@types/Modification";
import {fetchModifications} from "../store/modifications-store";

type UseModifications = {
    modifications: ModificationsResponse,
    canLoadMore: boolean
    loadMore: ()=> void
}

export const useModifications = (): UseModifications => {
    const [page, setPage] = useState(1);
    const [state, setState] = useState<ModificationsResponse>({
        mods: [],
        page: -1,
        pages: 0,
        success: true
    });

    useEffect(()=>{
        fetchModifications({
            page,
        }).then(response => {
            if(response.success){
                setState({
                    ...state,
                    page: response.page,
                    pages: response.pages,
                    mods: [
                        ...state.mods,
                        ...response.mods,
                    ]
                });
            }
        });
    }, [page]);

    return {
        modifications: state,
        canLoadMore: state.page < state.pages,
        loadMore: ()=> {
            setPage(page+1);
        }
    };
}