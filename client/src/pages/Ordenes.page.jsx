import GenericTable from "../components/Table/GenericTable.jsx";
import {useQuery} from "@tanstack/react-query";
import {useState} from "react";
import GenericPaginator from "../components/GenericPaginator/GenericPaginator.jsx";
import {Center, Container} from "@mantine/core";
import {fetchOrders} from "../api/fetchOrders.js";
import GenericButton from "../components/GenericButton/GenericButton.jsx";
import GenericLoader from "../components/GenericLoader/GenericLoader.jsx";
import {IconRefresh} from '@tabler/icons-react'
import OrderBadge from "../components/OrderBadge/OrderBadge.jsx";

export function OrdenesPage() {
    const [page, setPage] = useState(1);

    const {data, isLoading, isError, refetch, isFetching} = useQuery({
        queryKey: ['orders', {page}],
        queryFn: fetchOrders,
        keepPreviousData: true,
    });

    if (isLoading) return <GenericLoader/>;
    if (isError) return <p>Error Loading the orders</p>;

    const headers = ['ID', 'Status', 'Meal Name', 'Created at', 'Ingredients'];

    const rows = data.data.map((order) => {
        const ingredients = (
            <ol>
                {order.ingredients.map((ingredient) => {
                    return (
                        <li key={ingredient.id}>
                            {ingredient.name} - Used quantity: {ingredient.quantity}
                        </li>
                    );
                })}
            </ol>
        );
        return [
            order.id,
            <OrderBadge status={order.status}/>,
            order.recipe,
            order.created_at,
            ingredients
        ];
    });

    const handlePageChange = (newPage) => {
        setPage(newPage);
        refetch();
    };
    return (
        <div>
            <Container mb="1rem">
                <h3>Orders</h3>
                <GenericButton onClick={refetch}>
                    <IconRefresh width="15px"/>
                </GenericButton>
            </Container>

            {isFetching ? (
                <GenericLoader/>
            ) : (
                <>
                    <GenericTable
                        headers={headers}
                        rows={rows}
                    />

                    <Container fluid pt="1rem">
                        <Center>
                            <GenericPaginator
                                page={data.meta.current_page}
                                total={data.meta.last_page}
                                onPageChange={handlePageChange}
                            />
                        </Center>
                    </Container>
                </>
            )}
        </div>
    );
}
