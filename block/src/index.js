import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import Edit from "./edit";
import metadata from "./block.json";

// Register Block
registerBlockType(metadata.name, {
  title: __(metadata.title, "my-plugin"),
  icon: metadata.icon,
  category: metadata.category,
  supports: {
    align: ["wide", "full"], // Enable wide and full alignment options
  },
  attributes: {
    align: {
      type: "string",
      default: "wide", // Optional: set default alignment to 'wide'
    },
  },
  edit: Edit,
  save() {
    return null; // This block will be rendered dynamically on the front end
  },
});
