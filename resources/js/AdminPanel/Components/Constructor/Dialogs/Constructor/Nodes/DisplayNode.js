import { defineNode, NodeInterface, TextInterface } from "baklavajs";

export const DisplayNode = defineNode({
    type: "DisplayNode",
    title: "Display",
    inputs: {
        value: () => new NodeInterface("Value", "1")
    },
    outputs: {
        display: () => new TextInterface("Display", "")
    },
    calculate({ value }) {
        return {
            display: typeof value === "number" ? value.toFixed(3) : String(value)
        };
    }
});
