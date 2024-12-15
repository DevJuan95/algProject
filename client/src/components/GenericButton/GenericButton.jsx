import {Button} from "@mantine/core";

export default function GenericButton({
                                          children, onClick = () => {
    }
                                      }) {
    return (
        <Button
            onClick={onClick}
            variant="filled"
            color="#30aba9"
            size="compact-sm"
        >
            {children}
        </Button>
    );
}