import {Pagination} from '@mantine/core';

export default function GenericPaginator({total = 0, page = 1, onPageChange}) {
    return (
        <Pagination
            total={total}
            value={page}
            onChange={onPageChange}
            color="#00b19d"
        />
    );
}