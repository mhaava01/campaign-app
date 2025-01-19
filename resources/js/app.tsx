import { StrictMode } from "react";
import { Container, createRoot } from "react-dom/client";
import { NextUIProvider } from "@nextui-org/react";
import "./main.css";
import { BrowserRouter } from "react-router";
import Screens from "./src/screens";
import { SWRConfig } from "swr";
import api from "./src/api";
import { AuthContextProvider } from "./src/contexts/auth.context";
import { Toaster } from "sonner";
import * as React from "react";

createRoot(document.getElementById("root") as Container).render(
    <StrictMode>
        <NextUIProvider>
            <SWRConfig
                value={{
                    fetcher: ([key, paginated = false]: [
                        key: string,
                        readOne: boolean,
                    ]) =>
                        api
                            .get(key)
                            .then((response) =>
                                paginated
                                    ? response?.data
                                    : response?.data?.data,
                            ),
                }}
            >
                <AuthContextProvider>
                    <BrowserRouter>
                        <Toaster richColors />

                        <Screens />
                    </BrowserRouter>
                </AuthContextProvider>
            </SWRConfig>
        </NextUIProvider>
    </StrictMode>,
);
