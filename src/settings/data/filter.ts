import {Site, SiteRelation} from "../../@types/Settings";

const filterByRelationType = (sites: Site[], type: SiteRelation)=> sites.filter(({relation_type})=>relation_type === type || relation_type === "both");
export const filterObservers = (sites: Site[])=> filterByRelationType(sites, "observer");
export const filterObservables = (sites: Site[])=> filterByRelationType(sites, "observable");
