import {defineNode, NodeInterface, NumberInterface, SelectInterface, TextInterface} from "baklavajs";

export default defineNode({
    type: "MyNode",
    inputs: {
        number1: () => new NumberInterface("Number", 1),
        number2: () => new NumberInterface("Number", 10),
        number3: () => new TextInterface("Text","123"),
        operation: () => new SelectInterface("Operation", "Add", ["Add", "Subtract"]).setPort(false),
    },
    outputs: {
        output: () => new NodeInterface("Output", 0),
    },
});
