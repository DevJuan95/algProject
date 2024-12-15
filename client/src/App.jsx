import './App.css';
import '@mantine/core/styles.css';
import {MantineProvider} from '@mantine/core';
import {RouterProvider} from "@tanstack/react-router";
import {router} from './routes.jsx';
import {QueryClient, QueryClientProvider} from '@tanstack/react-query';

import {AuthProvider, useAuth} from "./context/AuthContext.jsx";

const queryClient = new QueryClient();

function InnerApp() {
    const {user} = useAuth();

    return (
        <RouterProvider router={router} context={user}/>
    );
}

export default function App() {
    return (
        <QueryClientProvider client={queryClient}>
            <AuthProvider>
                <MantineProvider>
                    <InnerApp/>
                </MantineProvider>
            </AuthProvider>
        </QueryClientProvider>
    );
}
