export type Site = {
    id?: number | null
    url: string
    slug: string
    api_key?: string
    relation_type?: "observer" | "observable" | "both"
    registration_time?: number
    last_notification_time?: number
}