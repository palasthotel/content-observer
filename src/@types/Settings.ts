
export type SiteRelation = "observer" | "observable" | "both";

export type Site = {
    id?: number | null
    url: string
    slug: string
    api_key?: string
    relation_type?: SiteRelation
    registration_time?: number
    last_notification_time?: number
}