import classes from './Navbar.module.css';
import {Code, Group, Text} from '@mantine/core';
import {Link} from '@tanstack/react-router';

import {
    IconForklift,
    IconLogout,
    IconReceipt2,
    IconHome,
    IconList,
    IconSoup
} from '@tabler/icons-react';
import {useAuth} from "../../hooks/useAuth.jsx";
import {useNavigate} from "@tanstack/react-router";
import {useMutation} from "@tanstack/react-query";
import axiosClient from "../../api/axiosClient.js";

const data = [
    {link: '/', label: 'Home', icon: IconHome},
    {link: '/ordenes', label: 'Orders', icon: IconList},
    {link: '/compras', label: 'Purchases', icon: IconReceipt2},
    {link: '/bodega', label: 'Stock', icon: IconForklift},
    {link: '/recetas', label: 'Recipes', icon: IconSoup},
];

export function Navbar() {
    const {setToken} = useAuth();
    const logoutMutation = useMutation({
        mutationFn: () => axiosClient.post('/logout'),
        onSuccess: () => {
            setToken( () => null);
            localStorage.removeItem('token');
            navigate({ to: '/login' });
        },
        onError: () => {
            setToken(null);
            localStorage.removeItem('token');
            navigate({ to: '/login' });
        },
    });
    const navigate = useNavigate();
    const links = data.map((item) => (
        <Link
            className={classes.link}
            to={item.link}
            key={item.label}
        >
            <item.icon className={classes.linkIcon} stroke={1.5}/>
            <span>{item.label}</span>
        </Link>
    ));
    return (
        <nav className={classes.navbar}>
            <div className={classes.navbarMain}>
                <Group className={classes.header} justify="space-between">
                    <Text
                        size="xl"
                        fw={900}
                        variant="gradient"
                        gradient={{from: '#30aba9', to: '#4feda2', deg: 90}}
                    >
                        Lunch Day
                    </Text>
                    <Code fw={700}>v0.1.1</Code>
                </Group>
                {links}
            </div>

            <div className={classes.footer}>
                <a href="#" className={classes.link} onClick={logoutMutation.mutate}>
                    <IconLogout className={classes.linkIcon} stroke={1.5}/>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    );
}