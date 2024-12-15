import {
    Button,
    Container,
    Paper,
    PasswordInput,
    TextInput,
    Title,
    Alert,
} from '@mantine/core';
import { useState } from 'react';
import { useMutation } from "@tanstack/react-query";
import axiosClient from "../api/axiosClient.js";
import {useAuth} from "../context/AuthContext.jsx";
import {useNavigate} from "@tanstack/react-router";

export default function LoginPage() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [generalError, setGeneralError] = useState('');
    const {setUser, setToken} = useAuth();
    const navigate = useNavigate();

    const loginMutation = useMutation({
        mutationFn: (credentials) => axiosClient.post('/login', credentials),
        onSuccess: async (data) => {
            localStorage.setItem('token', data.data.token);
            setUser(() => data.data.user);
            navigate({to: '/'});
        },
        onError: (error) => {
            setToken(null);
            localStorage.removeItem('token');
            const responseData = error.response?.data;
            if (responseData?.message) {
                setGeneralError(responseData.message);
            } else {
                setGeneralError('An unexpected error occurred.');
            }
        },
    });

    const handleLogin = async () => {
        setGeneralError('');
        loginMutation.mutate({ email, password });
    };

    return (
        <Container size={420} my={40}>
            <Title ta="center">Welcome!</Title>
            <Paper withBorder shadow="md" p={30} mt={30} radius="md">
                <TextInput
                    label="Email"
                    placeholder="test@example.com"
                    required
                    value={email}
                    onChange={(event) => setEmail(event.currentTarget.value)}
                />
                <PasswordInput
                    label="Password"
                    placeholder="password"
                    required
                    mt="md"
                    value={password}
                    onChange={(event) => setPassword(event.currentTarget.value)}
                />
                <Button
                    fullWidth
                    mt="xl"
                    onClick={handleLogin}
                    loading={loginMutation.isPending}
                    color="#30aba9">
                    Sign in
                </Button>

                {generalError && (
                    <Alert color="red" mb="md" mt="1rem">
                        {generalError}
                    </Alert>
                )}
            </Paper>
        </Container>
    );
}
