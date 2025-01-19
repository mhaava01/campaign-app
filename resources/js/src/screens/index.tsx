import { JSX, useMemo } from "react";
import { Spinner } from "@nextui-org/react";
import { Link, Outlet, Route, Routes } from "react-router";
import CampaignsScreen from "./campaigns/campaigns.screen";
import LoginScreen from "./login.screen";
import Header from "../components/header";
import { AUTH_STATE, useAuth } from "../contexts/auth.context";
import { CampaignsContextProvider } from "./campaigns/campaigns.context";

const Screens = () => {
    const { user, authenticationState } = useAuth();

    const authenticatedLayout = useMemo<JSX.Element>(() => {
        if (authenticationState === AUTH_STATE.PENDING)
            return (
                <div className="min-h-screen w-full grid place-items-center">
                    <Spinner size="lg" />
                </div>
            );
        if (authenticationState === AUTH_STATE.AUTHENTICATED) {
            return (
                <div className="min-h-screen bg-background">
                    <Header />
                    <main className="container mx-auto p-6">
                        <Outlet />
                    </main>
                </div>
            );
        }

        return <LoginScreen />;
    }, [authenticationState]);

    return (
        <Routes>
            {/* Authenticated Paths */}
            <Route path="/" element={authenticatedLayout}>
                <Route
                    index
                    element={
                        <CampaignsContextProvider>
                            <CampaignsScreen />
                        </CampaignsContextProvider>
                    }
                />
            </Route>
            <Route
                path="*"
                element={
                    <div className="size-full grid grid-cols-1 grid-rows-2 place-items-center">
                        <span>404</span>
                        <Link to="/">Go Home</Link>
                    </div>
                }
            />
        </Routes>
    );
};

export default Screens;
