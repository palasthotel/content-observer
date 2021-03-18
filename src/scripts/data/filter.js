
const filterByRelationType = (sites, type)=> sites.filter(({relation_type})=>relation_type === type || relation_type === "both");
export const filterObservers = (sites)=> filterByRelationType(sites, "observer");
export const filterObservables = (sites)=> filterByRelationType(sites, "observable");
