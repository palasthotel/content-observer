
export type ModificationType = "create" | "update" | "delete";

export type ModificationsQuery = {
    number?: number
    page?: number
    site_id?: number
    since?: number
    content_id?: number
    content_types?: string[]
}

export type ModificationResponse = {
    site_id: number
    content_id: number
    content_type: string
    type: ModificationType
    modified: number
}

export type ModificationsResponse = {
    success: boolean
    mods: ModificationResponse[]
    page: number
    pages: number
}