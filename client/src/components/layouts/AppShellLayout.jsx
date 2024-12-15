import {AppShell, Burger, Group} from '@mantine/core';
import {useDisclosure} from '@mantine/hooks';
import {Navbar} from "../Navbar/Navbar.jsx";
import {AlegraLogo} from "../Logo/AlegraLogo.jsx";
import {Outlet} from "@tanstack/react-router";

const LayoutComponent = () => {
    const [opened, {toggle}] = useDisclosure();
    return (
        <AppShell
            header={{height: 60}}
            navbar={{
                width: { base: 400 },
                breakpoint: 'sm',
                collapsed: { mobile: !opened },
            }}
            padding="md"
        >
            <AppShell.Header>
                <Group h="100%" px="md">
                    <Burger opened={opened} onClick={toggle} hiddenFrom="sm" size="sm"/>
                    <AlegraLogo/>
                </Group>
            </AppShell.Header>
            <AppShell.Navbar p="md">
                <Navbar/>
            </AppShell.Navbar>
            <AppShell.Main>
                <Outlet/>
            </AppShell.Main>
        </AppShell>
    );
};

export default LayoutComponent;