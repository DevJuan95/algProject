import {Container, rem, Title, Button, Center, Alert} from '@mantine/core';
import {NumberInput} from '@mantine/core';
import {useState} from 'react';
import {IconGrill} from '@tabler/icons-react';
import {useMutation} from '@tanstack/react-query';
import axiosClient from '../api/axiosClient.js';
import GenericLoader from "../components/GenericLoader/GenericLoader.jsx";
import {Link} from "@tanstack/react-router";

export function HomePage() {
    const [value, setValue] = useState(1);
    const icon = <IconGrill style={{width: rem(20), height: rem(20)}} stroke={1.5}/>;

    const orderMutation = useMutation({
        mutationFn: (quantity) =>
            axiosClient.post('/kitchen/orders',
                {quantity}
            ),
    });
    const handleOrder = () => {
        orderMutation.mutate(value);
    };
    return (
        <Center>
            <Container>
                <Title order={3}>Welcome!</Title>
                <NumberInput
                    leftSection={icon}
                    value={value}
                    onChange={setValue}
                    label="Enter the number of meals you want to order, enter a number from 1 to 5"
                    placeholder="Enter a number from 1 to 5"
                    min={1}
                    max={5}
                />
                <Center mt="sm">
                    <Button
                        variant="filled"
                        color="#30aba9"
                        onClick={handleOrder}
                        disabled={orderMutation.isPending}
                    >
                        {orderMutation.isPending ? 'PROCESSING...' : 'ORDER NOW'}
                    </Button>
                </Center>
                {orderMutation.isPending ? <GenericLoader/> : null}

                {orderMutation.isError && (
                    <div style={{color: 'red'}}>
                        <p>Error: {orderMutation.error?.response?.data?.message || orderMutation.error.message}</p>
                    </div>
                )}
                {orderMutation.isSuccess && (
                    <div>
                        <Alert color="green" mb="md" mt="1rem">
                            <p style={{textAlign: "center"}}>
                                Order placed successfully please go to the <Link to="/ordenes">Orders</Link> page <br/>
                                to check the status and the meals created or you can order more meals.
                            </p>
                        </Alert>
                    </div>
                )}
            </Container>
        </Center>
    );
}
