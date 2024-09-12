import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import metadata from "./block.json";

import Edit from "./edit";

// Register Block
registerBlockType(metadata.name, {
  edit: Edit,
  save() {
    return null; // Server-side rendering
  },
});
