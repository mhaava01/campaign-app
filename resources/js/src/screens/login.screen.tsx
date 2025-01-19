import { FormEventHandler, useCallback, useState } from "react";
import { Button, Input } from "@nextui-org/react";
import { useAuth } from "../contexts/auth.context";
import { toast } from "sonner";

const LoginScreen = () => {
    const { authenticate } = useAuth();
    const [authData, setAuthData] = useState({ email: "", password: "" });
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);

    const signIn = useCallback<FormEventHandler<HTMLFormElement>>(
        (event) => {
            event.preventDefault();
            authenticate(authData.email, authData.password)
                .catch((error: any) =>
                    toast.error("Sign in failed!", {
                        description:
                            error?.response?.data?.message ??
                            error?.message ??
                            "Unknown error",
                    }),
                )
                .finally(() => setIsSubmitting(false));
        },
        [authenticate, authData.email, authData.password],
    );

    return (
        <main className="min-h-screen size-full grid place-items-center bg-gray-800">
            <div className="h-60 w-full max-w-96 flex flex-col bg-gray-50 px-6 rounded-lg">
                <div className="py-4 w-full text-center text-xl font-bold text-gray-800">
                    <span>Campaign Management Tool</span>
                </div>
                <form className="flex flex-col gap-2" onSubmit={signIn}>
                    <Input
                        autoComplete="username"
                        placeholder="Email"
                        variant="bordered"
                        onValueChange={(email) =>
                            setAuthData((prev) => ({ ...prev, email }))
                        }
                    />
                    <Input
                        type="password"
                        variant="bordered"
                        autoComplete="current-password"
                        placeholder="Password"
                        onValueChange={(password) =>
                            setAuthData((prev) => ({ ...prev, password }))
                        }
                    />
                    <Button
                        color="primary"
                        type="submit"
                        className="mt-4"
                        isDisabled={
                            !authData?.email ||
                            !authData?.password ||
                            isSubmitting
                        }
                    >
                        Sign In
                    </Button>
                </form>
            </div>
        </main>
    );
};

export default LoginScreen;
