import {Avatar, Button, Dropdown, DropdownItem, DropdownMenu, DropdownTrigger, Input} from "@nextui-org/react";
import {useAuth} from "../contexts/auth.context";
import {JSX} from "react";

const Header = () => {
    const {currentWorkspace, user, deauthenticate} = useAuth()

    return (
        <header className="border-b bg-background">
            <div className="container mx-auto">
                <div className="flex px-2 h-16 items-center justify-between gap-4">
                    <div className="flex items-center gap-2">
                        <div
                            className="h-8 w-8 rounded-md bg-primary flex items-center justify-center text-primary-foreground font-bold">
                            C
                        </div>
                        <span className="font-semibold">Campaignify</span>
                    </div>
                    <div className="flex gap-2">
                        <Dropdown>
                            <DropdownTrigger>
                                <Button>
                                    {currentWorkspace?.name ?? ''}
                                </Button>
                            </DropdownTrigger>
                            <DropdownMenu>
                                {user?.workspaces?.map((workspace): JSX.Element => (
                                    <DropdownItem key={`workspace-${workspace?.id}`}>{workspace?.name}</DropdownItem>
                                ))}
                            </DropdownMenu>
                        </Dropdown>
                        <Dropdown>
                            <DropdownTrigger>
                                <Avatar name={user?.name} />
                            </DropdownTrigger>
                            <DropdownMenu>
                                <DropdownItem onPress={deauthenticate} key="log-out">Log out</DropdownItem>
                            </DropdownMenu>
                        </Dropdown>
                    </div>

                </div>
            </div>
        </header>
    )
}

export default Header
