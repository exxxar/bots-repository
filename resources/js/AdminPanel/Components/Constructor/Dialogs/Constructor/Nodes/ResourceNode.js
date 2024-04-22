import { Node } from "@baklavajs/core";
import InputText from "primevue/inputtext";
import {NumberInterface} from "baklavajs";

export class ResourceNode extends Node {
    constructor() {
        super();
        this.type = "ResourceNode";
        this.name = "Resource";
        this.addInput(new NumberInterface("Input", 0));
        this.addOption("test", "NewColOption", "def", "sidebar", {
            myNode: this
        });
        this.addType(this);
        this.addOutputInterface("Result");
        this.addOutputInterface("Export", { value: this.interfaces });

        this.addOption("ValueText", "TextOption", 100);
    }

    addType(cls) {
        cls.addInputInterface("rn" + Math.random(), "FieldOption", "Integer", {
            items: [
                "BigInteger",
                "Boolean",
                "Date",
                "DateTime",
                "Enum",
                "Float",
                "Integer",
                "Interval",
                "LargeBinary",
                "MatchType",
                "Numeric",
                "PickleType",
                "SchemaType",
                "SmallInteger",
                "String",
                "Text",
                "Time",
                "Unicode",
                "UnicodeText"
            ]
        });
    }

    calculate() {
        this.getInterface("Result").value = "10";
    }

    onDestroy() {
    }

    onPlaced() {
    }
}
