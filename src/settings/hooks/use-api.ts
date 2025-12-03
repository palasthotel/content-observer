import {useEffect, useState} from "@wordpress/element";
import {fetchSites, getTestSite, getTestSiteById, postSites} from "../data/api";
import {isValidHttpUrl} from "../data/url";
import {Site} from "../../@types/Settings";

type SitesHookResult = {
    sites: Site[];
    isFetching: boolean;
    isSaving: boolean;
    update: (args: {dirtySites: Site[]; deletes: (number|null)[]}) => void;
    error: string;
}
export const useSites = (): SitesHookResult => {

    const [fetchState, setFetchState] = useState<number>(1);
    const [isFetching, setIsFetching] = useState<boolean>(false);
    const [isSaving, setIsSaving] = useState<boolean>(false);
    const [state, setState] = useState<Site[]>([]);
    const [error, setError] = useState<string>("");

    useEffect(() => {
        setIsFetching(true);
        fetchSites().then(sites => {
            setIsFetching(false);
            setState(sites);
        });
    }, [fetchState]);

    return {
        sites: state,
        error,
        isFetching,
        isSaving,
        update: ({dirtySites, deletes}) => {
            setIsSaving(true);
            postSites({
                dirtySites,
                deletes,
            }).then(sites => {
                setIsSaving(false);
                setState(sites);
            }).catch(error => {
                console.error(error);
                setError(error.message);
            });
        },
    };
}

type SiteTestHookResult = {
    isSuccess: boolean;
    isTesting: boolean;
    testAgain: () => void;
}
export const useSiteTest = (
    site_url: string,
    site_api_key?: string
): SiteTestHookResult => {

    const [isTesting, setIsTesting] = useState(false);
    const [isSuccess, setIsSuccess] = useState(true);
    const [testAgain, setTestAgain] = useState(1);

    useEffect(()=>{
        if(isTesting) return;

        if(!isValidHttpUrl(site_url)){
            setIsSuccess(false);
            return;
        }

        if(!site_api_key || site_api_key.length <= 0){
            setIsSuccess(false);
            return;
        }

        setIsTesting(true);
        const {promise, cancel} = getTestSite({site_url, site_api_key});

        promise.then((success)=>{
            setIsTesting(false);
            setIsSuccess(success === true);
        });

        return ()=> cancel();

    }, [site_url, site_api_key, testAgain]);

    return {
        isSuccess,
        isTesting,
        testAgain: ()=> setTestAgain(t=>t+1),
    }
}

type SiteTestByIdHookResult = {
    isSuccess: boolean;
    isTesting: boolean;
    testAgain: () => void;
}
export const useSiteTestById = (
    site_id: string
): SiteTestHookResult => {

    const [isTesting, setIsTesting] = useState(false);
    const [isSuccess, setIsSuccess] = useState(true);
    const [testAgain, setTestAgain] = useState(1);

    useEffect(()=>{
        if(isTesting) return;

        setIsTesting(true);
        getTestSiteById(site_id).then((success)=>{
            setIsTesting(false);
            setIsSuccess(success === true);
        });

    }, [site_id, testAgain]);

    return {
        isSuccess,
        isTesting,
        testAgain: ()=> setTestAgain(t=>t+1),
    }
}