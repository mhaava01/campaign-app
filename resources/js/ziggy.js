const Ziggy = {
    url: "http:\/\/localhost",
    port: null,
    defaults: {},
    routes: {
        "l5-swagger.default.api": {
            uri: "api\/v1\/docs",
            methods: ["GET", "HEAD"],
        },
        "l5-swagger.default.docs": {
            uri: "docs\/{jsonFile?}",
            methods: ["GET", "HEAD"],
            parameters: ["jsonFile"],
        },
        "l5-swagger.default.asset": {
            uri: "docs\/asset\/{asset}",
            methods: ["GET", "HEAD"],
            parameters: ["asset"],
        },
        "l5-swagger.default.oauth2_callback": {
            uri: "api\/oauth2-callback",
            methods: ["GET", "HEAD"],
        },
        "user.show": { uri: "api\/v1\/user", methods: ["GET", "HEAD"] },
        "workspaces.campaigns.index": {
            uri: "api\/v1\/workspaces\/{workspaceId}\/campaigns",
            methods: ["GET", "HEAD"],
            parameters: ["workspaceId"],
        },
        "workspaces.campaigns.store": {
            uri: "api\/v1\/workspaces\/{workspaceId}\/campaigns",
            methods: ["POST"],
            parameters: ["workspaceId"],
        },
        "campaigns.update": {
            uri: "api\/v1\/campaigns\/{campaignId}",
            methods: ["PATCH"],
            parameters: ["campaignId"],
        },
        "countries.index": {
            uri: "api\/v1\/countries",
            methods: ["GET", "HEAD"],
        },
        "auth.login": { uri: "api\/v1\/login", methods: ["POST"] },
        "auth.logout": { uri: "api\/v1\/logout", methods: ["POST"] },
        "auth.csrf": { uri: "api\/v1\/csrf-cookie", methods: ["GET", "HEAD"] },
        "storage.local": {
            uri: "storage\/{path}",
            methods: ["GET", "HEAD"],
            wheres: { path: ".*" },
            parameters: ["path"],
        },
    },
};
if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
