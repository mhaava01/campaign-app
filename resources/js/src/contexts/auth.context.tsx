import {
    createContext,
    useMemo,
    useContext,
    useCallback,
    useEffect,
    JSX,
} from "react";
import useSWR from "swr";
import api, { route } from "../api";
import { components } from "../schema";

type UserResource = components["schemas"]["UserResource"];
type WorkspaceResource = components["schemas"]["WorkspaceResource"];
export enum AUTH_STATE {
    NOT_AUTHENTICATED = "not_authenticated",
    AUTHENTICATED = "authenticated",
    PENDING = "pending",
}

type AuthContextType = {
    authenticationState: AUTH_STATE;
    authenticate: (
        email: string,
        password: string,
        remember?: boolean,
    ) => Promise<void>;
    deauthenticate?: () => Promise<void>;
    currentWorkspace?: WorkspaceResource;
    user?: Partial<UserResource>;
};

const AuthContext = createContext<AuthContextType>({
    authenticationState: AUTH_STATE.PENDING,
    authenticate: () => Promise.resolve(),
    deauthenticate: () => Promise.resolve(),
    currentWorkspace: undefined,
    user: undefined,
});
export const AuthContextProvider: ({
    children,
}: {
    children: JSX.Element;
}) => JSX.Element = ({ children }) => {
    const {
        data: user,
        mutate: refreshUser,
        isLoading: isUserLoading,
    } = useSWR<Partial<UserResource>>([
        route("user.show", { include: "workspaces" }),
        false,
    ]);

    const currentWorkspace = useMemo(() => {
        // get first workspace for now
        return user?.workspaces?.[0];
    }, [user?.workspaces]);

    const authenticationState = useMemo(() => {
        if (user) {
            return AUTH_STATE.AUTHENTICATED;
        }
        // if (isUserLoading) {
        //     return AUTH_STATE.PENDING
        // }
        return AUTH_STATE.NOT_AUTHENTICATED;
    }, [user]);

    const authenticate = useCallback(
        async (email: string, password: string, remember?: boolean) => {
            await api.post(route("auth.login"), { email, password, remember });
            await refreshUser();
        },
        [refreshUser],
    );

    const deauthenticate = useCallback(async () => {
        await api.post(route("auth.logout"));
        await refreshUser(undefined);
    }, [refreshUser]);

    useEffect(() => {
        api.get(route("auth.csrf")).catch(console.error);
    }, []);

    const contextValues = useMemo(
        () => ({
            user,
            authenticationState,
            authenticate,
            deauthenticate,
            currentWorkspace,
        }),
        [
            user,
            authenticationState,
            authenticate,
            deauthenticate,
            currentWorkspace,
        ],
    );

    return (
        <AuthContext.Provider value={contextValues}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const authContext = useContext(AuthContext);

    if (!authContext) {
        throw new Error("useAuth must be used within an AuthProvider");
    }

    return authContext;
};
export default AuthContext;
