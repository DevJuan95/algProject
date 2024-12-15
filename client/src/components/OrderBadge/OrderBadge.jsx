import {Badge} from '@mantine/core';

const colors = {
    created: 'orange',
    delivered: 'teal',
    cancelled: 'red',
};
export default function OrderBadge({status = 'created'}) {
    const color = colors[status];
    return <Badge color={color}>{status}</Badge>;
}