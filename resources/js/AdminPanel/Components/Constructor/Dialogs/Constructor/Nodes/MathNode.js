import {
    defineNode,
    NumberInterface,
    SelectInterface,
    NodeInterface,
    TextInterface,
    ButtonInterface,
    CheckboxInterface,
    IntegerInterface,
    SliderInterface,
    TextInputInterface,
    TextareaInputInterface
} from "baklavajs";

let index = 0;
let self = null;
export const MathNode = defineNode({
    type: "MathNode",
    title: "Math",
    onCreate(){

    },
    inputs: {
        operation: () =>
            new SelectInterface("Operation", "Add", ["Add", "Subtract"]).setPort(
                false
            ),
        num1: () => new NumberInterface("Num 1", 1),
        num2: () => new NumberInterface("Num 2", 1),
        num3: () => new NodeInterface("Num 3", "0"),
        but: () =>
            new ButtonInterface("button", () => {
                return {
                    value: 0,
                }
            }),
        eck: () => new CheckboxInterface("Name", true),
        int: () => new IntegerInterface("Name", 5),
        sli: () => new SliderInterface("Name", 0.5, 0, 1),
        text: () => new TextInterface("Name", "Hello World!"),
        tip: () => new TextInputInterface("Name", "Edit me"),
        td: () => new TextareaInputInterface("Name", "Edit me")
    },
    outputs: {
        result: () => new NodeInterface("Result"),
        test:()=> new NodeInterface("Operation1")
    },
    calculate(state, {num1, num2, num3, operation}) {

        console.log("test")

        if (operation === "Add") {


            return {
                result: num1 + num2,
                test:0,
                num3: typeof num3 === "number" ? num3.toFixed(3) : String(num3)
            };
        } else {
            return {result: num1 - num2, test:1};
        }
    }
});
